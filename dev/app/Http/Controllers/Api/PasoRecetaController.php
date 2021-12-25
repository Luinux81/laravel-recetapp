<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Helpers\Tools;
use App\Models\Receta;
use App\Models\PasoReceta;
use Illuminate\Http\Request;
use App\Http\Controllers\PasoRecetaController as PasoRecetaBaseController;


class PasoRecetaController extends PasoRecetaBaseController
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


    public function show(Receta $receta, PasoReceta $paso)
    {
        try {
            $res = parent::show($receta, $paso);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }


    public function store(Receta $receta, Request $request)
    {
        try {
            $res = parent::store($receta, $request);
            $res = response($res, 201);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }


    public function update(Receta $receta, PasoReceta $paso, Request $request)
    {
        try {
            $res = parent::update($receta, $paso, $request);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }


    public function destroy(Receta $receta, PasoReceta $paso)
    {
        try {
            $res = parent::destroy($receta, $paso);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }
}
