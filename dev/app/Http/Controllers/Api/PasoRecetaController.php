<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Tools;
use App\Models\Receta;
use App\Models\PasoReceta;
use Illuminate\Http\Request;
use App\Http\Controllers\PasoRecetaController as PasoRecetaBaseController;


class PasoRecetaController extends PasoRecetaBaseController
{

    public function index(Receta $receta){
        return parent::index($receta);
    }

    public function show(Receta $receta, PasoReceta $paso){
        return parent::show($receta, $paso);
    }

    public function store(Receta $receta, Request $request){
        $paso = parent::store($receta, $request);

        switch (get_class($paso)) {
            case "\App\Models\PasoReceta":
                $res = response($paso,201);
                break;
            
            default:
                $res = Tools::getResponse($paso->original["tipo"], $paso->original["mensaje"], 200);
                break;
        }

        return $res;
    }

    public function update(Receta $receta, PasoReceta $paso, Request $request){
        return parent::update($receta, $paso, $request);
    }

    public function destroy(Receta $receta, PasoReceta $paso){
        return parent::destroy($receta, $paso);
    }
}
