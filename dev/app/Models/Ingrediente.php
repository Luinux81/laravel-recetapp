<?php

namespace App\Models;

use App\Helpers\OpenFoodFacts;
use App\Models\User;
use App\Models\Receta;
use App\Models\CategoriaIngrediente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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


    public static function getIngredienteOpenFoodFact($codigo) : Ingrediente
    {
        $data = OpenFoodFacts::getProductoOFFByCode($codigo);

        $ingrediente = new Ingrediente($data);

        return $ingrediente;
    }

}
