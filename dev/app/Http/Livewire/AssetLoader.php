<?php

namespace App\Http\Livewire;

use App\Models\PasoReceta;
use Livewire\Component;

class AssetLoader extends Component
{
    public $origen;
    public $id_modelo;
    public $modo;

    public $modelo;


    // TODO: Detectar si el origen al que pertence el paso es publico o no y obtener prefijo para el path(hacer en el controlador)

    public function mount($origen, $id_modelo, $modo){
        $this->origen = $origen;
        $this->id_modelo = $id_modelo;
        
        if($modo == "edit"){
            $this->modo = "edit";
        }
        else{
            $this->modo = "show";
        }

        switch ($origen) {
            case 'PasoReceta':
                $this->modelo = PasoReceta::find($this->id_modelo);
                break;
            
            default:
                $this->modelo = null;
                break;
        }
    }

    public function render()
    {
        $assets = collect();
        $publico = "";

        if($this->modelo != null){          
            $publico = $this->modelo->esPublico();
            
            if($publico){
                $ruta = "/storage/";
            }
            else{
                $ruta = "users/" . $this->modelo->user()->id . "/";
            }

            $assets = $this->modelo->assets()->pluck('ruta');

            $rutas = collect();

            foreach ($assets as $r) {
                $rutas->push($ruta . $r);
            }
        }

        return view('livewire.asset-loader',['modelo' => $this->modelo, 'rutas' => $rutas, 'publico' => $publico ]);
    }
}
