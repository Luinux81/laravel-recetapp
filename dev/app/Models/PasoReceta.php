<?php

namespace App\Models;

use App\Models\Receta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PasoReceta extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "pasos_receta";
    protected $guarded = [];

    public function receta(){
        return $this->belongsTo(Receta::class);
    }
}