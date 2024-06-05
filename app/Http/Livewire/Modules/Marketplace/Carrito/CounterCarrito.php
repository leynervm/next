<?php

namespace App\Http\Livewire\Modules\Marketplace\Carrito;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class CounterCarrito extends Component
{

    public $opencounter = false;

    public function render()
    {
        return view('livewire.modules.marketplace.carrito.counter-carrito');
    }

    public function deleteitem($rowId)
    {
        Cart::instance('shopping')->get($rowId);
        Cart::instance('shopping')->remove($rowId);
        $this->render();
        if (auth()->check()) {
            Cart::instance('shopping')->store(auth()->id());
        }
    }

    public function open()
    {
        $this->render();
        $this->opencounter = true;
    }
}
