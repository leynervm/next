<?php

namespace App\Http\Livewire\Modules\Marketplace\Carrito;

use App\Models\Empresa;
use App\Models\Moneda;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CounterCarrito extends Component
{

    public Empresa $empresa;
    public Moneda $moneda;
    public $pricetype;

    public function mount()
    {
        $this->pricetype = getPricetypeAuth();
    }

    public function render()
    {
        $this->verifyProductoCarshoop();
        return view('livewire.modules.marketplace.carrito.counter-carrito');
    }

    public function increment($rowId)
    {
        $cart = Cart::instance('shopping')->get($rowId);
        if ($cart->model) {
            $producto = $cart->model;
            $producto->load(['promocions' => function ($query) {
                $query->with(['itempromos.producto' => function ($query) {
                    $query->with('unit');
                }])->availables()->disponibles()->take(1);
            }])->loadCount(['almacens as stock' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
            }]);

            $promocion = verifyPromocion($producto->promocions->first());
            $combo = $producto->getAmountCombo($promocion, $this->pricetype ?? null);
            $carshoopitems = !is_null($combo) ? $combo->products : [];
            $pricesale = $producto->obtenerPrecioVenta($this->pricetype ?? null);

            if ($promocion) {
                if ($promocion->limit > 0 && (($promocion->outs + $cart->qty + 1) > $promocion->limit)) {
                    $mensaje = response()->json([
                        'title' => 'CANTIDAD SUPERA LAS UNIDADES DISPONIBLES EN PROMOCIÓN',
                        'text' => null,
                        'type' => 'warning'
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }
            }

            if ($producto->stock <= 0 || $producto->stock < ($cart->qty + 1)) {
                $mensaje = response()->json([
                    'title' => 'LÍMITE DE STOCK EN PRODUCTO ALCANZADO !',
                    'text' => null,
                    'icon' => 'warning'
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        }

        $options = $cart->options->toArray();
        $options['carshoopitems'] = $carshoopitems;
        $options['promocion_id'] = $promocion ? $promocion->id : null;

        Cart::instance('shopping')->update($rowId, [
            'qty' => Cart::get($rowId)->qty + 1,
            'price' => number_format($pricesale, 2, '.', ''),
            'options' => $options
        ]);
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
