<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardcontactoRadio extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $text, $document, $value, $id, $name, $phones, $disabled;

    public function __construct($text = '', $document = '', $value = '', $id, $name, $phones = null, $disabled = false)
    {
        $this->text = $text;
        $this->document = $document;
        $this->value = $value;
        $this->id = $id;
        $this->name = $name;
        $this->phones = $phones;
        $this->disabled = $disabled;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.cardcontacto-radio');
    }
}
