<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CategoriaIngrediente;
use App\Http\Controllers\CategoriaIngredienteBaseController;

class CategoriaIngredienteController extends CategoriaIngredienteBaseController
{
    public function index(){
        return parent::index();
    }


    public function show(CategoriaIngrediente $categoria){
        return parent::show($categoria);
    }


    public function store(Request $request){
        return parent::create($request);
    }


    public function update(Request $request, CategoriaIngrediente $categoria){
        return parent::update($request, $categoria);
    }


    public function destroy(CategoriaIngrediente $categoria){
        return parent::destroy($categoria);
    }
}
