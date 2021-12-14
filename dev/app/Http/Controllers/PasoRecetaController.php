<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\PasoReceta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PasoRecetaController extends Controller
{
    protected $rules = [
        'orden' => 'required|numeric|min:1',
        'texto' => 'required',
    ];

    public function create(Receta $receta){
        return view('recetas.pasos.create', compact('receta'));
    }

    public function store(Receta $receta, Request $request){
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

        return redirect()->route('recetas.paso.edit', compact(['receta', 'paso']));
    }

    public function edit(Receta $receta, PasoReceta $paso){
        $assets = $paso->assets()->get();
        
        foreach ($assets as $asset) {
            $imagenes[$asset->id]= $asset->ruta;
        }

        return view('recetas.pasos.edit', compact(['receta','paso','assets']));
    }

    public function update(Receta $receta, PasoReceta $paso, Request $request){
        $max = $receta->pasos()->count();
        $this->rules['orden'] = $this->rules['orden'] . "|max:" . $max;

        $data = $this->validate($request, $this->rules);

        //$pasosParaOrdenar = $receta->pasos()->where('orden','>=',$data['orden'])->orderBy('orden')->get();
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

        return redirect()->route('recetas.edit', compact('receta'));
    }

    public function destroy(Receta $receta, PasoReceta $paso){
        $aux = $receta->pasos()->find($paso->id);

        $pasosParaOrdenar = $receta
                                ->pasos()
                                ->where('orden',">",$paso->orden)
                                ->orderBy('orden')
                                ->get();

        
        foreach ($paso->assets as $asset){            
            $asset->borradoCompleto();
        }
        
        $aux->delete();

        foreach($pasosParaOrdenar as $p){
            $p->update([
                'orden' => intval($p->orden) - 1,
            ]);
        }

        return redirect()->route('recetas.edit', compact('receta'));
    }
}
