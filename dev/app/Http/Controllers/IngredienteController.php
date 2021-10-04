<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IngredienteController extends Controller
{   
    public function index(){
        $ingredientes = Auth::user()->ingredientes()->get();
        
        return view('ingredientes.index', compact('ingredientes'));
    }
}
