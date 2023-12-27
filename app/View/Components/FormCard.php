<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormCard extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $titulo, $subtitulo, $widthBefore, $default;

    public function __construct($titulo, $subtitulo = null, $widthBefore = 'before:w-6', $default = 0)
    {
        $this->titulo = $titulo;
        $this->subtitulo = $subtitulo;
        $this->widthBefore = $widthBefore;
        $this->default = $default;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-card');
    }
}
