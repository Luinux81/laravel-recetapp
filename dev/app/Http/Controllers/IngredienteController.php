<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\Ingrediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function show(Ingrediente $ingrediente){

    }
    




    public function index(Request $request){          
        $ingredientesPublicos = Ingrediente::where('user_id',NULL)->get();
        $ingredientesPropios = Auth::user()->ingredientes()->get();

        $categorias = Auth::user()->categoriasIngrediente()->orderBy('nombre')->get();

        if(!empty($request->input('filtro')) && !empty($request->input('valor_filtro'))){
            switch($request->input('filtro')){
                case "alf":
                    $filtradoPublico = $ingredientesPublicos->filter(
                        function($value,$key) use($request)  {
                            return strtolower($value->nombre[0]) == strtolower($request->input('valor_filtro'));
                        });
                    $filtradoPropio = $ingredientesPropios->filter(
                        function($value,$key) use($request){
                            return strtolower($value->nombre[0]) == strtolower($request->input('valor_filtro'));
                        });
                    break;    
                case "categoria":
                    if(!empty($request->input('valor_filtro'))){
                        $filtradoPublico = $ingredientesPublicos->filter(
                            function($value,$key) use($request)  {
                                return $value->cat_id == $request->input('valor_filtro');
                            });
                        $filtradoPropio = $ingredientesPropios->filter(
                            function($value,$key) use($request){
                                return $value->cat_id == $request->input('valor_filtro');
                            });
                    }
                    else{
                        $filtradoPublico = $ingredientesPublicos;
                        $filtradoPropio = $ingredientesPropios;
                    }
                    break;    
            }
        }
        else{
            $filtradoPublico = $ingredientesPublicos;
            $filtradoPropio = $ingredientesPropios;
        }
        
        $ingredientes = $filtradoPublico->merge($filtradoPropio)->sortBy('nombre');

        return view('ingredientes.index', compact('ingredientes','categorias'));
    }




    public function create(Request $request){
        $request->session()->reflash();

        $categorias = Auth::user()->categoriasIngrediente()->orderBy('nombre')->get();

        return view('ingredientes.create', compact('categorias'));
    }




    public function store(Request $request){        
        $validator = Validator::make($request->all(), $this->rules);

        if($validator->fails()){
            $request->session()->reflash();

            return redirect( route('ingredientes.create') )
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $validator->validated();

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
            "user_id" => Auth::user()->id,
        ]);

        if($request->session()->has('url_retorno')){
            return redirect($request->session()->get('url_retorno'));
        }
        else{
            return redirect()->route('ingredientes.index');
        }
    }


    public function edit(Request $request, Ingrediente $ingrediente){
        if($ingrediente->user_id == NULL){
            if(!Auth::user()->can('public_edit')){
                $obj = new stdClass();
                $obj->tipo = "error";
                $obj->mensaje = "No tiene permiso para editar este ingrediente";
            }
        }
        else{
            if($ingrediente->user_id != Auth::user()->id){
                $obj = new stdClass();
                $obj->tipo = "error";
                $obj->mensaje = "No tiene permiso para editar este ingrediente";
            }
        }

        if (!empty($obj)){
            $request->session()->flash('notificacion',$obj);
            return redirect()->route('ingredientes.index');
        }
        

        $categorias = Auth::user()->categoriasIngrediente()->orderBy('nombre')->get();

        return view('ingredientes.edit', compact(['categorias','ingrediente']));        
    }


    public function update(Request $request, Ingrediente $ingrediente){
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
        
        return redirect()->route('ingredientes.index');
    }

    public function destroy(Request $request, Ingrediente $ingrediente){
        if($ingrediente->user_id == NULL){
            if(!Auth::user()->can('public_destroy')){
                $error = new stdClass();
                $error->tipo = "error";
                $error->mensaje = "No tiene permiso para borrar este ingrediente";
            }
        }
        else{
            if(Auth::user()->id != $ingrediente->user_id){
                $error = new stdClass();
                $error->tipo = "error";
                $error->mensaje = "No tiene permiso para borrar este ingrediente";
            }
        }
        
        if(!empty($error)){
            $request->session()->flash('notificacion',$error);
            return redirect()->route('ingredientes.index');    
        }
        

        if(Storage::disk('public')->exists($ingrediente->imagen)){
            Storage::disk('public')->delete($ingrediente->imagen);
        }
        
        $ingrediente->delete();

        return redirect()->route('ingredientes.index');
    }
}
