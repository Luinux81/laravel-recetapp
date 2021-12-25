<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Helpers\Tools;
use App\Models\Receta;
use App\Models\Ingrediente;
use Illuminate\Http\Request;
use App\Http\Controllers\IngredienteRecetaController as IngredienteRecetaBaseController;

class IngredienteRecetaController extends IngredienteRecetaBaseController
{

    public function index(Receta $receta)
    {
        try {
            $res = parent::index($receta);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }


    public function show(Receta $receta, Ingrediente $ingrediente)
    {
        try {
            $res = parent::show($receta,$ingrediente);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }


    public function store(Request $request, Receta $receta){
        try {
            $res = parent::store($request, $receta);

            $res = json_decode(json_encode($res), true);  // Convertimos objeto stdclass en array

            $res = response($res, 201);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }


    public function update(Request $request, Receta $receta, Ingrediente $ingrediente)
    {
        try {
            $res = parent::update($request,$receta,$ingrediente);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }

        return $res;
    }


    public function destroy(Receta $receta, Ingrediente $ingrediente)
    {
        try {
            $res = parent::destroy($receta, $ingrediente);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }
}
