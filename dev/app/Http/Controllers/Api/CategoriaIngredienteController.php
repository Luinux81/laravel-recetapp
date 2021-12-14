<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoriaIngrediente;
use App\Models\User;
use Illuminate\Http\Request;

class CategoriaIngredienteController extends Controller
{
    protected $rules = [
        'nombre'=>'required',
        'descripcion'=>'',
        'cat_parent'=>'',
    ];

    public function index(){
        /** @var User */
        $user = auth()->user();

        $publicas = CategoriaIngrediente::where('user_id',NULL)->get();
        $privadas = $user->categoriasIngrediente()->get();

        $categorias = $publicas->concat($privadas)->sortBy('id');

        return response(['Total'=>$categorias->count(), 'Categorias'=> $categorias]);
    }



    public function show(CategoriaIngrediente $categoria){
        /** @var User */
        $user = auth()->user();

        if($categoria->user_id != NULL && $categoria->user_id != $user->id){
            return response(["mensaje"=>"Unauthorized"],401);
        }

        return $categoria;
    }



    public function store(Request $request){
        /** @var User */
        $user = auth()->user();

        $data = $request->validate($this->rules);

        $categoria = CategoriaIngrediente::create([
            'user_id' => $user->id,
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'catParent_id' => $data['cat_parent'],
        ]);

        return response($categoria,201);
    }



    public function update(Request $request, CategoriaIngrediente $categoria){
        /** @var User */
        $user = auth()->user();

        if($categoria->user_id == NULL || $categoria->user_id != $user->id){
            return response(["mensaje"=>"Unauthorized"],401);
        }

        $data = $request->validate($this->rules);

        $categoria->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'catParent_id' => $data['cat_parent'],
        ]);

        return response(["mensaje"=>"Acción realizada con éxito", "categoria"=>$categoria]);
    }



    public function destroy(CategoriaIngrediente $categoria){
        /** @var User */
        $user = auth()->user();

        if($categoria->user_id == NULL || $categoria->user_id != $user->id){
            return response(["mensaje"=>"Unauthorized"],401);
        }

        $categoria->delete();

        return response(["mensaje"=>"La acción se ha realizado con éxito"]);
    }
}
