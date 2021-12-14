<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoriaReceta;
use App\Models\User;
use Illuminate\Http\Request;

class CategoriaRecetaController extends Controller
{
    protected $rules = [
        "nombre" => "required",
        "descripcion"=>"",
        "cat_parent"=>"",
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var User */
        $user = auth()->user();

        $publicos = CategoriaReceta::where('user_id',NULL)->get();
        $privados = $user->categoriasReceta()->get();

        $categorias = $publicos->concat($privados)->sortBy('id');

        return $categorias;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** @var User */
        $user = auth()->user();

        $data = $request->validate($this->rules);

        $nuevaCatSuperior = CategoriaReceta::find($data['cat_parent']);
        
        if(!$nuevaCatSuperior){
            return response(["mensaje"=>"No existe la categoria superior"], 200);
        }
        
        $categoria = $user->categoriasReceta()->create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'catParent_id' => $data['cat_parent'],
        ]);

        return $categoria;
    }

    /**
     * Display the specified resource.
     *
     * @param  CategoriaReceta  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(CategoriaReceta $categoria)
    {
        /** @var User */
        $user = auth()->user();

        if($categoria->user_id != NULL && $categoria->user_id != $user->id){
            return response(["mensaje"=>"Unauthorized"], 401);
        }

        return $categoria;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  CategoriaReceta  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoriaReceta  $categoria)
    {
        /** @var User */
        $user = auth()->user();

        if($categoria->user_id == NULL || $categoria->user_id != $user->id){
            return response(["mensaje"=>"Unauthorized"], 401);
        }

        $data = $request->validate($this->rules);

        $nuevaCatSuperior = CategoriaReceta::find($data['cat_parent']);
        
        if(!$nuevaCatSuperior){
            return response(["mensaje"=>"No existe la categoria superior"], 200);
        }

        // No permitir bucles de categorias
        if($categoria->categoriasHija(true)->contains($nuevaCatSuperior)){
            return response(["mensaje"=>"Error no se permite esa asignación de categoria padre"], 200);
        }

        $categoria->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'catParent_id' => $data['cat_parent'],
        ]);

        return $categoria;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CategoriaReceta  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoriaReceta $categoria)
    {
        /** @var User */
        $user = auth()->user();

        if($categoria->user_id == NULL || $categoria->user_id != $user->id){
            return response(["mensaje"=>"Unauthorized"], 401);
        }

        $categoria->delete();

        return response(["mensaje"=>"Acción realizada correctamente"]);
    }
}
