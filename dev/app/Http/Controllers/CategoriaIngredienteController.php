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
        'nombre'=>'required',
        'descripcion'=>'',
        'categoria'=>'',
    ];


    protected function index()
    {
        $categorias = $this->user()->getAllCategoriasIngrediente();

        $resultado = CategoriaIngrediente::arbol($categorias);

        return $resultado;
    }


    protected function show(CategoriaIngrediente $categoria)
    {
        Tools::checkOrFail($categoria, "show");

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
        
        if($data['categoria'] != ""){
            $nuevaCatSuperior = CategoriaIngrediente::find($data['categoria']);

            if(!$nuevaCatSuperior){
                throw new Exception("No existe la categoria superior", 200);
            }
        }
        

        $categoria = $this->user()->categoriasIngrediente()->create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'catParent_id' => $data['categoria'],
        ]);

        return $categoria;
    }


    protected function edit(CategoriaIngrediente $categoria)
    {
        Tools::checkOrFail($categoria,"update");

        $categoriasHija = $categoria->hijos(true);

        return view('ingredientes.categorias.edit',compact(['categoria', 'categoriasHija']));
    }


    protected function update(Request $request,CategoriaIngrediente $categoria)
    {
        Tools::checkOrFail($categoria, "update");

        $data = $this->validate($request, $this->rules);

        if(empty($data['categoria'])){
            $data['categoria'] = NULL;
        }
        else{
            $nuevaCatSuperior = CategoriaIngrediente::find($data['categoria']);
        
            if(!$nuevaCatSuperior){
                throw new Exception("No existe la categor??a superior", 400);
            }

            // No permitir bucles de categorias
            if($categoria->hijos(true)->contains($nuevaCatSuperior)){
                throw new Exception("No se permite esa asignaci??n de categor??a superior", 400);
            }
        }

        $categoria->update([
            "nombre"=>$data['nombre'],
            "descripcion"=>$data['descripcion'],
            "catParent_id"=>$data['categoria'],
        ]);

        return $categoria;
    }


    protected function destroy(CategoriaIngrediente $categoria)
    {        
        Tools::checkOrFail($categoria, "delete");

        $categoria->borradoCompleto();
        
        return Tools::getResponse("info", "Acci??n realizada con ??xito", 200);
    }

    private function user() : User
    {
        return auth()->user();
    }
}
