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
        'deletePaso' => 'deletePaso',
        'updatePaso' => 'updatePaso',
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
        // if(!$this->checkAuth()) return;

        $paso = PasoReceta::find($payload["paso"]);

        if ($paso == null){
            $this->dispatchBrowserEvent("msg-err", "La referencia al paso no es correcta");
            return;
        }

        if(!$this->receta->pasos()->get()->contains($paso)){
            $this->dispatchBrowserEvent("msg-err", "El paso " . $paso->id . " no pertence a la receta");
            return;
        } 

        // dd($paso, $payload);

        $paso->update([
            'texto' => $payload["texto"],
        ]);
    }

    private function checkAuth()
    {
        $res = true;
        try {
            Tools::checkOrFail($this->receta, "show");
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('msg-err',$th->getMessage());
            $res = false;
        }
        finally{
            return $res;
        }
    }
}
