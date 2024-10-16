<?php

namespace App\Traits;

use App\Models\Empresa;
use App\Models\Pricetype;
use App\Models\Rango;

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
        $listaprecios = Pricetype::activos()->with(['rangos' => function ($query) {
            $query->whereRangoBetween($this->pricebuy);
        }])->get();

        // $precio_real_compra = $this->precio_real_compra;
        //Verifico si cuenta con promocion activa y disponible 
        $promocion = $this->getPromocion();
        $descuento = getDscto($promocion);
        $usarLista = Empresa::query()->select('uselistprice')->first()->usarlista() ?? false;

        if ($usarLista) {
            foreach ($listaprecios as $item) {
                if (count($item->rangos) > 0) {
                    $precio_venta = getPriceDinamic(
                        $this->pricebuy,
                        $item->rangos->first()->pivot->ganancia,
                        $item->rangos->first()->incremento,
                        $item->rounded,
                        $item->decimals,
                        $promocion
                    );


                    // $porcetaje_ganancia = $item->rangos->first()->pivot->ganancia;
                    // $precio_venta = $precio_real_compra + ($precio_real_compra * ($porcetaje_ganancia / 100));
                    // if ($item->rounded > 0) {
                    //     $precio_venta = round_decimal($precio_venta, $item->rounded);
                    // }

                    // if ($promocion) {
                    //     //si encuentra promociones extrae %DSCT en caso de descuento
                    //     if ($descuento > 0) {
                    //         $precio_venta = getPriceDscto($precio_venta, $descuento, $item);
                    //     }

                    //     $combo = $this->getAmountCombo($promocion, $item);

                    //     if ($combo) {
                    //         $precio_venta = $precio_venta + $combo->total;
                    //     }

                    //     if ($promocion->isRemate()) {
                    //         $precio_venta = $precio_real_compra;
                    //     }
                    // }

                    if ($promocion && $promocion->isCombo()) {
                        $combo = $this->getAmountCombo($promocion, $item);
                        $precio_venta = $precio_venta + $combo->total;
                    }
                    $this->{$item->campo_table} = $precio_venta;
                    $this->save();
                }
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
                    $combo = $this->getAmountCombo($promocion);
                    $precio_venta = $precio_venta + $combo->total;
                }

                $this->pricesale = $precio_venta;
                $this->save();
            }


            // if ($oldpromocion) {
            //     //Si promocion deja de estar disponible(EMPTY) retornamos el precio normal de venta
            //     $oldpromodisponible = verifyPromocion($oldpromocion);
            //     if (empty($oldpromodisponible)) {
            //         $this->pricesale = $oldpromocion->pricebuy;
            //         $this->save();
            //     }
            // } else {

            // $precio_venta = getPriceDinamic(
            //     $this->pricesale,
            //     0,
            //     0,
            //     0,
            //     2,
            //     $promocion
            // );

            // if ($promocion) {
            //     //si encuentra promociones extrae %DSCT en caso de descuento
            //     if ($descuento > 0) {
            //         $precio_venta = getPriceDscto($precio_venta, $descuento);
            //     }

            //     $combo = $this->getAmountCombo($promocion, null);

            //     if ($combo) {
            //         $precio_venta = $precio_venta + $combo->total;
            //     }

            //     if ($promocion->isRemate()) {
            //         $precio_venta = $this->pricebuy;
            //     }
            // }
            // $this->pricesale = $precio_venta;
            // $this->save();
            // }
        }
    }


    public function obtenerPrecioVenta($pricetype = null)
    {
        if (!empty($pricetype)) {
            if ($pricetype->rounded > 0) {
                $precioVenta = round_decimal($this->{$pricetype->campo_table}, $pricetype->rounded);
            }
            return number_format($precioVenta, $pricetype->decimals, '.', '');
        } else {
            $precioVenta = $this->pricesale;
            return number_format($precioVenta, 2, '.', '');
        }
    }

    public function obtenerPrecioByPricebuy($pricebuy, $promocion = null, $pricetype = null, $validatePrm = true)
    {

        if (!empty($promocion) && $validatePrm) {
            $promocion = verifyPromocion($promocion);
        }

        if (!empty($pricetype)) {
            $listaprecio = Pricetype::activos()->with(['rangos' => function ($query) use ($pricebuy) {
                $query->whereRangoBetween($pricebuy);
            }])->find($pricetype->id);

            if (count($listaprecio->rangos) > 0) {
                return getPriceDinamic(
                    $pricebuy,
                    $listaprecio->rangos->first()->pivot->ganancia,
                    $listaprecio->rangos->first()->incremento,
                    $pricetype->rounded,
                    $pricetype->decimals,
                    $promocion
                );
            }
        } else {
            if ($promocion && $promocion->isRemate()) {
                //En caso de remate debe tomar precio de compra del producto
                return number_format($this->pricebuy, 2, '.', '');
            }

            return getPriceDinamic($pricebuy, 0, 0, 0, 2, $promocion);

            // if ($promocion) {
            //     if ($promocion->isDescuento()) {
            //         $precio_venta = number_format($precio_venta - ($precio_venta * $promocion->descuento / 100), 2, '.', '');
            //     }
            //     if ($promocion->isRemate()) {
            //         $precio_venta = number_format($promocion->pricebuy, 2, '.', '');
            //     }
            // }
            // return number_format($precio_venta, 2, '.', '');
        }

        // return number_format($priceVenta, $pricetype->decimals ?? 2, '.', '');
    }


    //siempre traer las relacione cargadas con with en el producto
    private function getPromocion()
    {
        return count($this->promocions) > 0 ? verifyPromocion($this->promocions->first()) : null;
    }

    public function getAmountCombo($promocion, $pricetype = null, $almacen_id = null)
    {
        if (!empty($promocion) && $promocion->isCombo()) {
            $total = 0;
            $products = [];
            $type = null;
            foreach ($promocion->itempromos as $itempromo) {
                if ($almacen_id) {
                    $stockCombo = formatDecimalOrInteger($itempromo->producto->almacens->find($almacen_id)->pivot->cantidad ?? 0);
                } else {
                    $stockCombo = null;
                }

                $price = $pricetype ? $itempromo->producto->obtenerPrecioVenta($pricetype) : $itempromo->producto->pricesale;
                $pricenormal = $price;

                if ($itempromo->isDescuento()) {
                    $price = getPriceDscto($price, $itempromo->descuento, $pricetype);
                    $type = formatDecimalOrInteger($itempromo->descuento) . '% DSCT';
                }
                if ($itempromo->isGratuito()) {
                    $price = $pricetype ? $itempromo->producto->precio_real_compra : $itempromo->producto->pricebuy;
                    $type = 'GRATIS';
                }

                if ($pricetype) {
                    if ($pricetype->rounded > 0) {
                        $price = round_decimal($price, $pricetype->rounded);
                    }
                }

                $total = $total + number_format($price, $pricetype ? $pricetype->decimals : 3, '.', '');
                $products[] = [
                    'producto_id' => $itempromo->producto_id,
                    'name' => $itempromo->producto->name,
                    'image' => $itempromo->producto->image ? pathURLProductImage($itempromo->producto->image) : null,
                    'price' => $price,
                    'pricebuy' => $pricetype ? $itempromo->producto->precio_real_compra : $itempromo->producto->pricebuy,
                    'pricenormal' => $pricenormal,
                    'stock' => $stockCombo,
                    'unit' => $itempromo->producto->unit->name,
                    'type' => $type
                ];
            }
            return response()->json(['total' => $total, 'products' => $products])->getData();
        } else {
            return null;
        }
    }
}
