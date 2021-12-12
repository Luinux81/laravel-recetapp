<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use App\Models\Ingrediente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IngredienteController extends Controller{
    
    protected $rules = [
        "nombre" => 'required',
        "descripcion" => '',
        "marca" => '',
        "barcode" => '',
        "imagen" => 'image|nullable',        
        "url" => '',   
        "calorias" => '',     
        "fat_total" => '',        
        "fat_saturadas" => '',        
        "fat_poliinsaturadas" => '',        
        "fat_monoinsaturadas" => '',        
        "fat_trans" => '',        
        "colesterol" => '',        
        "sodio" => '',        
        "potasio" => '',        
        "fibra" => '',        
        "carb_total" => '',        
        "carb_azucar" => '',        
        "proteina" => '',
        "categoria" => '',
    ];

    public function index(){
        /** @var User */
        $user = auth()->user();
        
        $ingredientesPublicos = Ingrediente::where('user_id', NULL)->get();
        $ingredientes = $user->ingredientes()->get()->merge($ingredientesPublicos)->sortBy('nombre');

        return $ingredientes;
    }

    public function show(Ingrediente $ingrediente){
        /** @var User */
        $user = auth()->user();

        if($ingrediente->user_id != NULL && $ingrediente->user_id != $user->id){
            return response([
                'mensaje'=>'No tiene permiso para acceder al ingrediente'
            ],403);
        }
        else{
            return $ingrediente;
        }
    }

    public function store(Request $request){
        /** @var User */
        $user = auth()->user();

        $data = $request->validate($this->rules);

        $ingrediente = Ingrediente::create([
            "user_id" => $user->id,
            "nombre" => $data['nombre'],
            "descripcion" => $data['descripcion'],
            "marca" => $data['marca'],
            "barcode" => $data['barcode'],
            "imagen" => $data['imagen'],
            "url" => $data['url'],
            "calorias" => $data['calorias'],
            "fat_total" => $data['fat_total'],
            "fat_saturadas" => $data['fat_saturadas'],
            "fat_poliinsaturadas" => $data['fat_poliinsaturadas'],
            "fat_monoinsaturadas" => $data['fat_monoinsaturadas'],
            "fat_trans" => $data['fat_trans'],
            "colesterol" => $data['colesterol'],
            "sodio" => $data['sodio'],
            "potasio" => $data['potasio'],
            "fibra" => $data['fibra'],
            "carb_total" => $data['carb_total'],
            "carb_azucar" => $data['carb_azucar'],
            "proteina" => $data['proteina'],
            "cat_id" => $data['categoria'],
        ]);

        return response(['ingrediente'=>$ingrediente], 201);
    }

    public function update(Request $request, Ingrediente $ingrediente){
        $data = $request->validate($this->rules);

        $ingrediente->update([
            "nombre" => $data['nombre'],
            "descripcion" => $data['descripcion'],
            "marca" => $data['marca'],
            "barcode" => $data['barcode'],
            "imagen" => $data['imagen'],        
            "url" => $data['url'],   
            "calorias" => $data['calorias'],     
            "fat_total" => $data['fat_total'],        
            "fat_saturadas" => $data['fat_saturadas'],        
            "fat_poliinsaturadas" => $data['fat_poliinsaturadas'],        
            "fat_monoinsaturadas" => $data['fat_monoinsaturadas'],        
            "fat_trans" => $data['fat_trans'],        
            "colesterol" => $data['colesterol'],        
            "sodio" => $data['sodio'],        
            "potasio" => $data['potasio'],        
            "fibra" => $data['fibra'],        
            "carb_total" => $data['carb_total'],        
            "carb_azucar" => $data['carb_azucar'],        
            "proteina" => $data['proteina'],
            "cat_id" => $data['categoria'],
        ]);    

        return response(['ingrediente'=>$ingrediente], 200);
    }

    public function destroy(Request $request, Ingrediente $ingrediente){
        /** @var User */
        $user = auth()->user();

        $ing = $user->ingredientes()->find($ingrediente->id);
        if(!$ing){
            return response([
                'mensaje' => "No tiene permiso para realizar esta acción."
            ],401);
        }
        else{
            $ing->delete();
            return response([
                'mensaje' => "Acción realizada con éxito"
            ], 200);
        }
    }
}