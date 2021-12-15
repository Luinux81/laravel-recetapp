<?php

namespace App\Http\Controllers\Web;

use Throwable;
use App\Helpers\Tools;
use App\Models\Asset;
use App\Models\Receta;
use App\Models\PasoReceta;
use Illuminate\Http\Request;
use App\Http\Controllers\AssetController as AssetBaseController;

class AssetController extends AssetBaseController
{
    public function store(Receta $receta, PasoReceta $paso, Request $request){
        try {
            $asset = parent::store($receta, $paso, $request);
            Tools::notificaOk();
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
        }
        finally{
            if($request->callback){
                return redirect($request->callback);
            }
            else{
                return redirect()->route('recetas.paso.edit', compact(['receta', 'paso']));
            } 
        }
    }

    public function destroy(Receta $receta, PasoReceta $paso, Asset $asset){
        try{
            parent::destroy($receta, $paso, $asset);
            Tools::notificaOk();
        }
        catch (Throwable $th){
            Tools::notificaUIFlash("error", $th->getMessage());
        }
        finally{            
            return redirect()->route('recetas.paso.edit', compact(['receta', 'paso']));
        }
    }

}
