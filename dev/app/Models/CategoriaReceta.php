<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoriaReceta extends Model
{
    use HasFactory;

    protected $table = "categorias_receta";

    protected $guarded = [];

    // public function ingredientes(){
    //     return $this->hasMany(Ingrediente::class);
    // }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function categoriaPadre(){
        return $this->belongsTo(CategoriaReceta::class);
    }

    public function categoriasHija($recursivo = false){
        $out = new Collection();
        
        $hijas = CategoriaReceta::where('catParent_id','=',$this->id)->get();        
        $visitados = collect();        
        $i = 0;

        if($recursivo){            
            while($i < $hijas->count()){
                $cat = $hijas->all()[$i];
                if (!$visitados->contains($cat)){
                    $visitados->add($cat);

                    $subHijas = CategoriaReceta::where('catParent_id','=',$cat->id)->get();
                    foreach ($subHijas as $key => $subHija) {
                        $hijas->add($subHija);
                    }
                }
                $i++;
            }  
            $out = $hijas;      
        }
        else{
            $out = $hijas;
        }

        return $out;
    }
}
