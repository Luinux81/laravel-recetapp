<?php

namespace App\Models;

use App\Models\Asset;
use App\Models\Receta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PasoReceta extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "pasos_receta";
    protected $guarded = [];

    public function user()
    {
        return $this->receta()->user();
    }

    public function receta()
    {
        return $this->belongsTo(Receta::class);
    }

    public function esPublico()
    {
        return $this->receta()->first()->esPublico();
    }

    public function assets()
    {
        return $this->hasMany(Asset::class,'paso_id');
    }

    public function borradoCompleto()
    {
        foreach ($this->assets as $asset) {
            $asset->borradoCompleto();
        }

        $this->delete();
    }
}
