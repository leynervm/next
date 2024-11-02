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
        // $precio_real_compra = $this->precio_real_compra;
        //Verifico si cuenta con promocion activa y disponible 
        $promocion = $this->getPromocion();
        $usarLista = Empresa::query()->select('uselistprice')->first()->usarlista() ?? false;
        // $descuento = getDscto($promocion);

        if ($usarLista) {
            $rango = Rango::query()->with(['pricetypes' => function ($query) {
                $query->select('pricetypes.id', 'rounded', 'decimals', 'campo_table')
                    ->addSelect('pricetype_rango.ganancia');
            }])->whereRangoBetween($this->pricebuy)->first();

            foreach ($rango->pricetypes as $lista) {
                $precio_venta = getPriceDinamic(
                    $this->pricebuy,
                    $rango->ganancia,
                    $rango->incremento,
                    $lista->rounded,
                    $lista->decimals,
                    $promocion
                );

                if ($promocion && $promocion->isCombo()) {
                    $combo = $this->getAmountCombo($promocion, $lista);
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
                    $combo = $this->getAmountCombo($promocion);
                    $precio_venta = $precio_venta + $combo->total;
                }

                $this->pricesale = $precio_venta;
                $this->save();
            }
        }
    }


    public function obtenerPrecioVenta($pricetype = null)
    {
        if (!empty($pricetype)) {
            // if ($pricetype->rounded > 0) {
            //     $precioVenta = round_decimal($this->{$pricetype->campo_table}, $pricetype->rounded);
            // } else {
            //     $precioVenta = $this->{$pricetype->campo_table};
            // }
            return number_format($this->{$pricetype->campo_table}, $pricetype->decimals, '.', '');
        } else {
            return number_format($this->pricesale, 2, '.', '');
        }
    }

    public function obtenerPrecioByPricebuy($pricebuy, $promocion = null, $pricetype = null, $validatePrm = true)
    {

        if (!empty($promocion) && $validatePrm) {
            $promocion = verifyPromocion($promocion);
        }

        if (!empty($pricetype)) {
            $rango = Rango::query()->with(['pricetypes' => function ($query) use ($pricetype) {
                $query->select('pricetypes.id', 'rounded', 'decimals', 'campo_table')
                    ->addSelect('pricetype_rango.ganancia')->where('pricetypes.id', $pricetype->id);
            }])->whereRangoBetween($pricebuy)->first();
            $lista = $rango->pricetypes->first();
            $precio_venta = getPriceDinamic(
                $this->pricebuy,
                $lista->ganancia,
                $rango->incremento,
                $lista->rounded,
                $lista->decimals,
                $promocion
            );

            // if ($this->{$pricetype->campo_table} == '47.00') {
            //     dd($pricebuy, $this->pricebuy);
            // }

            if ($promocion && $promocion->isCombo()) {
                $combo = $this->getAmountCombo($promocion, $lista);
                $precio_venta = $precio_venta + $combo->total;
            }

            return $precio_venta;

            // $listaprecio = Pricetype::activos()->with(['rangos' => function ($query) use ($pricebuy) {
            //     $query->whereRangoBetween($pricebuy);
            // }])->find($pricetype->id);

            // if (count($listaprecio->rangos) > 0) {
            //     return getPriceDinamic(
            //         $pricebuy,
            //         $listaprecio->rangos->first()->pivot->ganancia,
            //         $listaprecio->rangos->first()->incremento,
            //         $pricetype->rounded,
            //         $pricetype->decimals,
            //         $promocion
            //     );
            // }
        } else {
            if ($promocion && $promocion->isRemate()) {
                //En caso de remate debe tomar precio de compra del producto
                return number_format($this->pricebuy, 2, '.', '');
            }

            return getPriceDinamic($pricebuy, 0, 0, 0, 2, $promocion);
        }
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
                    $stockCombo = decimalOrInteger($itempromo->producto->almacens->find($almacen_id)->pivot->cantidad ?? 0);
                } else {
                    $stockCombo = null;
                }

                $price = $pricetype ? $itempromo->producto->obtenerPrecioVenta($pricetype) : $itempromo->producto->pricesale;
                $pricenormal = $price;

                if ($itempromo->isDescuento()) {
                    $price = getPriceDscto($price, $itempromo->descuento, $pricetype);
                    $type = decimalOrInteger($itempromo->descuento) . '% DSCT';
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
