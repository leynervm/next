<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardRadio extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $text, $value, $id, $name, $price, $especificaciones, $disabled;

    public function __construct($text = '', $value = '', $id, $name, $price, $especificaciones = null, $disabled = false)
    {
        $this->text = $text;
        $this->value = $value;
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->especificaciones = $especificaciones;
        $this->disabled = $disabled;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card-radio');
    }
}
