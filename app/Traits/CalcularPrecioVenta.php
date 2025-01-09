<?php

namespace App\Traits;

use App\Enums\PromocionesEnum;
use App\Models\Pricetype;
use App\Models\Promocion;
use App\Models\Rango;
use CodersFree\Shoppingcart\Facades\Cart;

trait CalcularPrecioVenta
{

    protected function getPrecioRealCompraAttribute()
    {
        $rango = Rango::query()->select('incremento')->where('desde', '<=', $this->pricebuy)
            ->where('hasta', '>=', $this->pricebuy)->first();

        if (!$rango) {
            return null;
        }

        $precioRealCompra = $this->pricebuy + ($this->pricebuy * ($rango->incremento / 100));
        return number_format($precioRealCompra, 3, '.', '');
    }

    /**
     * @assignPrice Asinar precio de venta del producto.
     * @param instanceOf Promocion $oldpromocion: solamnte se recibe cuando la promoción 
     * se ha completedo correctamnete, retonamos el valor de venta anterior de $oldpromocion, 
     * Si no recibo $oldpromocion, mi consulta $this->getPromocion() no lo retornará 
     * como disponible y no podre actualizar e antiguo valor de venta.
     * @return null no retorna valores simplemente actualiza el valor valor de venta
     */
    public function assignPrice($oldpromocion = null)
    {
        // $precio_real_compra = $this->precio_real_compra;
        //Verifico si cuenta con promocion activa y disponible 
        $promocion = $this->getPromocion();
        // $usarLista = Empresa::query()->select('uselistprice')->first()->usarlista() ?? false;
        $usarLista = view()->shared('empresa')->usarlista() ?? false;
        // $descuento = getDscto($promocion);

        if ($usarLista) {
            $rango = Rango::query()->with(['pricetypes' => function ($query) {
                $query->select('pricetypes.id', 'rounded', 'decimals', 'campo_table')
                    ->addSelect('pricetype_rango.ganancia')->orderBy('id', 'asc');
            }])->whereRangoBetween($this->pricebuy)->first();

            if (empty($rango)) {
                $pricetypes = Pricetype::activos()->get();
                foreach ($pricetypes as $lista) {
                    $precio_venta = getPriceDinamic(
                        $this->pricebuy,
                        $lista->ganancia,
                        0,
                        $lista->rounded,
                        $lista->decimals
                    );

                    // if ($promocion && $promocion->isCombo()) {
                    //     $combo = getAmountCombo($promocion, $lista);
                    //     $precio_venta = $precio_venta + $combo->total;
                    // }

                    $this->{$lista->campo_table} = $precio_venta;
                    $this->save();
                }
                return;
            }

            foreach ($rango->pricetypes as $lista) {
                $precio_venta = getPriceDinamic(
                    $this->pricebuy,
                    $lista->ganancia,
                    $rango->incremento,
                    $lista->rounded,
                    $lista->decimals,
                    $promocion
                );

                if ($promocion && $promocion->isCombo()) {
                    $combo = getAmountCombo($promocion, $lista);
                    $precio_venta = $precio_venta + $combo->total;
                }

                $this->{$lista->campo_table} = $precio_venta;
                $this->save();
                // dd($producto, $item->incremento, $lista->ganancia, $precio_venta);
            }
        } else {
            if ($oldpromocion && empty(verifyPromocion($oldpromocion))) {
                //Si promocion deja de estar disponible(EMPTY) retornamos el precio normal de venta
                $this->pricesale = $oldpromocion->pricebuy;
                $this->save();
            } else {
                $pricesale = ($promocion && $promocion->isRemate()) ? $this->pricebuy : $this->pricesale;
                $precio_venta = getPriceDinamic($pricesale, 0, 0, 0, 2, $promocion);

                if ($promocion && $promocion->isCombo()) {
                    $combo = getAmountCombo($promocion);
                    $precio_venta = $precio_venta + $combo->total;
                }

                $this->pricesale = $precio_venta;
                $this->save();
            }
        }
    }


