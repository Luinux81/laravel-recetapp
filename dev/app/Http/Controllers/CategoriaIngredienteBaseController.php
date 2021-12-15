<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaIngrediente;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CategoriaIngredienteBaseController extends Controller
{
    protected $rules= [
        'cat_nombre'=>'required',
        'cat_descripcion'=>'',
        'cat_parent'=>'',
    ];



    public function show(CategoriaIngrediente $categoria){
        /** @var User */
        $user = auth()->user();

        if($categoria->user_id != NULL){
            if($categoria->user_id != $user->id){
                return response(['tipo'=>'error', 'mensaje'=>'No tiene permiso para realizar esta acción']);
            }
        }

        return $categoria;
    }



    public function index(){
        /** @var User */
        $user = auth()->user();

        $publicas = CategoriaIngrediente::where('user_id', NULL)->get();
        $privadas = $user->categoriasIngrediente()->get();

        $categorias = $publicas->concat($privadas)->sortBy('id');

        return $categorias;
    }



    public function create(){
        /** @var User */
        $user = auth()->user();

        $categorias = $user->categoriasIngrediente()->get();

        return $categorias;
    }



    public function store(Request $request){
        /** @var User */
        $user = auth()->user();

        $data = $this->validate($request,$this->rules);
        
        $nuevaCatSuperior = CategoriaIngrediente::find($data['cat_parent']);

        if(!$nuevaCatSuperior){
            return response(["tipo"=>"error", "mensaje"=>"No existe la categoria superior"], 200);
        }

        $categoria = $user->categoriasIngrediente()->create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'catParent_id' => $data['cat_parent'],
        ]);

        return $categoria;
    }



    public function edit(CategoriaIngrediente $categoria){
        $categoriasHija = $categoria->categoriasHija(true);

        return $categoriasHija;
    }



    public function update(Request $request,CategoriaIngrediente $categoria){
        $data = $this->validate($request, $this->rules);

        /** @var User */
        $user = auth()->user();

        if($categoria->user_id == NULL && ($categoria->user_id != $user->id)){
            return response(["tipo"=>"error", "mensaje"=>"No tiene permiso para realizar esta acción"], 401);
        }
        
        $nuevaCatSuperior = CategoriaIngrediente::find($data['cat_parent']);
        
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

    public function destroy(CategoriaIngrediente $categoria){        
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
