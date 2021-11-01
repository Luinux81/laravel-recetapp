<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\Ingrediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IngredienteRecetaController extends Controller
{

    protected $rules = [
        'ingrediente' => 'required',
        'cantidad' => 'numeric|required',
        'unidad_medida' => 'required',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \App\Models\Receta 
     * @return \Illuminate\Http\Response
     */
    public function create(Receta $receta)
    {
        $ingredientes = Auth::user()->ingredientes()->get();        

        return view('recetas.ingredientes.create', compact(['receta','ingredientes']));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Models\Receta
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Receta $receta, Request $request)
    {
        $data = $this->validate($request, $this->rules);

        $ingrediente = Ingrediente::findOrFail($data['ingrediente']);

        $receta->ingredientes()->attach($ingrediente, ['cantidad' => $data['cantidad'], 'unidad_medida' => $data['unidad_medida']]);
        
        return redirect()->route('recetas.edit',['receta' => $receta->id]);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Receta $receta
     * @param \App\Models\Ingrediente $ingrediente
     * @return \Illuminate\Http\Response
     */
    public function edit(Receta $receta, Ingrediente $ingrediente)
    {
        return view('recetas.ingredientes.edit', compact(['receta','ingrediente']));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param \App\Models\Receta $receta
     * @param \App\Models\Ingrediente $ingrediente
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Receta $receta, Ingrediente $ingrediente,Request $request)
    {
        $data = $this->validate($request, $this->rules);

        $receta->ingredientes()->detach($ingrediente);
        $receta->ingredientes()->attach($ingrediente,["cantidad"=>$data['cantidad'], "unidad_medida"=>$data['unidad_medida']]);

        return redirect()->route('recetas.edit', ['receta'=>$receta->id]);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Receta $receta
     * @param \App\Models\Ingrediente $ingrediente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta, Ingrediente $ingrediente)
    {
        $receta->ingredientes()->detach($ingrediente);

        return redirect()->route('recetas.edit', ['receta'=>$receta->id]);
    }
}
