<?php

namespace App\Traits;

use App\Models\Pricetype;
use App\Models\Rango;

trait CalcularPrecioVenta
{

    protected function getPrecioRealCompraAttribute()
    {
        $rango = Rango::where('desde', '<=', $this->pricebuy)
            ->where('hasta', '>=', $this->pricebuy)->first();

        if (!$rango) {
            return null;
        }

        $precioRealCompra = $this->pricebuy + ($this->pricebuy * ($rango->incremento / 100));
        return number_format($precioRealCompra, 3, '.', '');
    }

    public function assignPriceProduct($itempromo = null)
    {
        $listaprecios = Pricetype::activos()->with(['rangos' => function ($query) {
            $query->whereRangoBetween($this->pricebuy);
        }])->get();

        $precio_real_compra = $this->precio_real_compra;
        //Verifico si el producto tiene alguna promocion activa y disponible
        $promocion = $this->getPromocionDisponible();
        $descuento = $this->getPorcentajeDescuento($promocion);

        if (mi_empresa()->usarlista()) {
            foreach ($listaprecios as $item) {
                if (count($item->rangos) > 0) {
                    $porcetaje_ganancia = $item->rangos->first()->pivot->ganancia;
                    $precio_venta = $precio_real_compra + ($precio_real_compra * ($porcetaje_ganancia / 100));
                    if ($item->rounded > 0) {
                        $precio_venta = round_decimal($precio_venta, $item->rounded);
                    }

                    if ($promocion) {
                        //si encuentra promociones extrae %DSCT en caso de descuento
                        if ($descuento > 0) {
                            $precio_venta = $this->getPrecioDescuento($precio_venta, $descuento, $item);
                        }

                        $combo = $this->getAmountCombo($promocion, $item);

                        if ($combo) {
                            $precio_venta = $precio_venta + $combo->total;
                        }

                        if ($promocion->isRemate()) {
                            $precio_venta = $precio_real_compra;
                        }
                    }

                    if ($item->campo_table == 'precio_1') {
                        $this->precio_1 = $precio_venta;
                    }
                    if ($item->campo_table == 'precio_2') {
                        $this->precio_2 = $precio_venta;
                    }
                    if ($item->campo_table == 'precio_3') {
                        $this->precio_3 = $precio_venta;
                    }
                    if ($item->campo_table == 'precio_4') {
                        $this->precio_4 = $precio_venta;
                    }
                    if ($item->campo_table == 'precio_5') {
                        $this->precio_5 = $precio_venta;
                    }

                    $this->save();
                }
            }
        } else {
            $precio_venta = $this->pricesale;
            if ($itempromo) {
                $promodisponible = $itempromo->isDisponible() && $itempromo->isAvailable() ? true : false;
                if (!$promodisponible) {
                    $this->pricesale = $itempromo->pricebuy;
                    $this->save();
                }
            } else {

                if ($promocion) {
                    //si encuentra promociones extrae %DSCT en caso de descuento
                    if ($descuento > 0) {
                        $precio_venta = $this->getPrecioDescuento($precio_venta, $descuento, null);
                    }

                    $combo = $this->getAmountCombo($promocion, null);

                    if ($combo) {
                        $precio_venta = $precio_venta + $combo->total;
                    }

                    if ($promocion->isRemate()) {
                        $precio_venta = $this->pricebuy;
                    }
                }
                $this->pricesale = $precio_venta;
                $this->save();
            }
        }
    }


