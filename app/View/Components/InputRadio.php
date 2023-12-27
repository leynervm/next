<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputRadio extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $text, $disabled, $cantidad, $classes;

    public function __construct($text = '', $disabled = false, $cantidad = null)
    {
        $this->text = $text;
        $this->cantidad = $cantidad;
        $this->disabled = $disabled;
        // $this->classes = '';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input-radio');
    }
}
