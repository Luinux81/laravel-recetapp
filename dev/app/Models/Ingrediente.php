<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ingrediente extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $user_id;
    public $cat_id;

    public $nombre;
    public $descripcion;
    public $marca ;
    public $barcode;
    public $imagen;
    public $url;
        
    public $calorias;
    public $fat_total;
    public $fat_saturadas;
    public $fat_poliinsaturadas;
    public $fat_monoinsaturadas;
    public $fat_trans;
    public $colesterol;
    public $sodio;
    public $potasio;
    public $fibra;
    public $carb_total;
    public $carb_azucar;
    public $proteina;

    protected $guarded = [];


    public function user(){
        return $this->belongsTo(User::class);
    }

}
