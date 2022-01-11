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
        "categoria"=>"",
    ];

    protected function show(CategoriaReceta $categoria)
    {
        Tools::checkOrFail($categoria);

        return $categoria;
    }


    protected function index()
    {
        $categorias = $this->user()->getAllCategoriasReceta();

        $resultado = CategoriaReceta::arbol($categorias);

        return $resultado;
    }


    protected function create()
    {
        $categorias = $this->user()->getAllCategoriasReceta();

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

        if($data["categoria"] != "")
        {
            $nuevaCatSuperior = CategoriaReceta::find($data['categoria']);
        
            if(!$nuevaCatSuperior){
                throw new Exception("No existe la categoría superior", 400);
            }
        }
        
        $categoria = $this->user()->categoriasReceta()->create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'catParent_id' => $data['categoria'],
        ]);

        return $categoria;
    }


    protected function edit(CategoriaReceta $categoria)
    {
        Tools::checkOrFail($categoria, "public_edit");

        $categoriasHija = $categoria->hijos(true);

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
        Tools::checkOrFail($categoria, "public_edit");
        
        $data = $this->validate($request, $this->rules);

        if(empty($data['categoria'])){
            $data['cat_parent'] = NULL;
        }
        else{
            $nuevaCatSuperior = CategoriaReceta::find($data['cat_parent']);
        
            if(!$nuevaCatSuperior){
                throw new Exception("No existe la categoría superior", 400);
            }

            // No permitir bucles de categorias
            if($categoria->hijos(true)->contains($nuevaCatSuperior)){
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
        Tools::checkOrFail($categoria, "public_destroy");

        $categoria->borradoCompleto();
        
        return Tools::getResponse("info", "Acción realizada con éxito", 200);
    }


    private function user() : User
    {
        return auth()->user();
    }
}
