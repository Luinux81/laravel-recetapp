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
}
