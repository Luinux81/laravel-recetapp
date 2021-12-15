<?php

namespace App\Http\Controllers\Web;

use App\Helpers\Tools;
use Illuminate\Http\Request;
use App\Http\Controllers\IngredienteRecetaBaseController;
use App\Models\Ingrediente;
use App\Models\Receta;

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
        return parent::create($request,$receta);
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
        $resultado = parent::store($request,$receta);

        switch (get_class($resultado)) {
            case "\Illuminate\Http\Response":
                Tools::notificaUIFlash($resultado->original["tipo"], $resultado->original["mensaje"]);
                $res = redirect()->route('recetas.ingrediente.create',["recetas"=>$receta->id]);
                break;
            
            case "stdClass":
                $this->notificaOk();

            default:
                $res = redirect()->route('recetas.edit',["recetas"=>$receta->id]);
                break;
        }

        return $res;
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
        parent::edit($receta, $ingrediente);
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
        $resultado = parent::update($request,$receta,$ingrediente);

        switch (get_class($resultado)) {
            case "\Illuminate\Http\Response":
                Tools::notificaUIFlash($resultado->original["tipo"], $resultado->original["mensaje"]);
                $res = redirect()->route("recetas.ingrediente.edit",["receta"=>$receta->id,"ingrediente"=>$ingrediente->id]);
                break;
            
            case "stdClass":
                $this->notificaOk();

            default:
                $res = redirect()->route('recetas.edit', ['receta'=>$receta->id]);
                break;
        }        

        return $res;
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
        $resultado = parent::destroy($receta, $ingrediente);

        Tools::notificaUIFlash($resultado->original["tipo"], $resultado->orginal["mensaje"]);

        return redirect()->route("recetas.edit",["receta"=>$receta->id]);
    }



    private function notificaOk(){
        return Tools::notificaUIFlash("info", "Acción realizada correctamente");
    }
}
