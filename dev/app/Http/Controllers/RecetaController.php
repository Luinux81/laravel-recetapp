<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Helpers\Seeder;
use App\Models\Ingrediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RecetaController extends Controller
{

    protected $rules = [
        'nombre' => 'required',
        'descripcion' => '',
        'calorias' => 'numeric|nullable',
        'raciones' => 'numeric|nullable',
        'tiempo' => '',
        'categoria' => '',
        'imagen' => 'image|nullable',
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

        if(array_key_exists('imagen',$data)){
            $data['imagen'] = request('imagen')->store('recetas','public');
        }
        else{
            $data['imagen'] = "";
        }

        $receta = Receta::create([
            "nombre" => $data['nombre'],
            "descripcion" => $data['descripcion'],
            "calorias" => $data['calorias'],
            "raciones" => $data['raciones'],
            "tiempo" => $data['tiempo'],
            "imagen" => $data['imagen'],
            "cat_id" => $data['categoria'],
            "user_id" => Auth::user()->id,
        ]);

        return redirect()->route('recetas.edit',['receta'=>$receta->id]);
    }

    public function edit(Receta $receta){
        $categorias = Auth::user()->categoriasReceta()->get();

        return view('recetas.edit',compact(['receta','categorias']));
    }

    public function update(Request $request, Receta $receta){
        $data = $this->validate($request, $this->rules);

        if(empty($data['categoria'])){
            $data['categoria'] = NULL;
        }

        if(array_key_exists('imagen',$data)){
            $data['imagen'] = request('imagen')->store('recetas','public');

            if(Storage::disk('public')->exists($receta->imagen)){
                Storage::disk('public')->delete($receta->imagen);
            }
        }
        else{
            $data['imagen'] = $receta->imagen;
        }

        $receta->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'calorias' => $data['calorias'],
            "raciones" => $data['raciones'],
            "tiempo" => $data['tiempo'],
            'imagen' => $data['imagen'],
            'cat_id' => $data['categoria'],
        ]);

        return redirect()->route('recetas.index');
    }

    public function destroy(Receta $receta){
        if(Storage::disk('public')->exists($receta->imagen)){
            Storage::disk('public')->delete($receta->imagen);
        }

        foreach ($receta->pasos as $paso) {
            $paso->borradoCompleto();
        }

        $receta->ingredientes()->sync([]);

        $receta->delete();

        return redirect()->route('recetas.index');
    }

    public function saveSeed(Request $request, Receta $receta){        
        Seeder::actualizaSeedFileRecetas($receta);
        Seeder::actualizaSeederFileIngredientesReceta($receta);
        Seeder::actualizaSeederFilePasosReceta($receta);
        
        session()->flash('notificacion',(object)['tipo'=>"info",'mensaje'=>"Receta añadida a seeder correctamente"]);

        return redirect()->route('recetas.index');
    }
}
