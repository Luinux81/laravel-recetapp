<?php

namespace App\Models;

use App\Models\User;
use App\Models\PasoReceta;
use App\Models\Ingrediente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receta extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function ingredientes(){
        return $this->belongsToMany(Ingrediente::class, "ingrediente_receta")->withPivot('cantidad', 'unidad_medida');;
    }

    public function pasos(){
        return $this->hasMany(PasoReceta::class)->orderBy('orden');
    }
}