    public function obtenerPrecioVenta($pricetype = null)
    {

        // if (is_null($this->precio_real_compra)) {
        //     return null;
        // }

        // $precioManual = $this->calcularPrecioManualLista($pricetype);
        // if ($precioManual) {
        //     $precioVenta = $precioManual;
        // } else {
        //     $ganancia = $this->obtenerPorcentajeGanancia($pricetype->id);
        //     if (is_null($ganancia)) {
        //         return null;
        //     }
        //     $precioVenta = $this->precio_real_compra + ($this->precio_real_compra * ($ganancia / 100));
        // }

        // if ($pricetype->rounded > 0) {
        //     $precioVenta = round_decimal($precioVenta, $pricetype->rounded);
        // }

        // return number_format($precioVenta, $pricetype->decimals, '.', '');

        if (!empty($pricetype)) {
            if ($pricetype->campo_table == 'precio_1') {
                $precioVenta = $this->precio_1;
            }
            if ($pricetype->campo_table == 'precio_2') {
                $precioVenta = $this->precio_2;
            }
            if ($pricetype->campo_table == 'precio_3') {
                $precioVenta = $this->precio_3;
            }
            if ($pricetype->campo_table == 'precio_4') {
                $precioVenta = $this->precio_4;
            }
            if ($pricetype->campo_table == 'precio_5') {
                $precioVenta = $this->precio_5;
            }

            if ($pricetype->rounded > 0) {
                $precioVenta = round_decimal($precioVenta, $pricetype->rounded);
            }
            return number_format($precioVenta, $pricetype->decimals, '.', '');
        } else {
            $precioVenta = $this->pricesale;
            return number_format($precioVenta, 2, '.', '');
        }
        // if (in_array($pricetype->campo_table, lista_precios())) {
        //     if ($pricetype->campo_table == 'precio_1') {
        //         $precioVenta = $this->precio_1;
        //     }
        //     if ($pricetype->campo_table == 'precio_2') {
        //         $precioVenta = $this->precio_2;
        //     }
        //     if ($pricetype->campo_table == 'precio_3') {
        //         $precioVenta = $this->precio_3;
        //     }
        //     if ($pricetype->campo_table == 'precio_4') {
        //         $precioVenta = $this->precio_4;
        //     }
        //     if ($pricetype->campo_table == 'precio_5') {
        //         $precioVenta = $this->precio_5;
        //     }
        // }
    }

    public function obtenerPrecioByPricebuy($pricebuy, $promocion = null, $pricetype = null)
    {

        if (!empty($pricetype)) {
            $listaprecio = Pricetype::activos()->with(['rangos' => function ($query) use ($pricebuy) {
                $query->whereRangoBetween($pricebuy);
            }])->find($pricetype->id);

            if (count($listaprecio->rangos) > 0) {
                $precio_real_compra = number_format($pricebuy + ($pricebuy * ($listaprecio->rangos->first()->incremento / 100)), 3, '.', '');
                $porcetaje_ganancia = $listaprecio->rangos->first()->pivot->ganancia;
                $precio_venta = $precio_real_compra + ($precio_real_compra * ($porcetaje_ganancia / 100));

                if ($pricetype->rounded > 0) {
                    $precio_venta = round_decimal($precio_venta, $pricetype->rounded);
                }

                if ($promocion) {
                    if ($promocion->isDescuento()) {
                        $precio_venta = number_format($precio_venta - ($precio_venta * $promocion->descuento / 100), $pricetype->decimals, '.', '');
                    }
                    if ($promocion->isRemate()) {
                        $precio_venta = number_format($precio_real_compra, $pricetype->decimals, '.', '');
                    }

                    if ($promocion->isDescuento() || $promocion->isRemate()) {
                        if ($pricetype->rounded > 0) {
                            $precio_venta = round_decimal($precio_venta, $pricetype->rounded);
                        }
                    }
                }

                return number_format($precio_venta, $pricetype->decimals, '.', '');
            }
        } else {
            $precio_venta = $this->pricesale;
            if ($promocion) {
                if ($promocion->isDescuento()) {
                    $precio_venta = number_format($precio_venta - ($precio_venta * $promocion->descuento / 100), 2, '.', '');
                }
                if ($promocion->isRemate()) {
                    $precio_venta = number_format($promocion->pricebuy, 2, '.', '');
                }
            }
            return number_format($precio_venta, 2, '.', '');
        }

        // return number_format($priceVenta, $pricetype->decimals ?? 2, '.', '');
    }


