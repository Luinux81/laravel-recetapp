<?php

namespace App\Helpers;

use Exception;
use App\Models\Receta;
use App\Models\Ingrediente;
use App\Models\User;

class Tools{

    public static function getResponse(string $tipo, string $mensaje, int $codigo)
    {
        return response(["tipo"=>$tipo, "mensaje"=>$mensaje],$codigo);
    }


    public static function notificaUIFlash(string $tipo, string $mensaje) : void
    {
        session()->flash('notificacion',(object)['tipo' => $tipo, 'mensaje' => $mensaje]);
    }


    public static function notificaOk()
    {
        return Tools::notificaUIFlash("info", "Acción realizada correctamente");
    }


    public static function checkOrFail(object $modelo, string $permiso = "") : void
    {
        /** @var User */
        $user = auth()->user();

        // Miramos la clase del objeto de entrada y hacemos la verificación
        $class = get_class($modelo); 
        
        switch ($class) {
            case "\App\Models\Receta":
            case "\App\Models\Ingrediente":
            case "\App\Models\CategoriaIngrediente":
            case "\App\Models\CategoriaReceta":
                $temp = new $class();
                break;
            
            default:
                throw new Exception("Acción no permitida", 400);
                break;
        }

        // switch (get_class($modelo)) {
        //     case "\App\Models\Receta":
        //         $temp = new Receta();
        //         $temp = $modelo;
        //         break;

        //     case "\App\Models\Ingrediente":
        //         $temp = new Ingrediente();
        //         $temp = $modelo;
        //         break;
            
        //     default:
        //         throw new Exception("Acción no permitida", 400);
        //         break;
        // }

        // Los modelos que se dejen pasar deben tener una propiedad publica user_id
        if ($permiso){
            if($temp->user_id == NULL && $user->can($permiso)){
                throw new Exception("No tiene permiso para realizar esta acción", 401);
            }
        }

        if ($temp->user_id != NULL && ($temp->user_id != $user->id)){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }
    }
}