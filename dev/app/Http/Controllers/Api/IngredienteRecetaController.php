<?php

namespace App\Http\Controllers\Api;

use App\Models\Receta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ingrediente;
use App\Models\User;

class IngredienteRecetaController extends Controller
{
    protected $rules = [
        'ingrediente' => 'required',
        'cantidad' => 'numeric|required',
        'unidad_medida' => 'required',
    ];

    public function index(Receta $receta){
        /** @var User */
        $user = auth()->user();

        return $receta->ingredientes()->get();
    }




    public function show(Receta $receta, Ingrediente $ingrediente){
        $res = $receta->ingredientes()->find($ingrediente);

        if($res){
            return $res;
        }
        else{
            return response(['mensaje' => "El ingrediente " . $ingrediente->nombre . " no está en la receta"]);
        }
    }




    public function store(Request $request, Receta $receta){
        $data = $request->validate($this->rules);

        $ingrediente = Ingrediente::find($data['ingrediente']);

        if(!$ingrediente){
            return response(['mensaje'=>'El ingrediente no existe'],200);
        }

        if($receta->ingredientes()->find($data['ingrediente'])){
            return response(['mensaje'=>'El ingrediente ya existe en la receta'],200);
        }

        $receta->ingredientes()->attach($ingrediente, ['cantidad' => $data['cantidad'], 'unidad_medida' => $data['unidad_medida']]);

        return response(['ingrediente'=> $receta->ingredientes()->find($ingrediente)->first(), 'ingredientesEnReceta'=>$receta->ingredientes()->get()], 201);
    }




    public function update(Request $request, Receta $receta, Ingrediente $ingrediente){
        $data = $request->validate($this->rules);

        $receta->ingredientes()->detach($ingrediente);
        $receta->ingredientes()->attach($ingrediente,['cantidad'=>$data['cantidad'], 'unidad_medida'=>$data['unidad_medida']]);

        return response(["mensaje"=>"Acción realizada con éxito", "ingredientes"=>$receta->ingredientes()->get()]);
    }





    public function destroy(Receta $receta, Ingrediente $ingrediente){
        if($receta->ingredientes()->find($ingrediente) == NULL){
            return response(['mensaje'=>'El ingrediente no está en la receta']);
        }

        $receta->ingredientes()->detach($ingrediente);

        return response(['mensaje'=>'El ingrediente se ha eliminado de la receta','ingredientes'=>$receta->ingredientes()->get()]);
    }
}
