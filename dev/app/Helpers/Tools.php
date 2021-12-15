<?php

namespace App\Helpers;

class Tool{

    public static function notificaUIFlash(string $tipo, string $mensaje) : void
    {
        session()->flash('notificacion',(object)['tipo' => $tipo, 'mensaje' => $mensaje]);
    }
    
}