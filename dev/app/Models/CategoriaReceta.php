<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaReceta extends Categoria
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "categorias_receta";

    protected $guarded = [];


    protected function elementos()
    {
        return $this->recetas();    
    }

    public function padre()
    {
        return $this->belongsTo(CategoriaReceta::class);
    }

    public function recetas()
    {
        return $this->hasMany(Receta::class);
    }


    public static function arbol(Collection $categorias)
    {
        return Categoria::arbol($categorias);
    }
}
