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

    public $fontSize, $disabled;

    public function __construct($size = '[10px]', $disabled = false)
    {

        $this->fontSize = 'text-' . $size;
        // $this->classes = $fontSize . ' bg-fondobutton text-colorbutton block group relative font-semibold tracking-widest p-2.5 rounded-sm disabled:opacity-25 hover:bg-fondohoverbutton focus:bg-fondohoverbutton hover:ring-2 hover:ring-ringbutton focus:ring-2 focus:ring-ringbutton transition ease-in duration-150';
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