    /**
     * @getPrecioDescuento Obtener precio de venta de un producto aplicando los descuentos.
     * @param float $precioVenta Precio de venta sin aplicar descuentos
     * @param float $descuento El código del tipo de moneda al que desea convertir
     * @param integer $modo Modo de aplicar el descuento, Opcion:0 => Aplica sobre la diferencia del precio de venta - preciocompra, Otra opcion => aplica descuento directamente al precio de venta
     * @param $pricetype Instancia del modelo Pricetype cuando se usa en modo lista de precios
     * @return string valor convertido a la moneda enviada
     */
    public function getPrecioDescuento($precioVenta, $descuento, $modo = 0, $pricetype = null)
    {
        // $precioCompra = $pricetype ? $this->precio_real_compra : $this->pricebuy;
        // if ($modo == 0) {
        //     $precioDescuento = $precioVenta - (($precioVenta - $precioCompra)  * ($descuento / 100));
        // } else {
        $precioDescuento = number_format($precioVenta - ($precioVenta * ($descuento / 100)), 3, '.', '');
        // }

        if ($pricetype) {
            if ($pricetype->rounded > 0) {
                $precioDescuento = round_decimal($precioDescuento, $pricetype->rounded);
            }
        }

        return number_format($precioDescuento, $pricetype->decimals ?? 3, '.', '');
    }

    // protected function obtenerPorcentajeGanancia($pricetype_id)
    // {

    //     $pricetype = Pricetype::with(['rangos' => function ($query) {
    //         $query->whereRangoBetween($this->pricebuy);
    //     }])->find($pricetype_id);

    //     return count($pricetype->rangos) > 0 ? $pricetype->rangos->first()->pivot->ganancia : null;
    // }


    public function getPromocionDisponible()
    {
        $exists =  $this->promocions()->disponibles()->exists();
        if (!$exists) {
            return null;
        }
        $promo = $this->promocions()->disponibles()->first();
        return $promo->isDisponible() && $promo->isAvailable() ? $promo : null;
    }


    public function getPorcentajeDescuento($promocion)
    {
        if ($promocion) {
            if ($promocion->isDescuento()) {
                return $promocion->descuento;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }


    public function getAmountCombo($promocion, $pricetype = null, $almacen_id = null)
    {
        if ($promocion) {
            if ($promocion->isCombo()) {

                $total = 0;
                $products = [];
                $type = null;
                foreach ($promocion->itempromos as $itempromo) {
                    if ($almacen_id) {
                        $stockCombo = formatDecimalOrInteger($itempromo->producto->almacens->find($almacen_id)->pivot->cantidad ?? 0);
                        $seriesdisponibles =  $itempromo->producto->series()->disponibles()
                            ->where('almacen_id', $almacen_id)->exists();
                    } else {
                        $stockCombo = null;
                        $seriesdisponibles = false;
                    }

                    $price = $pricetype ? $itempromo->producto->obtenerPrecioVenta($pricetype) : $itempromo->producto->pricesale;
                    $pricenormal = $price;

                    if ($itempromo->isDescuento()) {
                        $price = $itempromo->producto->getPrecioDescuento($price, $itempromo->descuento, 0, $pricetype);
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
                        'image' => $itempromo->producto->getImageURL(),
                        'price' => $price,
                        'pricebuy' => $pricetype ? $itempromo->producto->precio_real_compra : $itempromo->producto->pricebuy,
                        'pricenormal' => $pricenormal,
                        'stock' => $stockCombo,
                        'unit' => $itempromo->producto->unit->name,
                        'existseries' => $seriesdisponibles,
                        'type' => $type
                    ];
                }
                return response()->json(['total' => $total, 'products' => $products])->getData();
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
