<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Receta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class RecetaController extends Controller
{
    protected $rules = [
        'nombre' => 'required',
        'descripcion' => '',
        'calorias' => 'numeric|nullable',
        'raciones' => 'numeric|nullable',
        'tiempo' => '',
        'categoria' => '',
        'imagen' => 'image|nullable',
    ];



    public function index(){
        /** @var User */
        $user = auth()->user();

        $publicas = Receta::where('user_id', NULL)->get();
        $privadas = $user->recetas()->get();

        $recetas = $publicas->concat($privadas)->sortBy('nombre');

        return $recetas;
    }




    public function show(Receta $receta){
        /** @var User */
        $user = auth()->user();
        
        if($receta->id != NULL && $receta->user_id != $user->id){
            return response(['mensaje'=>'Unauthorized'],401);
        }

        return $receta;
    }




    public function store(Request $request){
        /** @var User */
        $user = auth()->user();

        $data = $this->validate($request, $this->rules);

        $receta = Receta::where('nombre',$data['nombre']);

        if($receta->count()>0){
            if($receta->first()->user_id == $user->id){
                return response(["mensaje"=>"Ya existe una receta con ese nombre"]);
            }
        }

        if(empty($data['categoria'])){
            $data['categoria'] = NULL;
        }

        if(empty($data['calorias'])){
            $data['calorias'] = 0;
        }

        if(empty($data['raciones'])){
            $data['raciones'] = 1;
        }

        if(array_key_exists('imagen',$data)){
            $data['imagen'] = request('imagen')->store('recetas','public');
        }
        else{
            $data['imagen'] = "";
        }

        $receta = Receta::create([
            "nombre" => $data['nombre'],
            "descripcion" => $data['descripcion'],
            "calorias" => $data['calorias'],
            "raciones" => $data['raciones'],
            "tiempo" => $data['tiempo'],
            "imagen" => $data['imagen'],
            "cat_id" => $data['categoria'],
            "user_id" => $user->id,
        ]);

        return response($receta,201);
    }




    public function update(Request $request, Receta $receta){
        /** @var User */
        $user = auth()->user();

        if($receta->user_id != $user->id){
            return response(['mensaje'=>'Unauthorized'],401);
        }

        $data = $this->validate($request, $this->rules);

        if(empty($data['categoria'])){
            $data['categoria'] = NULL;
        }

        if(empty($data['calorias'])){
            $data['calorias'] = 0;
        }

        if(empty($data['raciones'])){
            $data['raciones'] = 1;
        }

        if(array_key_exists('imagen',$data)){
            $data['imagen'] = request('imagen')->store('recetas','public');

            if(Storage::disk('public')->exists($receta->imagen)){
                Storage::disk('public')->delete($receta->imagen);
            }
        }
        else{
            $data['imagen'] = $receta->imagen;
        }

        $receta->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'calorias' => $data['calorias'],
            "raciones" => $data['raciones'],
            "tiempo" => $data['tiempo'],
            'imagen' => $data['imagen'],
            'cat_id' => $data['categoria'],
        ]);

        return $receta;
    }




    public function destroy(Receta $receta){
        /** @var User */
        $user = auth()->user();

        if($receta->user_id != $user->id){
            return response(["mensaje"=>"Unauthorized"],401);
        }

        if(Storage::disk('public')->exists($receta->imagen)){
            Storage::disk('public')->delete($receta->imagen);
        }

        foreach ($receta->pasos as $paso) {
            $paso->borradoCompleto();
        }

        $receta->ingredientes()->sync([]);

        $receta->delete();

        return response(['mensaje'=>"La acción se ha realizado con éxito"]);
    }
}
