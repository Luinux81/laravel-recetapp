<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Helpers\Tools;
use App\Models\Asset;
use App\Models\Receta;
use App\Models\PasoReceta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AssetController as AssetBaseController;

class AssetController extends AssetBaseController
{
    public function store(Receta $receta, PasoReceta $paso, Request $request){
        try {
            $res = parent::store($receta, $paso, $request);            
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return response($res,201);
        }
    }

    public function destroy(Receta $receta, PasoReceta $paso, Asset $asset){
        try{
            $respuesta = parent::destroy($receta, $paso, $asset);
        }
        catch (Throwable $th){
            $respuesta = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $respuesta;
        }
    }
}
