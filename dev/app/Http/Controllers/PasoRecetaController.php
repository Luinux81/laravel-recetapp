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

        PasoReceta::create([
            'receta_id' => $receta->id,
            'orden' => $data['orden'],
            'texto' => $data['texto'],
        ]);

        return redirect()->route('recetas.edit', compact('receta'));
    }

    public function edit(Receta $receta, PasoReceta $paso){
        return view('recetas.pasos.edit', compact(['receta','paso']));
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
}
