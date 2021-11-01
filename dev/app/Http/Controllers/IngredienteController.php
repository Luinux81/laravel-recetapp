<?php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function index(){
        $ingredientes = Auth::user()->ingredientes()->get();
        
        return view('ingredientes.index', compact('ingredientes'));
    }


    public function create(){
        $categorias = Auth::user()->categoriasIngrediente()->get();

        return view('ingredientes.create', compact('categorias'));
    }


    public function store(Request $request){
        $data = $this->validate($request, $this->rules);

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

        return redirect()->route('ingredientes.index');
    }


    public function edit(Request $request, Ingrediente $ingrediente){
        $categorias = Auth::user()->categoriasIngrediente()->get();

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

    public function destroy(Ingrediente $ingrediente){
        if(Storage::disk('public')->exists($ingrediente->imagen)){
            Storage::disk('public')->delete($ingrediente->imagen);
        }
        
        $ingrediente->delete();

        return redirect()->route('ingredientes.index');
    }
}
