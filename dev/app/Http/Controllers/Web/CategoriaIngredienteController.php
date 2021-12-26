<?php

namespace App\Http\Controllers\Web;

use Throwable;
use App\Helpers\Tools;
use Illuminate\Http\Request;
use App\Models\CategoriaIngrediente;
use App\Http\Controllers\CategoriaIngredienteController as CategoriaIngredienteBaseController;

class CategoriaIngredienteController extends CategoriaIngredienteBaseController
{
    public function index()
    {
        $categorias = parent::index();

        return view('ingredientes.categorias.index',compact('categorias'));
    }


    public function show(CategoriaIngrediente $categoria)
    {
        try {
            $categoria = parent::show($categoria);

            $res = view('ingredientes.categorias.show', compact("categoria"));
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
            $res = redirect()->route('ingredientes.categoria.index');
        }
        finally{
            return $res;
        }
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
            $res = redirect()->route('ingredientes.categoria.index');
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());       

            $res = redirect()
                    ->route('ingredientes.categoria.create')
                    ->withErrors($th->validator)
                    ->withInput()
                    ;
        }
        finally{
            return $res;
        }
    }


    public function edit(CategoriaIngrediente $categoria)
    {
        return parent::edit($categoria);
    }


    public function update(Request $request, CategoriaIngrediente $categoria)
    {
        try {
            $categoria = parent::update($request, $categoria);

            Tools::notificaOk();
            $res = redirect()->route('ingredientes.categoria.index');
        }
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());

            if($th->getCode() == 400){
                $res = redirect()->route('ingredientes.categoria.edit', compact("categoria"));
            }
            else{
                $res = redirect()->route('ingredientes.categoria.index');
            }
        }
        finally{
            return $res;
        }
    }


    public function destroy(CategoriaIngrediente $categoria)
    {
        try {
            parent::destroy($categoria);

            Tools::notificaOk();
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
        }
        finally{
            return redirect()->route('ingredientes.categoria.index');
        }
    }
}
