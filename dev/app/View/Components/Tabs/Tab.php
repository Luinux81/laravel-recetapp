<?php

namespace App\View\Components\Tabs;

use Illuminate\View\Component;

class Tab extends Component
{
    public $nombre;
    public $titulo;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($nombre,$titulo)
    {
        $this->nombre = $nombre;
        $this->titulo = $titulo;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tabs.tab');
    }
}
