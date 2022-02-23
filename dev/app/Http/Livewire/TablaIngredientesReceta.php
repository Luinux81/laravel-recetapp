<?php

namespace App\Http\Livewire;

use App\Models\Ingrediente;
use App\Models\Receta;
use Livewire\Component;

class TablaIngredientesReceta extends Component
{
    public $receta;

    public $creando_new = false;
    public $new_ingrediente_cantidad;
    public $new_ingrediente_unidad;
    public $new_ingrediente_id;

    public $editando = false;
    public $edit_ingrediente_cantidad;
    public $edit_ingrediente_unidad;
    public $edit_ingrediente_id;

    protected $listeners = [
        'refreshTabla' => '$refresh',
        'addIngredienteReceta',
        'updateIngredienteReceta',
        'deleteIngredienteReceta',
        'clearNewFormErrors'
    ];

    protected $rules = [
        'new_ingrediente_cantidad' => 'required',
        'new_ingrediente_unidad' => 'required',
        'new_ingrediente_id' => 'required',
    ];

    public function mount(Receta $receta)
    {
        $this->receta = $receta;
    }

    public function render()
    {
        return view('livewire.tabla-ingredientes-receta');
    }
    
    public function addIngredienteReceta($payload)
    {
        $this->new_ingrediente_cantidad = $payload["cantidad"];
        $this->new_ingrediente_unidad = $payload["unidad"];
        $this->new_ingrediente_id = $payload["ingrediente"];

        $this->validate();

        $ingrediente = Ingrediente::find($this->new_ingrediente_id);

        $this->receta
            ->ingredientes()
            ->attach($ingrediente, [
                    'cantidad' => $this->new_ingrediente_cantidad, 
                    'unidad_medida' => $this->new_ingrediente_unidad
                ]);
        
        $this->emitSelf('refreshTabla');
        $this->creando_new = false;
    }

    public function clearNewFormErrors()
    {
        $this->resetErrorBag();
    }

    public function setCreandoNew($valor)
    {
        $this->creando_new = $valor;
    }


    public function updateIngredienteReceta($payload)
    {
        $this->edit_ingrediente_cantidad = $payload["cantidad"];
        $this->edit_ingrediente_unidad = $payload["unidad"];
        $this->edit_ingrediente_id = $payload["ingrediente"];

        $this->validate([
            'edit_ingrediente_cantidad' => 'required|numeric',
            'edit_ingrediente_unidad' => 'required',
            'edit_ingrediente_id' => 'required'
        ]);

        $ingrediente = Ingrediente::find($this->edit_ingrediente_id);
        
        $this->receta->ingredientes()->detach($ingrediente);
        $this->receta->ingredientes()->attach($ingrediente,[
            'cantidad'=>$this->edit_ingrediente_cantidad,
            'unidad_medida'=>$this->edit_ingrediente_unidad,
        ]);
        
        $this->emitSelf('refreshTabla');
    }


    public function confirmacionBorrado($id)
    {
        $this->dispatchBrowserEvent('swalIngrediente:confirm',[
            'type' => 'warning',
            'title' => 'Confirmación de borrado',
            'text' => 'Está seguro de eliminar el registro?',
            'id' => $id,
        ]);
    }

    public function deleteIngredienteReceta($payload)
    {
        // dd($payload);
        $this->receta->ingredientes()->detach($payload["ingrediente"]);
        $this->emitSelf('refreshTabla');
    }
}
