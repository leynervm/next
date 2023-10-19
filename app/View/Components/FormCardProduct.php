<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormCardProduct extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $almacens;
    public $series;

    public function __construct($almacens, $series = [])
    {
        $this->almacens = $almacens;
        $this->series = $series;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-card-product');
    }
}
