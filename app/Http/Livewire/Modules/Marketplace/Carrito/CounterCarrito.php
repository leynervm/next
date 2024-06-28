<?php

namespace App\Http\Livewire\Modules\Marketplace\Carrito;

use App\Models\Empresa;
use App\Models\Moneda;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class CounterCarrito extends Component
{

    public $opencounter = false;
    public Empresa $empresa;
    public Moneda $moneda;
    public $pricetype;

    public function mount($pricetype = null)
    {
        $this->pricetype = $pricetype;
    }

    public function render()
    {
        return view('livewire.modules.marketplace.carrito.counter-carrito');
    }

    public function increment($rowId)
    {
        Cart::instance('shopping')->update($rowId, Cart::get($rowId)->qty + 1);
        if (auth()->check()) {
            Cart::instance('shopping')->store(auth()->id());
        }
        $this->render();
        $this->dispatchBrowserEvent('updatecart', Cart::instance('shopping')->count());
    }

    public function decrement($rowId)
    {
        $item = Cart::instance('shopping')->get($rowId);
        if ($item->qty > 1) {
            Cart::instance('shopping')->update($rowId, Cart::get($rowId)->qty - 1);
        } else {
            Cart::instance('shopping')->remove($rowId);
        }
        $this->render();
        $this->dispatchBrowserEvent('updatecart', Cart::instance('shopping')->count());
        if (auth()->check()) {
            Cart::instance('shopping')->store(auth()->id());
        }
    }

    public function deleteitem($rowId)
    {
        Cart::instance('shopping')->get($rowId);
        Cart::instance('shopping')->remove($rowId);
        $this->render();
        $this->dispatchBrowserEvent('updatecart', Cart::instance('shopping')->count());
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
