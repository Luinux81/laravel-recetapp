<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\Ingrediente;
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

    protected $rulesIngredientes = [
        'ingrediente' => 'required',
        'cantidad' => 'numeric|required',
        'unidad_medida' => 'required',
    ];

    public function index(){
        $recetas = Auth::user()->recetas()->get();

        return view('recetas.index',compact('recetas'));
    }

    public function show(Receta $receta){
        return view('recetas.show',compact('receta'));
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


    public function createIngrediente(Receta $receta){
        $ingredientes = Auth::user()->ingredientes()->get();        

        return view('recetas.ingredientes.create', compact(['receta','ingredientes']));
    }

    public function storeIngrediente(Receta $receta, Request $request){
        $data = $this->validate($request, $this->rulesIngredientes);

        $ingrediente = Ingrediente::findOrFail($data['ingrediente']);

        $receta->ingredientes()->attach($ingrediente, ['cantidad' => $data['cantidad'], 'unidad_medida' => $data['unidad_medida']]);
        
        return redirect()->route('recetas.edit',['receta' => $receta->id]);
    }

    public function editIngrediente(Receta $receta, Ingrediente $ingrediente){
        return view('recetas.ingredientes.edit', compact(['receta','ingrediente']));
    }

    public function updateIngrediente(Receta $receta, Ingrediente $ingrediente, Request $request){
        $data = $this->validate($request, $this->rulesIngredientes);

        $receta->ingredientes()->detach($ingrediente);
        $receta->ingredientes()->attach($ingrediente,["cantidad"=>$data['cantidad'], "unidad_medida"=>$data['unidad_medida']]);

        return redirect()->route('recetas.edit', ['receta'=>$receta->id]);
    }

    public function destroyIngrediente(Receta $receta, Ingrediente $ingrediente){
        $receta->ingredientes()->detach($ingrediente);

        return redirect()->route('recetas.edit', ['receta'=>$receta->id]);
    }
}
