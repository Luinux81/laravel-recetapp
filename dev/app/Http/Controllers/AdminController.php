<?php

namespace App\Http\Controllers;

use App\Helpers\Tools;
use App\Models\Ingrediente;
use App\Models\Receta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function reviewPublishedQueue()
    {        
        $modelos = [];
        $res = DB::table('publish_queue')->select()->get();

        foreach ($res as $valor) {
            switch ($valor->tipo) {
                case 'I':
                    $temp = Ingrediente::findOrFail($valor->model_id);        
                    break;
                case 'R':
                    $temp = Receta::findOrFail($valor->model_id);        
                    break;
                default:
                    $temp = NULL;
                    break;
            }

            if($temp){
                $modelos[$valor->id]= $temp;
            }
        }

        return view('admin.review-publish-queue', compact('modelos'));
    }


    public function reviewPublish($id)
    {
        $item = DB::table('publish_queue')->select()->where('id', '=', $id)->first();

        switch ($item->tipo) {
            case 'I':
                $res = redirect()->route('ingredientes.show', ['ingrediente' => Ingrediente::findOrFail($item->model_id)]);
                break;
            case 'R':
                $res = redirect()->route('recetas.show', ['receta' => Receta::findOrFail($item->model_id)]);
                break;
            
            default:
                $res = redirect()->route('admin.review-publish-queue');
                break;
        }

        return $res;
    }


    public function confirmPublish($id)
    {
        $item = DB::table('publish_queue')->select()->where('id', '=', $id)->first();

        switch ($item->tipo) {
            case 'I':
                $modelo = Ingrediente::findOrFail($item->model_id);
                break;
            case 'R':
                $modelo = Receta::findOrfail($item->model_id);
                break;
            default:                
                break;
        }

        if(!empty($modelo)){
            $modelo->update([
                'publicado' => 1,
            ]);

            DB::table('publish_queue')->delete($id);

            $res = Tools::getResponse("info", "Elemento publicado con éxito", 200);
        }
        else{
            $res = Tools::getResponse("error", "No se ha encontrado el modelo", 404);
        }

        return $res;
    }


    public function denyPublish($id)
    {
        DB::table('publish_queue')->delete($id);

        $res = Tools::getResponse("info", "Acción realizada con éxito", 200);

        return $res;
    }
}
