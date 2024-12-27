<?php

namespace App\Http\Livewire\Modules\Marketplace\Carrito;

use App\Models\Promocion;
use CodersFree\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowCarrito extends Component
{

    public $moneda;
    public $pricetype;

    public function mount($pricetype = null)
    {
        $this->pricetype = $pricetype;
        $this->moneda = view()->shared('moneda');
        // $this->empresa = view()->shared('empresa');
    }

    public function render()
    {
        $shoppings = getCartRelations('shopping');
        return view('livewire.modules.marketplace.carrito.show-carrito', compact('shoppings'));
    }

    public function delete()
    {
        Cart::instance('shopping')->destroy();
        $this->render();
        $this->dispatchBrowserEvent('updatecart', Cart::instance('shopping')->count());
    }

    public function updateitem($rowId, $cantidad)
    {
        // $item = Cart::instance('shopping')->get($rowId);
        if ($cantidad > 0) {
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
                $combo = getAmountCombo($promocion, $this->pricetype);
                $carshoopitems = (!is_null($combo) && count($combo->products) > 0) ? $combo->products : [];
                $pricesale = $producto->getPrecioVentaDefault($this->pricetype ?? null);

                if ($promocion) {
                    if ($promocion->limit > 0 && (($promocion->outs + $cantidad) > $promocion->limit)) {
                        $mensaje = response()->json([
                            'title' => 'CANTIDAD SUPERA LAS UNIDADES DISPONIBLES EN PROMOCIÓN',
                            'text' => null,
                            'type' => 'warning'
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }
                }

                if ($producto->stock <= 0 || $producto->stock < $cantidad) {
                    $mensaje = response()->json([
                        'title' => 'STOCK DEL PRODUCTO EN ALMACÉN AGOTADO ! !',
                        'text' => null,
                        'icon' => 'warning'
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                $options = $cart->options->toArray();
                $options['carshoopitems'] = $carshoopitems;
                $options['promocion_id'] = $promocion ? $promocion->id : null;

                Cart::instance('shopping')->update($rowId, [
                    'qty' => $cantidad,
                    'price' => number_format($pricesale, 2, '.', ''),
                    'options' => $options
                ]);

                $this->render();
                $this->dispatchBrowserEvent('updatecart', Cart::instance('shopping')->count());
                if (auth()->check()) {
                    Cart::instance('shopping')->store(auth()->id());
                }
            }
        }
    }
}