    public function getPrecioVentaDefault($pricetype = null)
    {
        if (!empty($pricetype)) {
            return number_format($this->{$pricetype->campo_table}, $pricetype->decimals, '.', '');
        } else {
            return number_format($this->pricesale, 2, '.', '');
        }
    }

    //siempre traer las relacione cargadas con with en el producto
    private function getPromocion()
    {
        return ($this->promocions && count($this->promocions) > 0) ? verifyPromocion($this->promocions->first()) : null;
    }

    public function getPrecioVenta($pricetype = null)
    {
        $descuento = $this->promocions->where('type', PromocionesEnum::DESCUENTO->value)->first()->descuento ?? 0;
        $liquidacion = $this->promocions->where('type', PromocionesEnum::LIQUIDACION->value)->count() > 0 ? true : false;
// dd($descuento, $liquidacion);
        if ($liquidacion) {
            $precio_venta = getPriceDinamic($this->pricebuy, 0, !empty($pricetype) ? $pricetype->incremento : 2, 0, !empty($pricetype) ? $pricetype->decimals : 2);
            return $precio_venta;
        }

        if (!empty($pricetype)) {
            $precio_venta = number_format($this->{$pricetype->campo_table}, $pricetype->decimals, '.', '');
        } else {
            $precio_venta = number_format($this->pricesale, 2, '.', '');
        }

        if ($descuento > 0) {
            $precio_venta = getPriceDscto($precio_venta, $descuento, $pricetype ?? null);
            return $precio_venta;
        }

        return $precio_venta;
    }

    public function addfavorito()
    {
        $this->load(['promocions' => function ($query) {
            $query->where('type', '<>', PromocionesEnum::COMBO->value)
                ->availables()->disponibles();
        }]);

        $this->descuento = $this->promocions->where('type', PromocionesEnum::DESCUENTO->value)->first()->descuento ?? 0;
        $this->liquidacion = $this->promocions->where('type', PromocionesEnum::LIQUIDACION->value)->count() > 0 ? true : false;
        $promocion = null;
        $pricetype = null;
        $empresa = view()->shared('empresa');
        $moneda = view()->shared('moneda');

        if ($this->descuento > 0 || $this->liquidacion) {
            $promocion = verifyPromocion($this->promocions->first());
        }

        if ($empresa->usarLista()) {
            $pricetype = getPricetypeAuth();
        }

        $promocion_id = !empty($promocion) ? $promocion->id : null;
        $pricesale = $this->getPrecioVenta($pricetype);
        $igvsale = 0;

        if ($pricesale <= 0) {
            return response()->json([
                'success' => false,
                'mensaje' => "ERROR AL OBTENER PRECIO DE VENTA"
            ]);
            return false;
        }

        $wishlist = Cart::instance('wishlist')->search(function ($item) use ($promocion_id) {
            return $item->id == $this->id && $item->options->promocion_id == $promocion_id;
        });

        $favorito = $wishlist->first();
        if (empty($favorito)) {
            $cart = Cart::instance('wishlist')->add([
                'id' => $this->id,
                'name' => $this->name,
                'qty' => 1,
                'price' => number_format($pricesale, 2, '.', ''),
                'options' => [
                    'moneda_id' => $moneda->id,
                    'currency' => $moneda->currency,
                    'simbolo' => $moneda->simbolo,
                    'modo_precios' => $pricetype->name ?? 'DEFAUL PRICESALE',
                    'carshoopitems' => [],
                    'promocion_id' => $promocion_id,
                    'igv' => 0,
                    'subtotaligv' => 0
                ]
            ])->associate($this::class);

            if (auth()->check()) {
                Cart::instance('wishlist')->store(auth()->id());
            }

            return response()->json([
                'success' => true,
                'mensaje' => "AÑADIDO A FAVORITOS",
                'counter' => Cart::instance('wishlist')->count(),
                'favorito' => true
            ]);
        } else {
            Cart::instance('wishlist')->remove($favorito->rowId);
            if (auth()->check()) {
                Cart::instance('wishlist')->store(auth()->id());
            }

            return response()->json([
                'success' => true,
                'mensaje' => "QUITADO DE FAVORITOS",
                'counter' => Cart::instance('wishlist')->count(),
                'favorito' => false
            ]);
        }
    }
}
