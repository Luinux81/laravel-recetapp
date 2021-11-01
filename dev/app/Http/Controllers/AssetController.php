<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Receta;
use App\Models\PasoReceta;
use Illuminate\Http\Request;
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

        if($request->callback){
            return redirect($request->callback);
        }
        else{
            return redirect()->route('recetas.paso.edit', compact(['receta', 'paso']));
        }        
    }

    public function destroy(Receta $receta, PasoReceta $paso, Asset $asset){
        if(Storage::disk('public')->exists($asset->ruta)){
            Storage::disk('public')->delete($asset->ruta);
        }

        $asset->delete();

        return redirect()->route('recetas.paso.edit', compact(['receta', 'paso']));
    }


}
