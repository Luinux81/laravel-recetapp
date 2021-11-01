<?php

namespace App\Models;

use App\Models\PasoReceta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    public function paso(){
        return $this->belongsTo(PasoReceta::class, 'paso_id', 'id');
    }
}
