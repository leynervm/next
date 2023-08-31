<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputRadio extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $text, $disabled, $cantidad, $textSize, $classes;

    public function __construct($text = '', $disabled = false, $cantidad = null, $textSize = 'sm')
    {
        $this->text = $text;
        $this->cantidad = $cantidad;
        $this->disabled = $disabled;
        $this->textSize = 'text-' . $textSize;
        $this->classes = ' flex justify-center items-center gap-1 text-center font-semibold ring-2 ring-transparent text-next-500 p-1.5 pl-3 pr-4 bg-fondominicard border border-next-500 rounded-sm cursor-pointer hover:bg-next-500 hover:ring-next-500 hover:border-next-500 hover:text-white peer-checked:bg-next-700 peer-focus:bg-next-700 peer-focus-within:bg-next-700 peer-checked:border-next-700 peer-checked:ring-next-300 peer-checked:text-white peer-focus:text-white peer-focus-within:text-white checked:bg-next-700 peer-disabled:opacity-25 transition ease-in-out duration-150';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input-radio');
    }
}
