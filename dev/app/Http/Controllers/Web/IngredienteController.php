<?php

namespace App\Http\Controllers\Web;

use Throwable;
use App\Models\User;
use App\Helpers\Tools;
use App\Models\Ingrediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\IngredienteController as IngredienteBaseController;

class IngredienteController extends IngredienteBaseController
{
    public function index(Request $request)
    { 
        /** @var User */
        $user = auth()->user();

        $categorias = $user->categoriasIngrediente()->orderBy('nombre')->get();

        $ingredientes = parent::index($request);

        return view('ingredientes.index', compact("categorias","ingredientes"));
    }


    public function show(Ingrediente $ingrediente)
    {
        try {
            parent::show($ingrediente);
            $res = view('ingredientes.show',compact("ingrediente"));
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
            $res = redirect()->route('ingredientes.index');
        }
        finally{
            return $res;
        }
    }


    public function create(Request $request)
    {
        return parent::create($request);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if($validator->fails()){
            $request->session()->reflash();

            return redirect( route('ingredientes.create') )
                        ->withErrors($validator)
                        ->withInput();
        }

        parent::store($request);

        Tools::notificaOk();

        if($request->session()->has('url_retorno')){
            return redirect($request->session()->get('url_retorno'));
        }
        else{
            return redirect()->route('ingredientes.index');
        }
    }


    public function edit(Request $request, Ingrediente $ingrediente)
    {
        try {
            $res = parent::edit($request, $ingrediente);
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
            $res = redirect()->route('ingredientes.index');
        }
        finally{
            return $res;
        }
    }


    public function update(Request $request, Ingrediente $ingrediente)
    {
        try {
            parent::update($request, $ingrediente);
            Tools::notificaOk();
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
        }
        finally{
            return redirect()->route('ingredientes.index');
        }
    }


    public function destroy(Ingrediente $ingrediente)
    {
        try {
            parent::destroy($ingrediente);

            Tools::notificaOk();            
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage(), $th->getCode());
        }
        finally{
            return redirect()->route('ingredientes.index');
        }
    }


    public function publish(Ingrediente $ingrediente)
    {
        try 
        {
            $res = parent::publish($ingrediente);
            
            Tools::notificaUIFlash($res->original["tipo"], $res->original["mensaje"]);
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
        }
        finally
        {
            return redirect()->route('ingredientes.index');
        }
    }
}
