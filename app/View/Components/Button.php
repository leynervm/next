<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
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
        $this->classes = $fontSize . ' block border border-next-500 group relative font-semibold tracking-widest bg-next-500 text-white p-2.5 rounded-sm disabled:opacity-25 hover:bg-next-700 focus:bg-next-700 hover:border-next-700 focus:border-next-700 hover:ring-2 hover:ring-next-300 focus:ring-2 focus:ring-next-300 transition ease-in duration-150';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button');
    }
}
