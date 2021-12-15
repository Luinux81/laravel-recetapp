<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    protected function index(Receta $receta){
        /** @var User */
        $user = auth()->user();

        if($receta->user_id != NULL){
            if($receta->user_id != $user->id){
                return Tools::getResponse("error","No tiene permiso para realizar esta acción",401);
            }
        }

        return $receta->pasos()->get();
    }


    protected function show(Receta $receta, PasoReceta $paso){
        /** @var User */
        $user = auth()->user();

        if($receta->user_id != NULL){
            if($receta->user_id != $user->id){
                return Tools::getResponse("error","No tiene permiso para realizar esta acción",401);
            }
        }

        return $receta->pasos()->where("id",$paso->id)->first();
    }


    protected function create(Receta $receta){
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
    protected function store(Receta $receta, Request $request){
        /** @var User */
        $user = auth()->user();

        if($receta->user_id != $user->id){
            return Tools::getResponse("error","No tiene permiso para realizar esta acción",401);
        }

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
        return redirect()->route('recetas.paso.edit', compact(['receta', 'paso']));
    }


    /**
     * Devuelve la vista para editar pasos de recetas
     *
     * @param Receta $receta
     * @param PasoReceta $paso
     * @return 
     */
    protected function edit(Receta $receta, PasoReceta $paso){
        /** @var User */
        $user = auth()->user();

        if ($receta->user_id == NULL){
            if($receta->user_id != $user->id){
                return Tools::getResponse("error","No tiene pemiso para realizar esta acción",401);
            }
        }

        $assets = $paso->assets()->get();
        
        return view('recetas.pasos.edit', compact(['receta','paso','assets']));
    }


    protected function update(Receta $receta, PasoReceta $paso, Request $request){
        /** @var User */
        $user = auth()->user();

        if($receta->user_id != $user->id){
            return Tools::getResponse("error","No tiene permiso para realizar está acción", 401);
        }

        if($receta->pasos()->find($paso->id) == NULL){
            return Tools::getResponse("error","El paso no pertenece a la receta", 200);
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
        // return redirect()->route('recetas.edit', compact('receta'));
    }


    protected function destroy(Receta $receta, PasoReceta $paso){
        /** @var User */
        $user = auth()->user();

        if($receta->user_id != $user->id){
            return Tools::getResponse("error", "No tiene permiso para realizar esta acción", 401);
        }

        if($receta->id != $paso->receta_id){
            return Tools::getResponse("error", "El paso no pertence a la receta", 401);
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

        //return redirect()->route('recetas.edit', compact('receta'));
    }
}
