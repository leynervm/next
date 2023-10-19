<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MinicardPhone extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $phone;

    public function __construct($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.minicard-phone');
    }
}
