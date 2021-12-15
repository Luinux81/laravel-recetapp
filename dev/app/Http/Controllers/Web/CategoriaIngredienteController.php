<?php

namespace App\Http\Controllers\Web;

use App\Helpers\Tool;
use Illuminate\Http\Request;
use App\Models\CategoriaIngrediente;
use App\Http\Controllers\CategoriaIngredienteBaseController;

class CategoriaIngredienteController extends CategoriaIngredienteBaseController
{
    public function index(){
        $categorias = parent::index();

        return view('ingredientes.categorias.index',compact('categorias'));
    }



    public function show(CategoriaIngrediente $categoria){
        // $respuesta = parent::show($categoria);

        // switch ($respuesta) {
        //     case "Illuminate\Http\Response":
        //         Tool::notificaUIFlash($respuesta->original["tipo"], $respuesta->original["mensaje"]);

        //         break;
            
        //     default:
        //         # code...
        //         break;
        // }
    }



    public function create(){
        $categorias = parent::create();

        return view('ingredientes.categorias.create',compact('categorias'));
    }



    public function store(Request $request){
        $respuesta = parent::store($request);

        switch (get_class($respuesta)) {
            case "Illuminate\Http\Response":
                Tool::notificaUIFlash($respuesta->original['tipo'], $respuesta->original['mensaje']);
                $res = redirect()->route('ingredientes.categoria.create');
                break;
            
            case "App\Models\CategoriaIngrediente":
                Tool::notificaUIFlash("info", "Acción realizada con éxito");

            default:
                $res = redirect()->route('ingredientes.categoria.index');
                break;
        }

        return $res;
    }



    public function edit(CategoriaIngrediente $categoria){
        $categoriasHija = parent::edit($categoria);
        
        return view('ingredientes.categorias.edit',compact(['categoria', 'categoriasHija']));    
    }



    public function update(Request $request, CategoriaIngrediente $categoria){
        $respuesta = parent::update($request,$categoria);

        switch (get_class($respuesta)) {
            case "Illuminate\Http\Response":                                
                Tool::notificaUIFlash($respuesta->original["tipo"], $respuesta->original["mensaje"]);
                $res = redirect()->route('ingredientes.categoria.edit',['categoria'=>$categoria->id]);
                break;                

            case "App\Models\CategoriaReceta":       
                Tool::notificaUIFlash("info", "Categoría creada con éxito");
                
            default:
                $res = redirect()->route('ingredientes.categoria.index');                
        }
        
        return $res;
    }



    public function destroy(CategoriaIngrediente $categoria){
        $respuesta = parent::destroy($categoria);

        Tool::notificaUIFlash($respuesta->original["tipo"], $respuesta->original["mensaje"]);

        return redirect()->route('ingredientes.categoria.index'); 
    }
}
