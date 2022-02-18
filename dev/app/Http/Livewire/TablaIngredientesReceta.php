<?php

namespace App\Http\Livewire;

use App\Models\Receta;
use Livewire\Component;

class TablaIngredientesReceta extends Component
{
    public $receta;

    public function mount(Receta $receta)
    {
        $this->receta = $receta;
    }

    public function render()
    {
        return view('livewire.tabla-ingredientes-receta');
    }
}
