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

    public function calcularPrecioManualLista($pricetype)
    {
        $precioManual = $this->pricetypes()->where('pricetype_id', $pricetype->id)->first();
        if (!$precioManual) {
            return null;
        }

        $precioVenta = $precioManual->pivot->price;
        return number_format($precioVenta, $pricetype->decimals ?? 3, '.', '');
    }

    public function calcularPrecioVentaLista($pricetype)
    {

        if (is_null($this->precio_real_compra)) {
            return null;
        }

        $precioManual = $this->calcularPrecioManualLista($pricetype);
        if ($precioManual) {
            $precioVenta = $precioManual;
        } else {
            $ganancia = $this->obtenerPorcentajeGanancia($pricetype->id);
            if (is_null($ganancia)) {
                return null;
            }
            $precioVenta = $this->precio_real_compra + ($this->precio_real_compra * ($ganancia / 100));
        }

        if ($pricetype->rounded > 0) {
            $precioVenta = round_decimal($precioVenta, $pricetype->rounded);
        }

        return number_format($precioVenta, $pricetype->decimals, '.', '');
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
        $precioCompra = $pricetype ? $this->precio_real_compra : $this->pricebuy;
        if ($modo == 0) {
            $precioDescuento = $precioVenta - (($precioVenta - $precioCompra)  * ($descuento / 100));
        } else {
            $precioDescuento = $precioVenta - ($precioVenta * ($descuento / 100));
        }

        if ($pricetype) {
            if ($pricetype->rounded > 0) {
                $precioDescuento = round_decimal($precioDescuento, $pricetype->rounded);
            }
        }

        return number_format($precioDescuento, $pricetype->decimals ?? 3, '.', '');
    }

    protected function obtenerPorcentajeGanancia($pricetype_id)
    {

        $pricetype = Pricetype::with(['rangos' => function ($query) {
            $query->where('desde', '<=', $this->pricebuy)->where('hasta', '>=', $this->pricebuy);
        }])->find($pricetype_id);

        return $pricetype->rangos()->exists() ? $pricetype->rangos->first()->pivot->ganancia : null;
    }

    public function obtenerPorcentajeGananciaLista($pricetype_id)
    {

        $pricetype = Pricetype::with(['rangos' => function ($query) {
            $query->where('desde', '<=', $this->pricebuy)->where('hasta', '>=', $this->pricebuy);
        }])->find($pricetype_id);

        return $pricetype->rangos()->exists() ? $pricetype->rangos->first()->pivot->ganancia : null;
    }

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


    public function getAmountCombo($promocion, $pricetype = null)
    {
        if ($promocion) {
            if ($promocion->isCombo()) {

                $total = 0;
                $products = [];
                foreach ($promocion->itempromos as $itempromo) {
                    $price = $pricetype ? $itempromo->producto->calcularPrecioVentaLista($pricetype) : $itempromo->producto->pricesale;
                    $pricenormal = $price;

                    if ($itempromo->isDescuento()) {
                        $price = $itempromo->producto->getPrecioDescuento($price, $itempromo->descuento, 0, $pricetype);
                    }
                    if ($itempromo->isGratuito()) {
                        $price = $pricetype ? $itempromo->producto->precio_real_compra : $itempromo->producto->pricebuy;
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
