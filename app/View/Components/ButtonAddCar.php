<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ButtonAddCar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $classes, $disabled;

    public function __construct($size = '[10px]', $disabled = false)
    {
        $fontSize = 'text-' . $size;
        $this->classes = $fontSize . ' block border border-amber-500 group relative font-semibold tracking-widest bg-amber-500 text-amber-500 p-2.5 rounded-sm disabled:opacity-25 hover:bg-amber-700 focus:bg-amber-700 hover:border-amber-700 focus:border-amber-700 hover:ring-2 hover:ring-amber-300 focus:ring-2 focus:ring-amber-300 transition ease-in duration-150';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button-add-car');
    }
}
