<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\PasoReceta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $aux = $receta->pasos()->find($paso->id);

        foreach ($paso->assets as $asset){            
            $asset->borradoCompleto();
        }
        
        $aux->delete();

        return redirect()->route('recetas.edit', compact('receta'));
    }
}
