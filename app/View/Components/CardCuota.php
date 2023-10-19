<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardCuota extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $clases, $titulo, $detallepago;

    public function __construct($titulo, $detallepago = null)
    {
        $this->titulo = $titulo;
        $this->detallepago = $detallepago;
        $this->clases = 'p-2 relative bg-body flex flex-col justify-between text-[10px] rounded shadow-md shadow-shadowform hover:shadow-lg hover:shadow-shadowform';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card-cuota');
    }
}
