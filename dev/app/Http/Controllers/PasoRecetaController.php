<?php

namespace App\Http\Controllers;

use Exception;
use App\Helpers\Tools;
use App\Models\Receta;
use App\Models\PasoReceta;
use Illuminate\Http\Request;

class PasoRecetaController extends Controller
{
    protected $rules = [
        'orden' => 'required|numeric|min:1',
        'texto' => 'required',
    ];

    protected function index(Receta $receta)
    {
        Tools::checkOrFail($receta, "index");

        return $receta->pasos()->get();
    }


    protected function show(Receta $receta, PasoReceta $paso)
    {
        Tools::checkOrFail($receta, "show");

        return $receta->pasos()->where("id",$paso->id)->first();
    }


    protected function create(Receta $receta)
    {
        Tools::checkOrFail($receta, "update");

        return view('recetas.pasos.create', compact('receta'));
    }


    /**
     * Guarda un nuevo PasoReceta a la receta especificada
     *
     * @param Receta $receta
     * @param Request $request
     * 
     * @return \Illuminate\Http\Response|\App\Models\PasoReceta
     */
    protected function store(Receta $receta, Request $request)
    {
        Tools::checkOrFail($receta, "update");

        $max = $receta->pasos()->count() + 1;
        $this->rules['orden'] = $this->rules['orden'] . "|max:" . $max;

        $data = $this->validate($request, $this->rules);

        $pasosParaOrdenar = $receta->pasos()->where('orden','>=',$data['orden'])->get();

        $paso = PasoReceta::create([
            'receta_id' => $receta->id,
            'orden' => $data['orden'],
            'texto' => $data['texto'],
        ]);

        foreach($pasosParaOrdenar as $p){
            $p->update([
                'orden' => intval($p->orden) + 1,
            ]);
        }

        return $paso;
    }


    /**
     * Devuelve la vista para editar pasos de recetas
     *
     * @param Receta $receta
     * @param PasoReceta $paso
     * @return 
     */
    protected function edit(Receta $receta, PasoReceta $paso)
    {
        Tools::checkOrFail($receta, "update");

        $assets = $paso->assets()->get();
        
        return view('recetas.pasos.edit', compact(['receta','paso','assets']));
    }


    protected function update(Receta $receta, PasoReceta $paso, Request $request)
    {
        Tools::checkOrFail($receta, "update");

        if($receta->pasos()->find($paso->id) == NULL){
            throw new Exception("El paso no pertenece a la receta", 400);            
        }

        $max = $receta->pasos()->count();
        $this->rules['orden'] = $this->rules['orden'] . "|max:" . $max;

        $data = $this->validate($request, $this->rules);
        
        $pasos = $receta->pasos()->orderBy('orden');
        $pasosParaOrdenar = collect();

        $old = intval($paso->orden);
        $new = intval($data['orden']);

        if($old > $new){
            //Bajamos el orden del paso
            $min = $new;
            $max = $old-1;
            $incrementar = true;
        }
        else{
            //Subimos el orden del paso
            $min = $old+1;
            $max = $new;
            $incrementar = false;
        }

        if($old != $new){
            $pasosParaOrdenar = $pasos
                ->where('orden',">=",$min)
                ->where('orden',"<=",$max)
                ->get();
        }


        $receta->pasos()->find($paso->id)->update([
            'orden' => $data['orden'],
            'texto' => $data['texto'],
        ]);

        foreach ($pasosParaOrdenar as $p) {
            if($incrementar){
                $newValue = intval($p->orden) + 1;
            }
            else{
                $newValue = intval($p->orden) - 1;
            }
            $p->update(['orden' => $newValue]);
        }

        return $paso;        
    }


    protected function destroy(Receta $receta, PasoReceta $paso)
    {
        Tools::checkOrFail($receta, "update");

        if($receta->pasos()->find($paso->id) == NULL){
            throw new Exception("El paso no pertenece a la receta", 400);            
        }

        $pasosParaOrdenar = $receta
                                ->pasos()
                                ->where('orden',">",$paso->orden)
                                ->orderBy('orden')
                                ->get();

        
        foreach ($paso->assets as $asset){            
            $asset->borradoCompleto();
        }
        
        $paso->delete();

        foreach($pasosParaOrdenar as $p){
            $p->update([
                'orden' => intval($p->orden) - 1,
            ]);
        }

        return Tools::getResponse("info","La acción se ha realizado correctamente",200);
    }

}
