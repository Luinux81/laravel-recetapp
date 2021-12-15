<?php

namespace App\Http\Controllers\Web;

use App\Helpers\Tools;
use Illuminate\Http\Request;
use App\Http\Controllers\CategoriaRecetaBaseController;
use App\Models\CategoriaReceta;

class CategoriaRecetaController extends CategoriaRecetaBaseController
{
    public function index(){
        $categorias = parent::index();
        
        return view('recetas.categorias.index',compact('categorias'));
    }


    public function create(){
        $categorias = parent::create();

        return view('recetas.categorias.create',compact('categorias'));
    }


    public function store(Request $request){
        $respuesta = parent::store($request);

        switch(get_class($respuesta)){
            case "Illuminate\Http\Response":                 
                Tools::notificaUIFlash($respuesta->original["tipo"], $respuesta->original["mensaje"]);
                $res = redirect()->route('recetas.categoria.create');
                break;

            case "App\Models\CategoriaReceta":
                Tools::notificaUIFlash("info", "Categoría creada con éxito");

            default:
                $res = redirect()->route("recetas.categoria.index");
        }

        return $res;
    }


    public function edit(CategoriaReceta $categoria){
        $categoriasHija = parent::edit($categoria);
        
        return view('recetas.categorias.edit',compact(['categoria', 'categoriasHija']));        
    }

    public function update(Request $request, CategoriaReceta $categoria){
        $respuesta = parent::update($request,$categoria);

        switch (get_class($respuesta)) {
            case "Illuminate\Http\Response":                                
                Tools::notificaUIFlash($respuesta->original["tipo"], $respuesta->original["mensaje"]);
                $res = redirect()->route('recetas.categoria.edit',['categoria'=>$categoria->id]);
                break;                

            case "App\Models\CategoriaReceta":       
                Tools::notificaUIFlash("info", "Categoría creada con éxito");
                
            default:
                $res = redirect()->route('recetas.categoria.index');                
        }
        
        return $res;
    }

    public function destroy(CategoriaReceta $categoria)
    {
        $respuesta = parent::destroy($categoria);

        Tools::notificaUIFlash($respuesta->original["tipo"], $respuesta->original["mensaje"]);

        return redirect()->route('recetas.categoria.index'); 
    }
}
