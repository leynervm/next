<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardProducto extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public  $name, $image, $category, $almacen, $discount, $increment, $promocion;

    public function __construct($name = null, $image = null, $category = null, $almacen = null, $increment = null, $promocion = null)
    {
        $this->name = $name;
        $this->image = $image;
        $this->category = $category;
        $this->almacen = $almacen;
        $this->increment = $increment;
        $this->promocion = $promocion;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card-producto');
    }
}
