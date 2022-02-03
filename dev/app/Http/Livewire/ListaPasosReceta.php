<?php

namespace App\Http\Livewire;

use App\Models\PasoReceta;
use App\Models\Receta;
use Livewire\Component;

class ListaPasosReceta extends Component
{
    public $receta;

    protected $listeners = ['new-paso-receta' => 'createPaso'];

    public function render()
    {
        return view('livewire.lista-pasos-receta', ['receta' => $this->receta->id]);
    }

    public function updateOrdenPasos($list)
    {
        foreach ($list as $item) {
            PasoReceta::find($item['value'])->update(['orden' => $item['order']]);
        }
    }

    public function createPaso($payload){

        // dd($payload);

        $pasos = $this->receta->pasos();

        $max = $pasos->pluck('orden')->max();

        $pasos->insert([
            'receta_id' => $this->receta->id,
            'orden' => $max + 1,
            'texto' => $payload["texto"]
        ]);
    }
}
