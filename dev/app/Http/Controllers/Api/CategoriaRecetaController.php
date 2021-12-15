<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\CategoriaReceta;
use App\Http\Controllers\CategoriaRecetaBaseController;

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
        return parent::show($categoria);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return parent::store($request);
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
        return parent::update($request, $categoria);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  CategoriaReceta  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoriaReceta $categoria)
    {
        return parent::destroy($categoria);
    }
}
