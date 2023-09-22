<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputFile extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $titulo, $size, $classes;

    public function __construct($titulo = null, $size = "[10px]")
    {
        $this->titulo = $titulo;
        $size = 'text-' . $size;
        $this->classes = $size . ' inline-flex gap-2 p-2 justify-center items-center border border-next-500 relative font-semibold tracking-widest bg-next-500 text-white rounded-sm disabled:opacity-25 hover:bg-next-700 focus:bg-next-700 hover:border-next-700 focus:border-next-700 hover:ring-2 hover:ring-next-300 focus:ring-2 focus:ring-next-300 transition ease-in duration-150';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input-file');
    }
}
