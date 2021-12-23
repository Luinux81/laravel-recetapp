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
        $categorias = parent::index();

        return $categorias;
    }


    public function show(CategoriaIngrediente $categoria)
    {
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


    public function store(Request $request)
    {
        try {
            $res = parent::store($request);
            $res = response($res, 201);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }


    public function update(Request $request, CategoriaIngrediente $categoria)
    {
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


    public function destroy(CategoriaIngrediente $categoria)
    {
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
