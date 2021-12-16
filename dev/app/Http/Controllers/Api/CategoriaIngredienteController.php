<?php

namespace App\Http\Controllers\Api;

use Throwable;
use Illuminate\Http\Request;
use App\Helpers\Tools;
use App\Models\CategoriaIngrediente;
use App\Http\Controllers\CategoriaIngredienteController as CategoriaIngredienteBaseController;

class CategoriaIngredienteController extends CategoriaIngredienteBaseController
{
    public function index()
    {
        return parent::index();
    }


    public function show(CategoriaIngrediente $categoria){
        try {
            $res = parent::show($categoria);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }


    //TODO: Cambiar en postman el nombre del parametro categoria o cambiar el nombre del parametro en la vista y en $rules
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


    public function update(Request $request, CategoriaIngrediente $categoria){
        try {
            $res = parent::update($request, $categoria);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }


    public function destroy(CategoriaIngrediente $categoria){
        try {
            $res = parent::destroy($categoria);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }
}
