<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Receta;
use App\Helpers\Seeder;
use App\Helpers\Tools;
use App\Models\CategoriaReceta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    protected function index()
    {
        return $this->user()->getAllRecetas();
    }


    protected function show(Receta $receta)
    {
        Tools::checkOrFail($receta);

        return $receta;
    }


    protected function create()
    {
        $categorias = $this->user()->categoriasReceta()->get();

        return view('recetas.create',compact('categorias'));
    }


    protected function store(Request $request)
    {
        $data = $this->validate($request, $this->rules);

        if(empty($data['categoria'])){
            $data['categoria'] = NULL;
        }
        else{
            if(CategoriaReceta::where("id",$data['categoria'])->count() == 0){
                throw new Exception("La categoría de recetas no existe", 200);
            }
        }

        $receta = Receta::create([
            "nombre" => $data['nombre'],
            "descripcion" => $data['descripcion'],
            "calorias" => $data['calorias'],
            "raciones" => $data['raciones'],
            "tiempo" => $data['tiempo'],
            "cat_id" => $data['categoria'],
            "user_id" => auth()->user()->id,
        ]);

        if(array_key_exists('imagen', $data)){
            $receta->setImagen(request('imagen'));
        }

        return $receta;
    }


    protected function edit(Receta $receta)
    {
        Tools::checkOrFail($receta, "public_edit");

        $categorias = $this->user()->categoriasReceta()->get();

        if($receta->imagen){
            $receta->imagen = Tools::getImagen64($receta->imagen);
        }

        return view('recetas.edit',compact(['receta','categorias']));
    }


    public function update(Request $request, Receta $receta)
    {
        Tools::checkOrFail($receta, "public_edit");

        $data = $this->validate($request, $this->rules);
        
        if(empty($data['categoria'])){
            $data['categoria'] = NULL;
        }
        else{
            if(CategoriaReceta::where("id",$data['categoria'])->count() == 0){
                throw new Exception("La categoría de recetas no existe", 200);
            }
        }

        $receta->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'calorias' => $data['calorias'],
            "raciones" => $data['raciones'],
            "tiempo" => $data['tiempo'],
            'cat_id' => $data['categoria'],
        ]);

        if(array_key_exists('imagen', $data)){
            $receta->setImagen(request('imagen'));
        }

        return $receta;
    }


    public function destroy(Receta $receta)
    {
        Tools::checkOrFail($receta, "public_destroy");

        $receta->borradoCompleto();

        return Tools::getResponse("info", "Acción realizada correctamente", 200);
    }


    public function saveSeed(Request $request, Receta $receta)
    {        
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


    protected function publish(Receta $receta)
    {
        Tools::checkOrFail($receta, "public_edit");

        if(!$receta->esPublicable()) throw new Exception("La receta no es publicable", 401);

        DB::table('publish_queue')->insert([
            'tipo' => 'R',
            'model_id' => $receta->id,
            'created_at' => now(),
        ]);

        return Tools::getResponse("info", "Receta " . $receta->nombre . " añadido a cola de publicación", 200);        
    }
}
