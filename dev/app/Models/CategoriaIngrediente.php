<?php

namespace App\Models;

use App\Models\Ingrediente;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaIngrediente extends Categoria
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "categorias_ingrediente";

    protected $guarded = [];

    protected function elementos()
    {
        return $this->ingredientes();
    }

    public function padre()
    {
        return $this->belongsTo(CategoriaIngrediente::class);
    }

    public function ingredientes()
    {
        return $this->hasMany(Ingrediente::class, 'cat_id', 'id');
    }

    public static function arbol(Collection $categorias)
    {
        return Categoria::arbol($categorias);
    }
}

