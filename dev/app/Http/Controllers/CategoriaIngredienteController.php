<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaIngrediente;
use Illuminate\Support\Facades\Auth;

class CategoriaIngredienteController extends Controller
{
    protected $rules= [
        'cat_nombre'=>'required',
        'cat_descripcion'=>'',
        'cat_parent'=>'',
    ];

    public function index(){
        $categorias = Auth::user()->categoriasIngrediente()->get();

        return view('ingredientes.categorias.index',compact('categorias'));
    }

    public function create(){
        $categorias = Auth::user()->categoriasIngrediente()->get();

        return view('ingredientes.categorias.create',compact('categorias'));
    }

    public function edit(Request $request, CategoriaIngrediente $categoria){
        $categoriasHija = $categoria->categoriasHija(true);

        return view('ingredientes.categorias.edit',compact(['categoria','categoriasHija']));
    }

    public function store(Request $request){
        $data = $this->validate($request,$this->rules);
        

        $cat = new CategoriaIngrediente([
            'user_id'=>Auth::user()->id,
            'nombre'=>$data['cat_nombre'],
            'descripcion'=>$data['cat_descripcion'],
            'catParent_id'=>$data['cat_parent'],
        ]);

        $cat->save();

        return redirect()->route('ingredientes.categoria.index');
    }

    public function update(Request $request,CategoriaIngrediente $categoria){
        $data = $this->validate($request, $this->rules);
        
        if(empty($data['cat_parent'])){
            $data['cat_parent'] = NULL;
        }

        $categoria->update([
            'nombre'=>$data['cat_nombre'],
            'descripcion'=>$data['cat_descripcion'],
            'catParent_id'=>$data['cat_parent']
        ]);

        return redirect()->route('ingredientes.categoria.index');
    }

    public function destroy(CategoriaIngrediente $categoria){        
        $hijas = $categoria->categoriasHija(false);

        $recursivo = false;

        foreach ($hijas as $key => $hija) {
            if($recursivo){
                $hija->delete();
            }
            else{
                $hija->catParent_id = NULL;
                $hija->save();
            }
        }

        $categoria->delete();

        return redirect()->route('ingredientes.categoria.index');
    }
}
