<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\PasoReceta;
use Illuminate\Http\Request;

class PasoRecetaController extends Controller
{
    protected $rules = [
        'orden' => 'required',
        'texto' => 'required',
    ];

    public function create(Receta $receta){
        return view('recetas.pasos.create', compact('receta'));
    }

    public function store(Receta $receta, Request $request){
        $data = $this->validate($request, $this->rules);

        $paso = PasoReceta::create([
            'receta_id' => $receta->id,
            'orden' => $data['orden'],
            'texto' => $data['texto'],
        ]);

        return redirect()->route('recetas.paso.edit', compact(['receta', 'paso']));
    }

    public function edit(Receta $receta, PasoReceta $paso){
        $assets = $paso->assets()->get();
        
        foreach ($assets as $asset) {
            $imagenes[$asset->id]= $asset->ruta;
        }

        return view('recetas.pasos.edit', compact(['receta','paso','assets']));
    }

    public function update(Receta $receta, PasoReceta $paso, Request $request){
        $data = $this->validate($request, $this->rules);

        $receta->pasos()->find($paso->id)->update([
            'orden' => $data['orden'],
            'texto' => $data['texto'],
        ]);

        return redirect()->route('recetas.edit', compact('receta'));
    }

    public function destroy(Receta $receta, PasoReceta $paso){
        $receta->pasos()->find($paso->id)->delete();

        return redirect()->route('recetas.edit', compact('receta'));
    }

    // public function storeImagePaso(Receta $receta, PasoReceta $paso, Request $request){
    //     $data = $this->validate($request, [
    //         'imagen' => 'image|required',
    //     ]);
        
    //     $img = request('imagen')->store('pasos','public');

    //     $imagenes = $paso->media_assets;
    //     if($imagenes){
    //         $imagenes = json_decode($imagenes);
    //     }
    //     else{
    //         $imagenes = [];
    //     }

    //     array_push($imagenes, $img);

    //     $imagenes = json_encode($imagenes);

    //     $paso->update([
    //         'media_assets' => $imagenes,
    //     ]);

    //     if($request->callback){
    //         return redirect($request->callback);
    //     }
    //     else{
    //         return redirect()->route('receta.paso.edit', compact(['receta', 'paso']));
    //     }
        
    // }
}
