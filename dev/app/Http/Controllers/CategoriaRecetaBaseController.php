<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaReceta;
use App\Models\User;

class CategoriaRecetaBaseController extends Controller
{
    protected $rules = [
        "nombre" => "required",
        "descripcion"=>"",
        "cat_parent"=>"",
    ];

    public function show(CategoriaReceta $categoria){
        /** @var User */
        $user = auth()->user();

        if($categoria->user_id != NULL && $categoria->user_id != $user->id){
            return response(["tipo"=>"error", "mensaje"=>"No tiene permiso para realizar esta acción"], 401);
        }

        return $categoria;
    }
    


    protected function index(){
        /** @var User */
        $user = auth()->user();

        $publicas = CategoriaReceta::where('user_id', NULL)->get();
        $privadas = $user->categoriasReceta()->get();

        $categorias = $publicas->concat($privadas)->sortBy('id');

        return $categorias;    
    }


    protected function create(){
        /** @var User */
        $user = auth()->user();

        $categorias = $user->categoriasReceta()->get();

        return $categorias;
    }


    /**
     * Guarda un nuevo recurso
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|CategoriaReceta
     */
    protected function store(Request $request){
        /** @var User */
        $user = auth()->user();

        $data = $request->validate($this->rules);

        $nuevaCatSuperior = CategoriaReceta::find($data['cat_parent']);
        
        if(!$nuevaCatSuperior){
            return response(["tipo"=>"error", "mensaje"=>"No existe la categoria superior"], 200);
        }
        
        $categoria = $user->categoriasReceta()->create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'catParent_id' => $data['cat_parent'],
        ]);

        return $categoria;
    }


    protected function edit(CategoriaReceta $categoria){        
        $categoriasHija = $categoria->categoriasHija(true);

        return $categoriasHija;
    }


    /**
     * Actualiza el recurso especificado 
     *
     * @param Request $request
     * @param CategoriaReceta $categoria
     * @return \Illuminate\Http\Response|CategoriaReceta
     */
    protected function update(Request $request, CategoriaReceta $categoria){        
        $data = $this->validate($request, $this->rules);

        /** @var User */
        $user = auth()->user();

        if($categoria->user_id == NULL && ($categoria->user_id != $user->id)){
            return response(["tipo"=>"error", "mensaje"=>"No tiene permiso para realizar esta acción"], 401);
        }
        
        $nuevaCatSuperior = CategoriaReceta::find($data['cat_parent']);
        
        if(!$nuevaCatSuperior){
            return response(["tipo"=>"error", "mensaje"=>"No existe la categoria superior"], 200);
        }

        // No permitir bucles de categorias
        if($categoria->categoriasHija(true)->contains($nuevaCatSuperior)){
            return response(["tipo"=>"error", "mensaje"=>"No se permite esa asignación de categoria superior"], 200);
        }

        if(empty($data['cat_parent'])){
            $data['cat_parent'] = NULL;
        }

        $categoria->update([
            "nombre"=>$data['nombre'],
            "descripcion"=>$data['descripcion'],
            "catParent_id"=>$data['cat_parent'],
        ]);

        return $categoria;
    }


    public function destroy(CategoriaReceta $categoria){
        /** @var User */
        $user = auth()->user();

        if($categoria->user_id == NULL){
            $res = response(["tipo"=>"error", "mensaje"=>"No tiene permiso para realizar esta acción"], 401);
        }
        else{
            if($categoria->user_id != $user->id){
                $res = response(["tipo"=>"error", "mensaje"=>"No tiene permiso para realizar esta acción"], 401);
            }
        }

        $categoria->delete();
        $res = response(["tipo"=>"info", "mensaje"=>"Acción realizada correctamente"]);

        return $res;
    }
}
