<?php

namespace App\Http\Controllers\Api;

use App\Models\Receta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PasoReceta;
use App\Models\User;

class PasoRecetaController extends Controller
{
    protected $rules = [
        'orden' => 'required|numeric|min:1',
        'texto' => 'required',
    ];

    public function index(Receta $receta){
        $pasos = $receta->pasos()->get();

        return $pasos;
    }

    public function show(Receta $receta, PasoReceta $paso){
        $res = $receta->pasos()->where("id",$paso->id)->first();

        return $res;
    }

    public function store(Request $request, Receta $receta){
        $max = $receta->pasos()->count() + 1;
        $this->rules['orden'] = $this->rules['orden'] . "|max:" . $max;

        $data = $request->validate($this->rules);

        $pasosParaOrdenar = $receta->pasos()->where('orden','>=',$data['orden'])->get();

        $paso = $receta->pasos()->create([
            'orden' => $data['orden'],
            'texto' => $data['texto']
        ]);

        foreach ($pasosParaOrdenar as $p) {
            $p->update([
                'orden' => $p->orden + 1,
            ]);
        }

        return response($paso,201);
    }

    public function update(Request $request, Receta $receta, PasoReceta $paso){
        $maxOrden = $receta->pasos()->count();
        $this->rules['orden'] = $this->rules['orden'] . "|max:" . $maxOrden;

        $data = $request->validate($this->rules);

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

        $paso->update([
            'orden' => $data['orden'],
            'texto' => $data['texto']
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
        
        return response(['paso'=>$paso,'all'=>$receta->pasos()->get()],200);
    }

    public function destroy(Receta $receta, PasoReceta $paso){
        /** @var User */
        $user = auth()->user();
        $pasosParaOrdenar = collect();

        if($user->recetas()->find($receta)->first()){
            if($receta->pasos()->find($paso)->first()){
                $paso->borradoCompleto();
                $check = true;

                $pasosParaOrdenar = $receta->pasos()->where('orden','>',$paso->orden)->get();
            }
        }

        foreach ($pasosParaOrdenar as $p) {
            $p->update([
                'orden' => intval($p->orden) - 1,
            ]);
        }

        if($check){
            $mensaje = "Acción completada con éxito";
            $code = 200;            
        }
        else{
            $mensaje = "No tiene permiso para realizar esta acción";
            $code = 401;            
        }

        return response([$mensaje,$receta->pasos()->get()],$code);
    }
}
