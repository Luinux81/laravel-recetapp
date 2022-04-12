<?php

namespace App\Helpers;

use Exception;
use App\Models\Receta;
use App\Models\Ingrediente;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

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


    public static function checkOrFail(object $modelo, string $accion = "show") : bool
    {
        /** @var User */
        $user = auth()->user();

        // Miramos la clase del objeto de entrada y hacemos la verificación
        $class = get_class($modelo); 
        
        if($class[0] != "\\"){
            $class = "\\" . $class;
        }

        switch ($class) {
            case "\App\Models\Receta":
            case "\App\Models\Ingrediente":
            case "\App\Models\CategoriaIngrediente":
            case "\App\Models\CategoriaReceta":
                $temp = new $class();
                $temp = $modelo;
                break;
            
            default:
                throw new Exception("Modelo no permitido", 400);
                break;
        }


        switch ($accion){
            case "show":
            case "index":
                $permiso = "public_index";
                break;
            case "create":
                $permiso = "public_create";
                break;
            case "update":
                $permiso = "public_edit";
                break;
            case "delete":
                $permiso = "public_destroy";
                break;
            default:
                throw new Exception("Acción no permitida", 400);
                break;

        }

        // Los modelos que se dejen pasar deben tener una propiedad publica user_id
        if($temp->user_id != $user->id){
            // El usuario no es propietario del modelo, seguir comprobando ....
            if($temp->esPublico()){
                // El modelo es público, comprobar permiso
                if(!$user->can($permiso)){
                    throw new Exception("No tiene permiso para realizar esta acción en un recurso público", 401);
                }
            }
            else{
                // El modelo es privado y el usuario no es el propietario, comprobar permiso private_access
                if(!$user->can("private_access")){
                    throw new Exception("No tiene permiso para realizar esta acción sobre un recurso ajeno", 401);
                }
            }
        }

        // if($temp->esPublico())
        // {
        //     if(!$user->can($permiso)) 
        //         throw new Exception("No tiene permiso para realizar esta acción", 401);
        // }
        // else
        // {
        //     if(($temp->user_id != $user->id) && !$user->can("private_access"))
        //         throw new Exception("No tiene permiso para realizar esta acción", 401);
        // }


        // if ($permiso){
        //     if($temp->esPublico())
        //     {
        //         if(!$user->can($permiso)) 
        //             throw new Exception("No tiene permiso para realizar esta acción", 401);
        //     }
        //     else
        //     {
        //         if(($temp->user_id != $user->id) && !$user->can("private_access"))
        //             throw new Exception("No tiene permiso para realizar esta acción", 401);
        //     }
        // }
        // else{
        //     if ($temp->esPublico()){
        //         throw new Exception("No tiene permiso para realizar esta acción", 401);
        //     }
        //     else{
        //         if (($temp->user_id != $user->id) && !$user->can("private_access")){
        //             throw new Exception("No tiene permiso para realizar esta acción", 401);
        //         }
        //     }
        // }
        

        return true;
    }

    public static function getImagen64($ruta)
    {
        $full_path = Storage::path($ruta);
        $base64 = base64_encode(Storage::get($ruta));
        $image_data = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;

        return $image_data;
    }
}