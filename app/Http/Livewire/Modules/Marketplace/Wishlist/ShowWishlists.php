<?php

namespace App\Http\Livewire\Modules\Marketplace\Wishlist;

use App\Models\Moneda;
use App\Models\Producto;
use CodersFree\Shoppingcart\Facades\Cart;
use Livewire\Component;

class ShowWishlists extends Component
{

    public Moneda $moneda;


    public function render()
    {
        return view('livewire.modules.marketplace.wishlist.show-wishlists');
    }

    public function increment($rowId)
    {
        Cart::instance('wishlist')->update($rowId, Cart::get($rowId)->qty + 1);
        if (auth()->check()) {
            Cart::instance('shopping')->store(auth()->id());
        }
        $this->render();
        $this->dispatchBrowserEvent('updatewishlist', Cart::instance('wishlist')->count());
    }

    public function decrement($rowId)
    {
        $item = Cart::instance('wishlist')->get($rowId);
        if ($item->qty > 1) {
            Cart::instance('wishlist')->update($rowId, Cart::get($rowId)->qty - 1);
        } else {
            Cart::instance('wishlist')->remove($rowId);
        }
        $this->render();
        $this->dispatchBrowserEvent('updatewishlist', Cart::instance('wishlist')->count());
        if (auth()->check()) {
            Cart::instance('wishlist')->store(auth()->id());
        }
    }

    public function deleteitem($rowId)
    {
        Cart::instance('wishlist')->get($rowId);
        Cart::instance('wishlist')->remove($rowId);
        $this->render();
        $this->dispatchBrowserEvent('updatewishlist', Cart::instance('wishlist')->count());
        if (auth()->check()) {
            Cart::instance('wishlist')->store(auth()->id());
        }
    }

    public function delete()
    {
        Cart::instance('wishlist')->destroy();
        $this->render();
        $this->dispatchBrowserEvent('updatewishlist', Cart::instance('wishlist')->count());
    }

    public function move_to_carshoop($rowId)
    {
        $wishlist = Cart::instance('wishlist')->get($rowId);
        Cart::instance('wishlist')->remove($rowId);
        Cart::instance('shopping')->add([
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
            Cart::instance('wishlist')->store(auth()->id());
        }
    }
}
