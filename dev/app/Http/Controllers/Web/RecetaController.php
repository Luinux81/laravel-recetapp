<?php

namespace App\Http\Controllers\Web;

use Throwable;
use App\Helpers\Tools;
use App\Models\Receta;
use Illuminate\Http\Request;
use App\Http\Controllers\RecetaController as RecetaBaseController;

class RecetaController extends RecetaBaseController
{

    public function index()
    {
        $recetas = parent::index();

        return view('recetas.index',compact('recetas'));
    }


    public function show(Receta $receta)
    {
        try {
            parent::show($receta);
            $res = redirect()->route('recetas.show', compact("receta"));
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
            $res = redirect()->route('recetas.index');
        }
        finally{
            return $res;
        }
    }


    public function create(){
        return parent::create();
    }


    public function store(Request $request){
        try {
            $receta = parent::store($request);

            Tools::notificaOk();
            $res = redirect()->route('recetas.edit', compact("receta"));
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
            $res = redirect()->route('recetas.index');
        }
        finally
        {
            return $res;
        }
    }


    public function edit(Receta $receta)
    {
        return parent::edit($receta);
    }


    public function update(Request $request, Receta $receta)
    {
        try {
            $receta = parent::update($request, $receta);
            Tools::notificaOk();

            $res = redirect()->route('recetas.edit', compact("receta"));
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
            
            if(strpos($th->getMessage(), "categoría de recetas") !== false){
                $res = redirect()->route('recetas.edit', compact("receta"));
            }
            else{
                $res = redirect()->route('recetas.index');
            }
        }
        finally{
            return $res;
        }
    }


    public function destroy(Receta $receta)
    {
        try {
            parent::destroy($receta);
            
            Tools::notificaOk();            
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
        }
        finally{
            return redirect()->route('recetas.index');
        }
    }

}
