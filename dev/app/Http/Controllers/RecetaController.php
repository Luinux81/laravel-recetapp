<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Receta;
use App\Helpers\Seeder;
use App\Helpers\Tools;
use App\Models\CategoriaReceta;
use Illuminate\Http\Request;
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

    protected function index(){
        $recetas_privadas = $this->user()->recetas()->get();
        $recetas_publicas = Receta::where('user_id',NULL)->get();

        $recetas = $recetas_privadas->concat($recetas_publicas)->sortBy('nombre');

        return $recetas;
    }


    protected function show(Receta $receta){
        if($receta->user_id != NULL && $receta->user_id != $this->user()->id){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }

        return $receta;
    }


    protected function create(){
        $categorias = $this->user()->categoriasReceta()->get();

        return view('recetas.create',compact('categorias'));
    }


    protected function store(Request $request){
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
            "user_id" => $this->user()->id,
        ]);

        return $receta;
    }


    protected function edit(Receta $receta){
        $categorias = $this->user()->categoriasReceta()->get();

        return view('recetas.edit',compact(['receta','categorias']));
    }


    public function update(Request $request, Receta $receta){
        if($receta->user_id == NULL && !$this->user()->can('public_edit')){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }

        if($receta->user_id != NULL && $receta->user_id != $this->user()->id){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }

        $data = $this->validate($request, $this->rules);
        
        if(empty($data['categoria'])){
            $data['categoria'] = NULL;
        }
        else{
            if(CategoriaReceta::where("cat_id",$data['categoria'])->count() == 0){
                throw new Exception("La categoría de recetas no existe", 200);
            }
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

        $receta = $receta->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'calorias' => $data['calorias'],
            "raciones" => $data['raciones'],
            "tiempo" => $data['tiempo'],
            'imagen' => $data['imagen'],
            'cat_id' => $data['categoria'],
        ]);

        return $receta;
    }


    public function destroy(Receta $receta){
        if($receta->user_id == NULL && !$this->user()->can('public_destroy')){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }

        if($receta->user_id != NULL && $receta->user_id != $this->user()->id){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }

        if(Storage::disk('public')->exists($receta->imagen)){
            Storage::disk('public')->delete($receta->imagen);
        }

        foreach ($receta->pasos as $paso) {
            $paso->borradoCompleto();
        }

        $receta->ingredientes()->sync([]);

        $receta->delete();

        return Tools::getResponse("info", "Acción realizada correctamente", 200);
    }


    public function saveSeed(Request $request, Receta $receta){        
        Seeder::actualizaSeedFileRecetas($receta);
        Seeder::actualizaSeederFileIngredientesReceta($receta);
        Seeder::actualizaSeederFilePasosReceta($receta);
        
        session()->flash('notificacion',(object)['tipo'=>"info",'mensaje'=>"Receta añadida a seeder correctamente"]);

        return redirect()->route('recetas.index');
    }


    private function user() : User
    {
        return auth()->user();
    }

}
