<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Receta;
use App\Models\Ingrediente;
use Illuminate\Http\Request;
use App\Http\Controllers\IngredienteRecetaBaseController;

class IngredienteRecetaController extends IngredienteRecetaBaseController
{

    public function index(Receta $receta){
        return parent::index($receta);
    }


    public function show(Receta $receta, Ingrediente $ingrediente){
        return parent::show($receta,$ingrediente);
    }


    public function store(Request $request, Receta $receta){
        $resultado = parent::store($request, $receta);

        return $resultado;
    }


    public function update(Request $request, Receta $receta, Ingrediente $ingrediente){
        return parent::update($request,$receta,$ingrediente);
    }


    public function destroy(Receta $receta, Ingrediente $ingrediente){
        return parent::destroy($receta, $ingrediente);
    }
}
