<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NavLink extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $titulo;
    public $classes;

    public function __construct($titulo = null, $active = false)
    {
        // $this->titulo = $titulo;
        $this->classes = $active ?? false
            ? 'inline-flex group p-1.5 justify-center md:justify-start items-center text-colorlinknav cursor-pointer font-semibold rounded-lg shadow text-hovercolorlinknav bg-hoverlinknav transition-all ease-in-out duration-150'
            : 'inline-flex group p-1.5 justify-center md:justify-start items-center text-colorlinknav cursor-pointer font-semibold rounded-lg shadow hover:text-hovercolorlinknav hover:bg-hoverlinknav focus:bg-hoverlinknav focus:text-hovercolorlinknav transition-all ease-in-out duration-150';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.nav-link');
    }
}
