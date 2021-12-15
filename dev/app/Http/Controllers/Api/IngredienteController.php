<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Tools;
use Throwable;
use App\Models\User;
use App\Models\Ingrediente;
use Illuminate\Http\Request;
use App\Http\Controllers\IngredienteController as IngredienteBaseController;

class IngredienteController extends IngredienteBaseController{
    
    public function index(Request $request){
        return parent::index($request);
    }

    public function show(Ingrediente $ingrediente){
        try {
            $res = parent::show($ingrediente);              
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }

    public function store(Request $request){
        $ingrediente = parent::store($request);

        return response($ingrediente, 201);
    }

    public function update(Request $request, Ingrediente $ingrediente){
        try {
            $res = parent::update($request, $ingrediente);
        } 
        catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }

    public function destroy(Ingrediente $ingrediente){
        try {
            $res = parent::destroy($ingrediente);            
        } catch (Throwable $th) {
            $res = Tools::getResponse("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return $res;
        }
    }

}