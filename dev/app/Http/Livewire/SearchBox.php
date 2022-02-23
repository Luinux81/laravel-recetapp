<?php

namespace App\Http\Livewire;

use App\Models\Ingrediente;
use Livewire\Component;

class SearchBox extends Component
{
    public $search;
    public $busquedaActiva;
    
    public $selectedIndex;
    public $selectedModel;
    public $selectedModelId;

    public $nombre;

    public $claseModelo;
    public $modelos;

    protected $listeners = [
        'clearSearchBox' => 'clearSearchBox'
    ];

    public function mount($clase,$nombre)
    {
        $this->resetEstado();
        $this->nombre = $nombre;

        switch ($clase) {
            case 'ingrediente':
                $this->claseModelo = Ingrediente::class;
                break;
            
            default:
                $this->claseModelo = Ingrediente::class;
                break;
        }
    }


    public function updatedSearch()
    {
        if($this->busquedaActiva){
            $this->modelos = $this->claseModelo::where('nombre','like','%'. $this->search . '%')
            ->orderBy('nombre','ASC')
            ->get()
            ->toArray();
        }
        else{
            $this->modelos = [];
        }
    }


    public function incrementaSelectedIndex()
    {
        if($this->selectedIndex === count($this->modelos) - 1){
            $this->selectedIndex = 0;
            return;
        }        
        $this->selectedIndex++;
    }

    public function decrementaSelectedIndex()
    {
        if($this->selectedIndex === 0){
            $this->selectedIndex = count($this->modelos) - 1;
            return;
        }        
        $this->selectedIndex--;
    }

    public function activaBusqueda()
    {
        $this->busquedaActiva = true;
    }

    public function seleccionaIndex($index = -1)
    {
        if($index < 0){
            $this->selectedModel = $this->modelos[$this->selectedIndex];
            $this->selectedModelId = $this->modelos[$this->selectedIndex]['id'];
        }
        else{
            $this->selectedModel = $this->modelos[$index];
            $this->selectedModelId = $this->modelos[$index]['id'];
        }

        $this->busquedaActiva = false;
        $this->resetEstado();
    }

    public function resetEstado()
    {
        $this->busquedaActiva = true;
        $this->search = "";
        $this->modelos = [];
        $this->selectedIndex = 0;
        // $this->selectedModel = null;
        // $this->selectedModelId = null;
    }
    
    public function clearSearchBox()
    {
        $this->resetEstado();
        $this->selectedModel = null;
        $this->selectedModelId = null;
    }

    public function render()
    {
        return view('livewire.search-box');
    }
}
