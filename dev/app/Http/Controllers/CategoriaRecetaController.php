<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaReceta;
use Illuminate\Support\Facades\Auth;

class CategoriaRecetaController extends Controller
{
    protected $rules = [
        "nombre" => "required",
        "descripcion"=>"",
        "cat_parent"=>"",
    ];

    public function index(){
        $categorias = Auth::user()->categoriasReceta()->get();
        
        return view('recetas.categorias.index',compact('categorias'));
    }

    public function create(){
        $categorias = Auth::user()->categoriasReceta()->get();

        return view('recetas.categorias.create',compact('categorias'));
    }

    public function store(Request $request){
        $data = $this->validate($request,$this->rules);

        $categoria = new CategoriaReceta();

        $categoria->fill([
            "user_id"=>Auth::user()->id,
            "nombre"=>$data['nombre'],
            "descripcion"=>$data['descripcion'],
            "catParent_id"=>$data['cat_parent'],
            ]);
        
        $categoria->save();

        return redirect()->route("recetas.categoria.index");
    }

    public function edit(CategoriaReceta $categoria){
        //$categorias = Auth::user()->categoriasReceta()->get();
        $categoriasHija = $categoria->categoriasHija(true);

        return view('recetas.categorias.edit',compact(['categoria', 'categoriasHija']));        
    }

    public function update(CategoriaReceta $categoria, Request $request){
        $data = $this->validate($request, $this->rules);

        if(empty($data['cat_parent'])){
            $data['cat_parent'] = NULL;
        }

        $categoria->update([
            "nombre"=>$data['nombre'],
            "descripcion"=>$data['descripcion'],
            "catParent_id"=>$data['cat_parent'],
        ]);

        return redirect()->route('recetas.categoria.index');
    }

    public function destroy(CategoriaReceta $categoria){
        $categoria->delete();

        return redirect()->route('recetas.categoria.index');
    }
}
