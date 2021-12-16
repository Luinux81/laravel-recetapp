<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helpers\Tools;
use Illuminate\Http\Request;
use App\Models\CategoriaReceta;

class CategoriaRecetaController extends Controller
{
    protected $rules = [
        "nombre" => "required",
        "descripcion"=>"",
        "cat_parent"=>"",
    ];

    protected function show(CategoriaReceta $categoria)
    {
        if($categoria->user_id != NULL && $categoria->user_id != $this->user()->id){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }

        return $categoria;
    }


    protected function index()
    {
        $publicas = CategoriaReceta::where('user_id', NULL)->get();
        $privadas = $this->user()->categoriasReceta()->get();

        $categorias = $publicas->concat($privadas)->sortBy('id');

        return $categorias;    
    }


    protected function create()
    {
        $categorias = $this->user()->categoriasReceta()->get();

        return view('recetas.categorias.create',compact('categorias'));
    }


    /**
     * Guarda un nuevo recurso
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|CategoriaReceta
     */
    protected function store(Request $request)
    {
        $data = $request->validate($this->rules);

        $nuevaCatSuperior = CategoriaReceta::find($data['cat_parent']);
        
        if(!$nuevaCatSuperior){
            throw new Exception("No existe la categoría superior", 400);
        }
        
        $categoria = $this->user()->categoriasReceta()->create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'catParent_id' => $data['cat_parent'],
        ]);

        return $categoria;
    }


    protected function edit(CategoriaReceta $categoria)
    {        
        $categoriasHija = $categoria->categoriasHija(true);

        return view('recetas.categorias.edit',compact(['categoria', 'categoriasHija'])); 
    }


    /**
     * Actualiza el recurso especificado 
     *
     * @param Request $request
     * @param CategoriaReceta $categoria
     * @return \Illuminate\Http\Response|CategoriaReceta
     */
    protected function update(Request $request, CategoriaReceta $categoria)
    {   
        if($categoria->user_id == NULL && !$this->user()->can('public_edit')){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }

        if($categoria->user_id != NULL && ($categoria->user_id != $this->user()->id)){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }
        
        $data = $this->validate($request, $this->rules);

        if(empty($data['cat_parent'])){
            $data['cat_parent'] = NULL;
        }
        else{
            $nuevaCatSuperior = CategoriaReceta::find($data['cat_parent']);
        
            if(!$nuevaCatSuperior){
                throw new Exception("No existe la categoría superior", 400);
            }

            // No permitir bucles de categorias
            if($categoria->categoriasHija(true)->contains($nuevaCatSuperior)){
                throw new Exception("No se permite esa asignación de categoría superior", 400);
            }
        }

        $categoria->update([
            "nombre"=>$data['nombre'],
            "descripcion"=>$data['descripcion'],
            "catParent_id"=>$data['cat_parent'],
        ]);

        return $categoria;
    }


    protected function destroy(CategoriaReceta $categoria)
    {
        if($categoria->user_id == NULL && !$this->user()->can('public_destroy')){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }

        if($categoria->user_id != NULL && $categoria->user_id != $this->user()->id){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }

        $categoria->delete();
        
        return Tools::getResponse("info", "Acción realizada con éxito", 200);
    }


    private function user() : User
    {
        return auth()->user();
    }
}
