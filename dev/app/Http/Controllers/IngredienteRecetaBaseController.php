<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Tools;
use App\Models\Receta;
use App\Models\Ingrediente;
use Illuminate\Http\Request;
use stdClass;

class IngredienteRecetaBaseController extends Controller
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
    public function index(Receta $receta)
    {
        /** @var User */
        $user = auth()->user();

        if($receta->user_id != NULL && $receta->user_id != $user->id ){
            return response(["tipo"=>"error","mensaje"=>"No tiene permiso para realizar esta acción"]);
        }

        return $receta->ingredientes()->get();
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receta  $receta
     * @param  \App\Models\Ingrediente  $ingrediente
     * @return \Illuminate\Http\Response|Ingrediente
     */
    public function show(Receta $receta, Ingrediente $ingrediente)
    {
        /** @var User */
        $user = auth()->user();

        if($receta->user_id != NULL && $receta->user_id != $user->id ){
            return response(["tipo"=>"error","mensaje"=>"No tiene permiso para realizar esta acción"]);
        }

        $res = $receta->ingredientes()->find($ingrediente);
        if(!$res){
            $res = response(['tipo'=>'error','mensaje' => "El ingrediente " . $ingrediente->nombre . " no está en la receta"]);
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
    public function create(Request $request, Receta $receta)
    {
        /** @var User */
        $user = auth()->user();
        
        $request->session()->flash('url_retorno', route('recetas.ingrediente.create', ['receta'=>$receta->id ]));
        
        $ingredientes = $user->getAllIngredientesAccesibles();        

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
    public function store(Request $request, Receta $receta)
    {
        $data = $this->validate($request, $this->rules);

        $ingrediente = Ingrediente::find($data['ingrediente']);

        if(!$ingrediente){
            return Tools::getResponse("error", "El ingrediente no existe", 200);
        }

        if($receta->ingredientes()->find($data['ingrediente'])){
            return Tools::getResponse("error", "El ingrediente ya existe en la receta", 200);
        }

        $receta->ingredientes()->attach($ingrediente, ['cantidad' => $data['cantidad'], 'unidad_medida' => $data['unidad_medida']]);
        
        $resultado = (object)[
            'tipo' => 'info',
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
     * @return \Illuminate\Http\Response|stdClass
     */
    public function update(Request $request, Receta $receta, Ingrediente $ingrediente)
    {
        $data = $this->validate($request, $this->rules);

        if($receta->ingredientes()->find($ingrediente) == NULL){
            Tools::notificaUIFlash("error","El ingrediente no está en la receta");
            return Tools::getResponse("error","El ingrediente no está en la receta",200);
        }
        
        $receta->ingredientes()->detach($ingrediente);
        $receta->ingredientes()->attach($ingrediente,["cantidad"=>$data['cantidad'], "unidad_medida"=>$data['unidad_medida']]);

        $res = (object)[
            'tipo' => 'info',
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
    public function destroy(Receta $receta, Ingrediente $ingrediente)
    {
        if($receta->ingredientes()->find($ingrediente) == NULL){
            return Tools::getResponse("error","El ingrediente no está en la receta",200);
        }

        $receta->ingredientes()->detach($ingrediente);
        
        return Tools::getResponse("info","La acción se ha realizado correctamente",200);
    }
}
