<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\CategoriaIngrediente;
use App\Helpers\Tools;
use Illuminate\Http\Request;


class CategoriaIngredienteController extends Controller
{
    protected $rules= [
        'cat_nombre'=>'required',
        'cat_descripcion'=>'',
        'cat_parent'=>'',
    ];


    protected function index()
    {
        $publicas = CategoriaIngrediente::where('user_id', NULL)->get();
        $privadas = $this->user()->categoriasIngrediente()->get();

        $categorias = $publicas->concat($privadas)->sortBy('id');

        return $categorias;
    }


    protected function show(CategoriaIngrediente $categoria)
    {
        if($categoria->user_id != NULL && $categoria->user_id != $this->user()->id ){
            throw new Exception("No tiene permiso para realizar esta acción", 401);            
        }

        return $categoria;
    }


    protected function create()
    {
        $categorias = $this->user()->categoriasIngrediente()->get();

        return view('ingredientes.categorias.create',compact('categorias'));
    }


    protected function store(Request $request)
    {
        $data = $this->validate($request,$this->rules);
        
        $nuevaCatSuperior = CategoriaIngrediente::find($data['cat_parent']);

        if(!$nuevaCatSuperior){
            throw new Exception("No existe la categoria superior", 200);
        }

        $categoria = $this->user()->categoriasIngrediente()->create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'catParent_id' => $data['cat_parent'],
        ]);

        return $categoria;
    }


    protected function edit(CategoriaIngrediente $categoria)
    {
        $categoriasHija = $categoria->categoriasHija(true);

        return view('ingredientes.categorias.edit',compact(['categoria', 'categoriasHija']));
    }


    protected function update(Request $request,CategoriaIngrediente $categoria)
    {
        if($categoria->user_id == NULL && !$this->user()->can('public_edit')){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }

        if($categoria->user_id != NULL && $categoria->user_id != $this->user()->id){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }

        $data = $this->validate($request, $this->rules);

        if(empty($data['cat_parent'])){
            $data['cat_parent'] = NULL;
        }
        else{
            $nuevaCatSuperior = CategoriaIngrediente::find($data['cat_parent']);
        
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


    protected function destroy(CategoriaIngrediente $categoria)
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