<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecetaController extends Controller
{

    protected $rules = [
        'nombre' => 'required',
        'descripcion' => '',
        'calorias' => 'numeric|nullable',
        'categoria' => '',
    ];

    public function index(){
        $recetas = Auth::user()->recetas()->get();

        return view('recetas.index',compact('recetas'));
    }

    public function create(){
        $categorias = Auth::user()->categoriasReceta()->get();

        return view('recetas.create',compact('categorias'));
    }

    public function store(Request $request){
        $data = $this->validate($request, $this->rules);

        if(empty($data['categoria'])){
            $data['categoria'] = NULL;
        }

        Receta::create([
            "nombre" => $data['nombre'],
            "descripcion" => $data['descripcion'],
            "calorias" => $data['calorias'],
            "cat_id" => $data['categoria'],
            "user_id" => Auth::user()->id,
        ]);

        return redirect()->route('recetas.index');
    }

    public function edit(Receta $receta){
        $categorias = Auth::user()->categoriasReceta()->get();

        return view('recetas.edit',compact(['receta','categorias']));
    }

    public function update(Request $request, Receta $receta){
        $data = $this->validate($request, $this->rules);

        $receta->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'calorias' => $data['calorias'],
            'cat_id' => $data['categoria'],
        ]);

        return redirect()->route('recetas.index');
    }

    public function destroy(Receta $receta){
        $receta->delete();

        return redirect()->route('recetas.index');
    }
}
