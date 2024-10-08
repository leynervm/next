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
        // $this->verifyProductoCarshoop();
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
        $this->verifyProductoCarshoop();
        $this->render();
        $this->opencounter = true;
    }

    public function verifyProductoCarshoop()
    {
        if (Cart::instance('shopping')->count() > 0) {
            $count = 0;
            foreach (Cart::instance('shopping')->content() as $item) {
                if (is_null($item->model)) {
                    Cart::instance('shopping')->get($item->rowId);
                    Cart::instance('shopping')->remove($item->rowId);
                    $count++;
                }
            }
            if (auth()->check()) {
                Cart::instance('shopping')->store(auth()->id());
            }
            if ($count > 0) {
                $mensaje = response()->json([
                    'title' => "ALGUNOS PRODUCTOS FUERON REMOVIDOS DEL CARRITO.",
                    'text' => 'Carrito de compras actualizado, algunos productos han dejado de estar disponibles en tienda web.',
                    'type' => 'warning'
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        }
    }
}
