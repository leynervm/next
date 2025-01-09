<?php

namespace App\Http\Livewire\Modules\Marketplace\Wishlist;

use App\Models\Moneda;
use App\Models\Producto;
use CodersFree\Shoppingcart\Facades\Cart;
use Livewire\Component;

class ShowWishlists extends Component
{

    public $moneda;
    public $pricetype;

    public function mount($pricetype = null)
    {
        $this->pricetype = $pricetype;
        $this->moneda = view()->shared('moneda');
    }

    public function render()
    {
        $wishlists = getCartRelations('wishlist');
        return view('livewire.modules.marketplace.wishlist.show-wishlists', compact('wishlists'));
    }

    public function deleteitem($rowId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        Cart::instance('wishlist')->remove($rowId);
        if (auth()->check()) {
            Cart::instance('wishlist')->store(auth()->id());
        }

        if (auth()->check()) {
            Cart::instance('wishlist')->store(auth()->id());
            Cart::instance('wishlist')->destroy();
            Cart::instance('wishlist')->restore(auth()->id());
        }
        $this->render();
        $this->dispatchBrowserEvent('updatewishlist', Cart::instance('wishlist')->count());
    }


    public function delete()
    {
        Cart::instance('wishlist')->destroy();
        $this->render();
        $this->dispatchBrowserEvent('updatewishlist', Cart::instance('wishlist')->count());
    }

    public function movetocarshoop($rowId)
    {
        $wishlist = Cart::instance('wishlist')->get($rowId);
        Cart::instance('wishlist')->remove($rowId);
        Cart::instance('shopping')->add([
            'id' => $wishlist->id,
            'name' => $wishlist->name,
            'qty' => $wishlist->qty,
            'price' => number_format($wishlist->price, 2, '.', ''),
            'options' => [
                'date' => now('America/Lima'),
                'moneda_id' => $wishlist->options->moneda_id,
                'currency' => $wishlist->options->currency,
                'simbolo' => $wishlist->options->simbolo,
                'modo_precios' => $wishlist->options->modo_precios,
                'carshoopitems' => $wishlist->options->carshoopitems,
                'promocion_id' => $wishlist->options->promocion_id,
                'igv' => 0,
                'subtotaligv' => 0
            ]
        ])->associate(Producto::class);
        // $this->render();
        $this->dispatchBrowserEvent('updatecart', Cart::instance('shopping')->count());
        $this->dispatchBrowserEvent('updatewishlist', Cart::instance('wishlist')->count());
        if (auth()->check()) {
            Cart::instance('wishlist')->store(auth()->id());
            Cart::instance('shopping')->store(auth()->id());
        }
    }
}
