<?php

namespace App\Http\Controllers\Web;

use Throwable;
use App\Helpers\Tools;
use App\Models\CategoriaReceta;
use Illuminate\Http\Request;
use App\Http\Controllers\CategoriaRecetaController as CategoriaRecetaBaseController;

class CategoriaRecetaController extends CategoriaRecetaBaseController
{
    public function index()
    {
        $categorias = parent::index();
        
        return view('recetas.categorias.index',compact('categorias'));
    }


    public function create()
    {
        return parent::create();
    }


    public function store(Request $request)
    {
        try {
            parent::store($request);
            
            Tools::notificaOk();
            $res = redirect()->route('recetas.categoria.index');
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());

            if($th->getCode() == 400){
                $res = $res = redirect()->route('recetas.categoria.create');
            }
            else{
                $res = redirect()->route('recetas.categoria.index');
            }
        }
        finally{
            return $res;
        }
    }


    public function edit(CategoriaReceta $categoria)
    {
        return parent::edit($categoria);
    }


    public function update(Request $request, CategoriaReceta $categoria)
    {
        try {
            $categoria = parent::update($request, $categoria);

            Tools::notificaOk();
            $res = redirect()->route('recetas.categoria.index');
        }
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());

            if($th->getCode() == 400){
                $res = redirect()->route('recetas.categoria.edit', compact("categoria"));
            }
            else{
                $res = redirect()->route('recetas.categoria.index');
            }
        }
        finally{
            return $res;
        }
    }


    public function destroy(CategoriaReceta $categoria)
    {
        try {
            parent::destroy($categoria);

            Tools::notificaOk();
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
        }
        finally{
            return redirect()->route('recetas.categoria.index');
        }
    }
}
