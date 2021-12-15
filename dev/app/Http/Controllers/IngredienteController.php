<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Ingrediente;
use App\Helpers\Tools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IngredienteController extends Controller
{   
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


    protected function index(Request $request){    
        $ingredientesPublicos = Ingrediente::where('user_id',NULL)->get();
        $ingredientesPropios = $this->user()->ingredientes()->get();

        // if(!empty($request->input('filtro')) && !empty($request->input('valor_filtro'))){
        //     switch($request->input('filtro')){
        //         case "alf":
        //             $filtradoPublico = $ingredientesPublicos->filter(
        //                 function($value,$key) use($request)  {
        //                     return strtolower($value->nombre[0]) == strtolower($request->input('valor_filtro'));
        //                 });
        //             $filtradoPropio = $ingredientesPropios->filter(
        //                 function($value,$key) use($request){
        //                     return strtolower($value->nombre[0]) == strtolower($request->input('valor_filtro'));
        //                 });
        //             break;    
        //         case "categoria":
        //             if(!empty($request->input('valor_filtro'))){
        //                 $filtradoPublico = $ingredientesPublicos->filter(
        //                     function($value,$key) use($request)  {
        //                         return $value->cat_id == $request->input('valor_filtro');
        //                     });
        //                 $filtradoPropio = $ingredientesPropios->filter(
        //                     function($value,$key) use($request){
        //                         return $value->cat_id == $request->input('valor_filtro');
        //                     });
        //             }
        //             else{
        //                 $filtradoPublico = $ingredientesPublicos;
        //                 $filtradoPropio = $ingredientesPropios;
        //             }
        //             break;    
        //     }
        // }
        // else{
        //      $filtradoPublico = $ingredientesPublicos;
        //      $filtradoPropio = $ingredientesPropios;
        // }

        // $ingredientes = $filtradoPublico->merge($filtradoPropio)->sortBy('nombre');

        $ingredientes = $ingredientesPublicos->concat($ingredientesPropios)->sortBy('nombre');

        return $ingredientes;
    }


    protected function show(Ingrediente $ingrediente){
        if($ingrediente->user_id != NULL){
            if($ingrediente->user_id != $this->user()->id){
                throw new Exception("No tiene permiso para realizar esta acción", 401);
            }
        }

        return $ingrediente;
    }


    protected function create(Request $request){
        $request->session()->reflash();

        $categorias = $this->user()->categoriasIngrediente()->orderBy('nombre')->get();

        return view('ingredientes.create', compact('categorias'));
    }


    protected function store(Request $request){        
        $data = $request->validate($this->rules);

        if(empty($data['categoria'])){
            $data['categoria'] = NULL;
        }

        if(array_key_exists('imagen',$data)){
            if($data['imagen']){
                $data['imagen'] = request('imagen')->store('ingredientes','public');
            }
        }
        else{
            $data['imagen'] = "";
        }

        $ingrediente = Ingrediente::create([
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
            "user_id" => $this->user()->id,
        ]);

        return $ingrediente;
    }


    protected function edit(Request $request, Ingrediente $ingrediente){
        if($ingrediente->user_id == NULL && !$this->user()->can('public_edit')){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }
        
        if($ingrediente->user_id != NULL && $ingrediente->user_id != $this->user()->id){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }
        
        
        $categorias = $this->user()->categoriasIngrediente()->orderBy('nombre')->get();

        return view('ingredientes.edit', compact(['categorias','ingrediente']));        
    }


    protected function update(Request $request, Ingrediente $ingrediente){
        if($ingrediente->user_id == NULL && !$this->user()->can('public_edit')){
            throw new Exception("No tiene permiso para realizar esta acción", 401);            
        }

        if($ingrediente->user_id != NULL && $ingrediente->user_id != $this->user()->id){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }

        $data = $this->validate($request, $this->rules);

        if(empty($data['categoria'])){
            $data['categoria'] = NULL;
        }

        if(array_key_exists('imagen',$data)){
            if($data['imagen']){
                $data['imagen'] = request('imagen')->store('ingredientes','public');

                if(Storage::disk('public')->exists($ingrediente->imagen)){
                    Storage::disk('public')->delete($ingrediente->imagen);
                }
            }            
        }
        else{
            $data['imagen'] = $ingrediente->imagen;
        }

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
        
        return $ingrediente;
    }


    protected function destroy(Ingrediente $ingrediente){
        if($ingrediente->user_id == NULL && !$this->user()->can('public_destroy')){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }

        if($ingrediente->user_id != NULL && $this->user()->id != $ingrediente->user_id){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }
        
        if(Storage::disk('public')->exists($ingrediente->imagen)){
            Storage::disk('public')->delete($ingrediente->imagen);
        }
        
        $ingrediente->delete();

        return Tools::getResponse("info", "Acción realizada con éxito", 200);        
    }


    private function user() : User
    {
        /** @var User */
        return auth()->user();
    }
}
