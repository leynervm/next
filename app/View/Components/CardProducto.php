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

    public  $name, $image, $category, $almacen, $discount, $increment;

    public function __construct($name, $image = null, $category = null, $almacen = null, $discount = null, $increment = null)
    {
        $this->name = $name;
        $this->image = $image;
        $this->category = $category;
        $this->almacen = $almacen;
        $this->discount = $discount;
        $this->increment = $increment;
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
