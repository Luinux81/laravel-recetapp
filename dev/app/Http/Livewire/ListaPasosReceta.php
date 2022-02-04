<?php

namespace App\Http\Livewire;

use App\Helpers\Tools;
use App\Models\Receta;
use Livewire\Component;
use App\Models\PasoReceta;

// TODO: Hacer comprobaciones de seguridad en las operaciones createPaso, etc...
class ListaPasosReceta extends Component
{
    public $receta;

    protected $listeners = [
        'new-paso-receta' => 'createPaso',
        'deletePaso' => 'deletePaso'
    ];

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
        // if(!$this->checkAuth()) return;

        $pasos = $this->receta->pasos();

        $max = $pasos->pluck('orden')->max();

        $pasos->insert([
            'receta_id' => $this->receta->id,
            'orden' => $max + 1,
            'texto' => $payload["texto"]
        ]);
    }

    public function confirmacionBorrado($id)
    {
        $this->dispatchBrowserEvent('swal:confirm',[
            'type' => 'warning',
            'title' => 'Confirmación de borrado',
            'text' => 'Está seguro de eliminar el registro?',
            'id' => $id,
        ]);
    }


    public function deletePaso(PasoReceta $paso)
    {
        // if(!$this->checkAuth()) return;

        if(!$this->receta->pasos()->get()->contains($paso)) return;

        $paso->borradoCompleto();
    }

    public function updatePaso($payload)
    {

    }

    private function checkAuth()
    {
        $res = true;
        try {
            Tools::checkOrFail($this->receta);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('msg-err',$th->getMessage());
            $res = false;
        }
        finally{
            return $res;
        }
    }
}
