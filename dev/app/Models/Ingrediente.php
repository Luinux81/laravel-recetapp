<?php

namespace App\Models;

use App\Helpers\OpenFoodFacts;
use App\Helpers\Tools;
use App\Models\User;
use App\Models\Receta;
use App\Models\CategoriaIngrediente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Ingrediente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function categoria()
    {
        return $this->belongsTo(CategoriaIngrediente::class,"cat_id");
    }


    public function recetas()
    {
        return $this->belongsToMany(Receta::class, "ingrediente_receta")->withPivot('cantidad', 'unidad_medida');
    }


    /**
     * Función para eliminar completamente el ingrediente si no es publico y hacer softdelete si lo es
     * //TODO: Comprobar que el ingrediente no está en ninguna receta, si esta en una publica no borrar, si esta en receta borrar?
     * @return void
     */
    public function borradoCompleto()
    {
        if(!$this->esPublico()){
            if(!empty($this->imagen)){
                if(Storage::exists($this->imagen)){
                    Storage::delete($this->imagen);
                }
            }
            $this->forceDelete();
        }
        else{
            $this->delete();
        }
    }



    public function setImagen(UploadedFile $imagen = null)
    {
        Tools::checkOrFail($this, "public_edit");

        if ($imagen != null){
            if($this->esPublico()){
                $ruta = $imagen->store('public/ingredientes');
            }
            else{
                $ruta = $imagen->store('users/' . auth()->user()->id . '/ingredientes');
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
        if($this->esPublico()) return false;  // El ingrediente ya es público

        $res = DB::table('publish_queue')
            ->select('*')
            ->where('tipo', '=' ,'I')
            ->where('model_id', '=', $this->id);

        if($res->count()>0) return false;   // El ingrediente ya esta en la cola de publicación

        return true;
    }


    public static function getIngredienteOpenFoodFact($codigo) : Ingrediente
    {
        $data = OpenFoodFacts::getProductoOFFByCode($codigo);

        $ingrediente = new Ingrediente($data);

        return $ingrediente;
    }

}
