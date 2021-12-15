<?php

namespace App\Http\Controllers\Web;

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
        return parent::create($receta);
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
        $paso = parent::store($receta, $request);

        switch (get_class($paso)) {
            case "\Illuminate\Http\Response":                
                Tools::notificaUIFlash($paso->original["tipo"], $paso->original["mensaje"]);
                $res = redirect()->route('recetas.paso.create',["receta"=>$receta->id]);
                break;

            case "\App\Models\PasoReceta":
                Tools::notificaOk();
                
            default:
                $res = redirect()->route('recetas.paso.edit', compact(['receta', 'paso']));
                break;
        }

        return $res;
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
        $resultado = parent::edit($receta, $paso);

        switch (get_class($resultado)) {
            case "\Illuminate\Http\Response":
                Tools::notificaUIFlash($resultado->original["tipo"], $resultado->original["mensaje"]);
                $res = redirect()->route('recetas.edit',["receta"=>$receta->id]);
                break;
            
            default:
                // El resultado es una vista, la devolvemos directamente
                $res = $resultado;
                break;
        }

        return $res;
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
        $paso = parent::update($receta, $paso, $request);

        switch (get_class($paso)) {
            case "\Illuminate\Http\Response":
                Tools::notificaUIFlash($paso->original["tipo"], $paso->original["mensaje"]);
                $res = redirect()->route('recetas.paso.edit',compact("receta","paso"));
                break;
            
            case "\App\Models\PasoReceta":
                Tools::notificaOk();

            default:
                $res = redirect()->route('recetas.edit',compact("receta"));
                break;
        }

        return $res;
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
        $resultado = parent::destroy($receta, $paso);

        Tools::notificaUIFlash($resultado->original["tipo"], $resultado->orginal["mensaje"]);

        return redirect()->route("recetas.edit",["receta"=>$receta->id]);
    }
}
