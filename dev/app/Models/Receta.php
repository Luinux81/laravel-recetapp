<?php

namespace App\Models;

use App\Helpers\Tools;
use App\Models\User;
use App\Models\PasoReceta;
use App\Models\Ingrediente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Receta extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class, "ingrediente_receta")->withPivot('cantidad', 'unidad_medida');;
    }

    public function pasos()
    {
        return $this->hasMany(PasoReceta::class)->orderBy('orden');
    }


    public function borradoCompleto()
    {
        if($this->esPublico()){
            $this->delete();
        }
        else{
            if(!empty($this->imagen)){
                if(Storage::exists($this->imagen)){
                    Storage::delete($this->imagen);
                }
            }
    
            foreach ($this->pasos as $paso) {
                $paso->borradoCompleto();
            }
    
            $this->ingredientes()->sync([]);
    
            $this->forceDelete();
        }
    }


    public function setImagen(UploadedFile $imagen = null)
    {
        Tools::checkOrFail($this, "update");

        if ($imagen != null){
            if($this->esPublico()){
                $ruta = $imagen->store('public/recetas');
            }
            else{
                $ruta = $imagen->store('users/' . auth()->user()->id . '/recetas');
            }

            if(!empty($this->imagen)){
                if(Storage::exists($this->imagen)){
                    Storage::delete($this->imagen);
                }
            }

            $this->update(["imagen" => $ruta]);
        }

        return $ruta;
    }


    public function esPublico() : bool
    {
        if ($this->publicado == NULL){
            $this->publicado = false;
        }
        
        return $this->publicado;
    }

    public function esPublicable() : bool
    {   
        if($this->esPublico()) return false;  // La receta ya es pública

        $res = DB::table('publish_queue')
            ->select('*')
            ->where('tipo', '=' ,'R')
            ->where('model_id', '=', $this->id);

        if($res->count()>0) return false;   // La receta ya está en la cola de publicación

        return true;
    }
}
