<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ButtonNext extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */


    public $classes;
    public $classesIcon;
    public $classTitulo;
    public $titulo;

    public function __construct($size = 'sm', $classTitulo = 'text-xs font-bold', $titulo = null)
    {

        $this->classTitulo = $classTitulo;
        $this->titulo = $titulo;

        if ($size == 'xs') {
            $classesSize = 'w-20 h-20';
            $classesIcon = 'w-4 h-4';
        } elseif ($size == 'md') {
            $classesSize = 'w-28 h-28';
            $classesIcon = 'w-8 h-8';
        } elseif ($size == 'lg') {
            $classesSize = 'w-32 h-32';
            $classesIcon = 'w-10 h-10';
        } elseif ($size == 'xl') {
            $classesSize = 'px-5 py-12';
            $classesIcon = 'w-12 h-12';
        } else {
            $classesSize = 'w-24 h-24';
            $classesIcon = 'w-6 h-6';
        }

        $this->classes = $classesSize . ' relative bg-fondominicard text-colorlinknav shadow shadow-shadowminicard p-3 rounded-xl block hover:shadow-md hover:shadow-shadowminicard transition-shadow ease-out duration-150';
        $this->classesIcon = $classesIcon;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button-next');
    }
}
