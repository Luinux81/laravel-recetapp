<?php

namespace App\Models;

use App\Models\User;
use App\Models\Ingrediente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoriaIngrediente extends Model
{
    use HasFactory;

    protected $table = "categorias_ingrediente";

    protected $guarded = [];

    public function ingredientes(){
        return $this->hasMany(Ingrediente::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function categoriaPadre(){
        return $this->belongsTo(CategoriaIngrediente::class);
    }

    public function categoriaHijas(){
        return $this->hasMany(CategoriaIngrediente::class,"catParent_id");
    }

    public function categoriaRaiz(){

    }
}
