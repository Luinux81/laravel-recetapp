<?php

namespace App\Http\Controllers\Web;

use Throwable;
use App\Helpers\Tools;
use App\Models\Receta;
use App\Models\PasoReceta;
use Illuminate\Http\Request;
use App\Http\Controllers\PasoRecetaController as PasoRecetaBaseController;

class PasoRecetaController extends PasoRecetaBaseController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Receta $receta)
    {
        try {
            $res = parent::create($receta);
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
     * @param \App\Models\Receta $receta
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Receta $receta, Request $request)
    {
        try {
            $paso = parent::store($receta, $request);

            Tools::notificaOk();
            $res = redirect()->route('recetas.paso.edit', compact("receta", "paso"));
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
            $res = redirect()->route('receta.edit', compact("receta"));
        }
        finally{
            return $res;
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receta $receta
     * @param  \App\Models\PasoReceta $paso
     * 
     * @return 
     */
    public function edit(Receta $receta, PasoReceta $paso)
    {
        try {
            $res = parent::edit($receta, $paso);
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
            $res = redirect()->route('receta.edit', compact("receta"));
        }
        finally{
            return $res;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Receta
     * @param  \App\Models\PasoReceta
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     * 
     */
    public function update(Receta $receta, PasoReceta $paso, Request $request)
    {
        try {
            $paso = parent::update($receta, $paso, $request);

            Tools::notificaOk();
            $res = redirect()->route('recetas.edit', compact("receta"));
        } 
        catch (Throwable $th) {
            Tools::notificaUIFlash("error", $th->getMessage());
            $res = redirect()->route('receta.paso.edit', compact("receta", "paso"));
        }
        finally{
            return $res;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Receta $receta
     * @param  \App\Models\PasoReceta $paso
     * 
     * @return \Illuminate\Http\Response
     */
    protected function destroy(Receta $receta, PasoReceta $paso)
    {
        try {
            parent::destroy($receta, $paso);

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
