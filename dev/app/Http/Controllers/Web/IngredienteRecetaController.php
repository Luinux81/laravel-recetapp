<?php

namespace App\Http\Controllers\Web;

use Throwable;
use App\Helpers\Tools;
use App\Models\Receta;
use App\Models\Ingrediente;
use Illuminate\Http\Request;
use App\Http\Controllers\IngredienteRecetaController as IngredienteRecetaBaseController;

class IngredienteRecetaController extends IngredienteRecetaBaseController
{

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request
     * @param \App\Models\Receta 
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Receta $receta)
    {
        try {
            $res = parent::create($request,$receta);
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
            $res = redirect()->route('recetas.edit', compact("receta"));
        }
        finally{
            return $res;
        }
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Receta $receta
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Receta $receta)
    {
        try {
            parent::store($request,$receta);

            Tools::notificaOk();           
            $res = redirect()->route('recetas.edit', compact("receta"));
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
            $res = redirect()->route('recetas.ingrediente.create', compact("receta"));
        }
        finally{
            return $res;
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Receta $receta
     * @param \App\Models\Ingrediente $ingrediente
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(Receta $receta, Ingrediente $ingrediente)
    {
        try {
            $res = parent::edit($receta, $ingrediente);
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
            $res = redirect()->route('recetas.index');
        }
        finally{
            return $res;
        }
        
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receta $receta, Ingrediente $ingrediente)
    {
        try {
            parent::update($request, $receta, $ingrediente);

            Tools::notificaOk();
            $res = redirect()->route('recetas.edit', compact("receta"));
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
            $res = redirect()->route('recetas.ingrediente.edit', compact("receta", "ingrediente"));
        }
        finally{
            return $res;
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Receta $receta
     * @param \App\Models\Ingrediente $ingrediente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta, Ingrediente $ingrediente)
    {
        try {
            parent::destroy($receta, $ingrediente);

            Tools::notificaOk();            
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
        }
        finally{
            return redirect()->route("recetas.edit", compact("receta"));
        }
    }

}
