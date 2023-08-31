<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardNext extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $titulo;
    public $alignFooter;
    public $classes;

    public function __construct($titulo, $alignFooter = 'justify-end')
    {
        $this->titulo = $titulo;
        $this->alignFooter = $alignFooter;
        $this->classes = 'flex flex-col justify-between relative bg-fondominicard text-colorlinknav shadow shadow-shadowminicard rounded-lg hover:shadow-md hover:shadow-shadowminicard transition-shadow ease-out duration-150';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card-next');
    }
}
