<?php

namespace App\Http\Livewire\Modules\Marketplace\Carrito;

use CodersFree\Shoppingcart\Facades\Cart;
use Livewire\Component;

class CounterCarrito extends Component
{

    public $empresa;
    public $moneda;
    public $pricetype;

    public function mount()
    {
        $this->pricetype = getPricetypeAuth();
        $this->moneda = view()->shared('moneda');
        $this->empresa = view()->shared('empresa');
    }

    public function render()
    {
        $shoppings = getCartRelations('shopping');
        return view('livewire.modules.marketplace.carrito.counter-carrito', compact('shoppings'));
    }
}
