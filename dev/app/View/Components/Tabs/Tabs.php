<?php

namespace App\View\Components\Tabs;

use Illuminate\View\Component;

class Tabs extends Component
{
    public $activa;
    public $id;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($dataId, $activa)
    {
        $this->id = $dataId;
        $this->activa = $activa;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tabs.tabs');
    }
}
