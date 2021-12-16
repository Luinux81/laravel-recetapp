<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Receta;
use App\Helpers\Tools;
use Illuminate\Http\Request;
use App\Http\Controllers\RecetaController as RecetaBaseController;

class RecetaController extends RecetaBaseController
{

    public function index(){
        return parent::index();
    }


    public function show(Receta $receta){
        try {
            $res = parent::show($receta);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }


    public function store(Request $request){
        try {
            $res = parent::store($request);            
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }


    public function update(Request $request, Receta $receta){
        try {
            $res = parent::update($request, $receta);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }


    public function destroy(Receta $receta){
        try {
            $res = parent::destroy($receta);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }

}
