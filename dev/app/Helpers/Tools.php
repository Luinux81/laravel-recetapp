<?php

namespace App\Helpers;

class Tools{

    public static function notificaUIFlash(string $tipo, string $mensaje) : void
    {
        session()->flash('notificacion',(object)['tipo' => $tipo, 'mensaje' => $mensaje]);
    }
    

    public static function getResponse(string $tipo, string $mensaje, int $codigo){
        return response(["tipo"=>$tipo, "mensaje"=>$mensaje],$codigo);
    }

    public static function notificaOk(){
        return Tools::notificaUIFlash("info", "Acción realizada correctamente");
    }
}