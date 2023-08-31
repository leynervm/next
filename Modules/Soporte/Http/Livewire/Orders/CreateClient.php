<?php

namespace Modules\Soporte\Http\Livewire\Orders;

use Livewire\Component;

class CreateClient extends Component
{

    public $open = false;

    protected $listeners = ['openModal'];

    public function render()
    {
        return view('soporte::livewire.orders.create-client');
    }

    public function openModal()
    {
        $this->open = true;
    }
}
