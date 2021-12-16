<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helpers\Tools;
use App\Models\Receta;
use App\Models\Ingrediente;
use Illuminate\Http\Request;
use stdClass;

class IngredienteRecetaController extends Controller
{

    //TODO: HACER CHECKS DE INGREDIENTES IGUAL QUE LAS RECETAS EN STORE Y UPDATE
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
    protected function index(Receta $receta)
    {        
        $this->checkOrFail($receta);

        return $receta->ingredientes()->get();
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receta  $receta
     * @param  \App\Models\Ingrediente  $ingrediente
     * @return \Illuminate\Http\Response|Ingrediente
     */
    protected function show(Receta $receta, Ingrediente $ingrediente)
    {
        $this->checkOrFail($receta);

        $res = $receta->ingredientes()->find($ingrediente);
        if(!$res){
            throw new Exception("El ingrediente " . $ingrediente->nombre . " no está en la receta", 400);
        }

        return $res;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request
     * @param \App\Models\Receta 
     * 
     * @return \Illuminate\Http\Response
     */
    protected function create(Request $request, Receta $receta)
    {
        $this->checkOrFail($receta);

        $request->session()->flash('url_retorno', route('recetas.ingrediente.create', ['receta'=>$receta->id ]));
        
        $ingredientes = $this->user()->getAllIngredientesAccesibles();        

        return view('recetas.ingredientes.create', compact(['receta','ingredientes']));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param \App\Models\Receta $receta     
     * 
     * @return \Illuminate\Http\Response|stdClass
     */
    protected function store(Request $request, Receta $receta)
    {
        $this->checkOrFail($receta);

        $data = $this->validate($request, $this->rules);

        $ingrediente = Ingrediente::find($data['ingrediente']);

        if(!$ingrediente){
            throw new Exception("El ingrediente no existe", 400);
        }

        if($receta->ingredientes()->find($data['ingrediente'])){
            throw new Exception("El ingrediente ya existe en la receta", 400);
        }

        $receta->ingredientes()->attach($ingrediente, ['cantidad' => $data['cantidad'], 'unidad_medida' => $data['unidad_medida']]);
        
        $resultado = (object)[
            'ingrediente' => $ingrediente,
            'cantidad' => $data['cantidad'],
            'unidad_medida' => $data['unidad_medida']
        ];

        return $resultado;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Receta $receta
     * @param \App\Models\Ingrediente $ingrediente
     * 
     * @return \Illuminate\Http\Response
     */
    protected function edit(Receta $receta, Ingrediente $ingrediente)
    {
        $this->checkOrFail($receta);

        return view('recetas.ingredientes.edit', compact(['receta','ingrediente']));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Models\Receta $receta
     * @param \App\Models\Ingrediente $ingrediente
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|stdClass
     */
    protected function update(Request $request, Receta $receta, Ingrediente $ingrediente)
    {
        $this->checkOrFail($receta);

        $data = $this->validate($request, $this->rules);

        if($receta->ingredientes()->find($ingrediente) == NULL){
            throw new Exception("El ingrediente no existe en la receta", 400);
        }
        
        $receta->ingredientes()->detach($ingrediente);
        $receta->ingredientes()->attach($ingrediente,["cantidad"=>$data['cantidad'], "unidad_medida"=>$data['unidad_medida']]);

        $res = (object)[
            'ingrediente' => $ingrediente,
            'cantidad' => $data['cantidad'],
            'unidad_medida' => $data['unidad_medida']
        ];

        return $res;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Receta $receta
     * @param \App\Models\Ingrediente $ingrediente
     * @return \Illuminate\Http\Response|stdClass
     */
    protected function destroy(Receta $receta, Ingrediente $ingrediente)
    {
        $this->checkOrFail($receta);

        if($receta->ingredientes()->find($ingrediente) == NULL){
            throw new Exception("El ingrediente no existe en la receta", 400);
        }

        $receta->ingredientes()->detach($ingrediente);
        
        return Tools::getResponse("info","La acción se ha realizado correctamente",200);
    }

    private function user() : User
    {
        return auth()->user();
    }

    private function checkOrFail(Receta $receta) : void
    {
        if($receta->user_id != NULL && $receta->user_id != $this->user()->id ){
            throw new Exception("No tiene permiso para realizar esta acción", 401);
        }
    }
}
