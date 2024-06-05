<?php

namespace App\Http\Livewire\Modules\Marketplace\Carrito;

use App\Models\Empresa;
use App\Models\Moneda;
use App\Models\Producto;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class AddCarrito extends Component
{

    public Empresa $empresa;
    public Moneda $moneda;
    public Producto $producto;
    public $pricetype;

    public function mount($pricetype = null)
    {
        $this->pricetype = $pricetype;
    }

    public function render()
    {
        return view('livewire.modules.marketplace.carrito.add-carrito');
    }

    public function add_to_cart(Producto $producto, $cantidad)
    {

        $promocion = $producto->getPromocionDisponible();
        $descuento = $producto->getPorcentajeDescuento($promocion);
        $combo = $producto->getAmountCombo($promocion, $this->pricetype);
        $priceCombo = $combo ? $combo->total : 0;
        $carshoopitems = (!is_null($combo) && count($combo->products) > 0) ? $combo->products : [];

        if ($this->empresa->usarLista()) {
            if ($this->pricetype) {
                $price = $producto->calcularPrecioVentaLista($this->pricetype);
                $price = !is_null($promocion) && $promocion->isRemate()
                    ? $producto->precio_real_compra
                    : $price;
                $pricesale = $descuento > 0 ? $producto->getPrecioDescuento($price, $descuento, 0, $this->pricetype) : $price;
            }
        } else {
            $price = $producto->pricesale;
            $price = !is_null($promocion) && $promocion->isRemate() ? $producto->pricebuy : $price;
            $pricesale = $descuento > 0 ? $producto->getPrecioDescuento($price, $descuento, 0) : $price;
        }

        if (isset($price)) {
            $price = $price + $priceCombo;
            $pricesale = $pricesale + $priceCombo;
            // dd($price, $pricesale);
            Cart::instance('shopping')->add([
                'id' => $producto->id,
                'name' => $producto->name,
                'qty' => $cantidad,
                'price' => number_format($pricesale, 2, '.', ''),
                'options' => [
                    // 'pricebuy' => number_format($this->empresa->usarLista() ? $producto->precio_real_compra : $producto->pricebuy, 2, '.', ''),
                    'moneda_id' => $this->moneda->id,
                    'currency' => $this->moneda->currency,
                    'simbolo' => $this->moneda->simbolo,
                    'modo_precios' => $this->empresa->usarLista() ? $this->pricetype->name : 'DEFAUL PRICESALE',
                    'carshoopitems' => $carshoopitems,
                    'promocion_id' => $promocion ?  $promocion->id : null,
                    'igv' => 0,
                    'subtotaligv' => 0
                ]
            ])->associate(Producto::class);

            $this->dispatchBrowserEvent('updatecart', Cart::instance('shopping')->count());

            if (auth()->check()) {
                Cart::instance('shopping')->store(auth()->id());
            }
            $this->dispatchBrowserEvent('toast', toastJSON('Agregado al carrito'));
        } else {
            $mensaje = response()->json([
                'title' => 'CONFIGURAR LISTA DE PRECIOS PARA TIENDA VIRTUAL !',
                'text' => 'No se pudo obtener el precio de venta, configurar correctamente el modo de precios de los productos.',
                'type' => 'warning'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }
    }
}
