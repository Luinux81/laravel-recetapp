<?php

namespace App\Http\Controllers\Api;

use App\Models\Asset;
use App\Models\Receta;
use App\Models\PasoReceta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function store(Receta $receta, PasoReceta $paso, Request $request){
        $data = $this->validate($request, [
            'imagen' => 'image|required',
        ]);
        
        $img = request('imagen')->store('pasos','public');

        
        $paso->assets()->save(new Asset([
            'tipo' => 'local',
            'ruta' => $img,
            'remoto' => false,
        ]));

        return response($paso, 201);        
    }

    public function destroy(Receta $receta, PasoReceta $paso, Asset $asset){
        if(Storage::disk('public')->exists($asset->ruta)){
            Storage::disk('public')->delete($asset->ruta);
        }

        $asset->delete();

        return response(["mensaje"=>"Acción realizada con éxito"]);
    }
}
