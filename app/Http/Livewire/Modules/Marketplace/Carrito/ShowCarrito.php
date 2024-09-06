<?php

namespace App\Http\Livewire\Modules\Marketplace\Carrito;

use App\Models\Moneda;
use App\Models\Producto;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class ShowCarrito extends Component
{

    public Moneda $moneda;

    public function render()
    {
        return view('livewire.modules.marketplace.carrito.show-carrito');
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

    public function delete()
    {
        Cart::instance('shopping')->destroy();
        $this->render();
        $this->dispatchBrowserEvent('updatecart', Cart::instance('shopping')->count());
    }

    public function move_to_wishlist($rowId)
    {
        $wishlist = Cart::instance('shopping')->get($rowId);
        Cart::instance('shopping')->remove($rowId);
        Cart::instance('wishlist')->add([
            'id' => $wishlist->id,
            'name' => $wishlist->name,
            'qty' => $wishlist->qty,
            'price' => number_format($wishlist->price, 2, '.', ''),
            'options' => [
                'moneda_id' => $wishlist->options->moneda_id,
                'currency' => $wishlist->options->currency,
                'simbolo' => $wishlist->options->simbolo,
                'modo_precios' => $wishlist->options->modo_precios,
                'carshoopitems' => $wishlist->options->carshoopitems,
                'promocion_id' => $wishlist->options->promocion_id ?  $wishlist->options->promocion_id : null,
                'igv' => 0,
                'subtotaligv' => 0
            ]
        ])->associate(Producto::class);
        $this->render();
        $this->dispatchBrowserEvent('updatecart', Cart::instance('shopping')->count());
        $this->dispatchBrowserEvent('updatewishlist', Cart::instance('wishlist')->count());
        if (auth()->check()) {
            Cart::instance('shopping')->store(auth()->id());
        }
    }

    public function updateitem($rowId, $cantidad)
    {
        // $item = Cart::instance('shopping')->get($rowId);
        if ($cantidad > 0) {
            Cart::instance('shopping')->update($rowId, $cantidad);
        }
        $this->render();
        $this->dispatchBrowserEvent('updatecart', Cart::instance('shopping')->count());
        if (auth()->check()) {
            Cart::instance('shopping')->store(auth()->id());
        }
    }
}
