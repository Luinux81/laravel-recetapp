<?php

namespace App\Http\Controllers;

use App\Helpers\Tools;
use Exception;
use App\Models\User;
use App\Models\Asset;
use App\Models\Receta;
use App\Models\PasoReceta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function store(Receta $receta, PasoReceta $paso, Request $request){

        $this->checkCondiciones($receta, $paso);

        $this->validate($request, ['imagen' => 'image|required']);
        
        if($receta->esRecetaPublica()){
            $img = request('imagen')->store('pasos','public');
        }
        else{
            $img = request('imagen')->store('users/' . auth()->user()->id . '/pasos/');
        }
        

        $asset = new Asset([
            'tipo' => 'local',
            'ruta' => $img,
            'remoto' => false,
        ]);

        $asset = $paso->assets()->save($asset);

        return $asset;       
    }



    public function destroy(Receta $receta, PasoReceta $paso, Asset $asset){

        $this->checkCondiciones($receta, $paso);

        $asset->borradoCompleto();

        return Tools::getResponse("info","Acción realizada con éxito", 200);
    }



    private function checkCondiciones(Receta $receta, PasoReceta $paso){
        /** @var User */
        $user = auth()->user();

        if($receta->user_id != $user->id){
            throw new Exception("No tiene permiso para realizar esta acción", 401);            
        }

        if($receta->id != $paso->receta_id){
            throw new Exception("El paso no pertenece a la receta especificada", 200);            
        }
    }


}
