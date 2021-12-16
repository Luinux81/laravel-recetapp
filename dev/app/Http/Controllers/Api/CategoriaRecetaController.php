<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Helpers\Tools;
use App\Models\CategoriaReceta;
use Illuminate\Http\Request;
use App\Http\Controllers\CategoriaRecetaController as CategoriaRecetaBaseController;

class CategoriaRecetaController extends CategoriaRecetaBaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return parent::index();
    }


    /**
     * Display the specified resource.
     *
     * @param  CategoriaReceta  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(CategoriaReceta $categoria)
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  CategoriaReceta  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoriaReceta  $categoria)
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


    /**
     * Remove the specified resource from storage.
     *
     * @param  CategoriaReceta  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoriaReceta $categoria)
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
