<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaIngredienteController extends Controller
{
    public function index(){
        $categorias = Auth::user()->categoriasIngrediente()->get();

        return view('ingredientes.categorias.index',compact('categorias'));
    }
}
