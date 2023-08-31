<?php

namespace App\Helpers;

use App\Models\Pricetype;

class GetPrice
{

    public static function getPriceventa($preciocompra, $incremento, $ganancia, $descuento, $decimal = 2, $default = false)
    {
        $priceCompra = number_format($preciocompra + ($preciocompra * $incremento) / 100, $decimal, '.', '');

        $price = number_format($priceCompra + ($priceCompra * $ganancia) / 100, $decimal, '.', '');
        $amountDescuento = number_format(($price * $descuento) / 100,  $decimal, '.', '');

        if ($default) {
            $priceRounded = self::round_up($price - $amountDescuento, 0);
            return number_format($priceRounded, $decimal, '.', '');
        } else {
            return number_format($price - $amountDescuento, $decimal, '.', '');
        }
    }


    public static function getPriceProducto($producto, $priceSelected = null,)
    {

        try {

            $precioCompra = $producto->pricebuy;
            $precioVenta = $producto->pricebuy;
            $precioDescuento = $producto->pricebuy;
            $descuento = number_format(0, 2);
            $amountDescuento = number_format(0, 2);
            $decimal = 2;
            $rangoexists = false;

            if (count($producto->ofertasdisponibles)) {
                $descuento = $producto->ofertasdisponibles()->first()->descuento;
            }

            if ($priceSelected) {

                $pricetype = Pricetype::findOrFail($priceSelected);

                if ($pricetype) {
                    if (count($pricetype->rangos)) {
                        foreach ($pricetype->rangos as $rango) {
                            if ($producto->pricebuy >= $rango->desde && $producto->pricebuy <= $rango->hasta) {
                                // if ($pricetype->id == $priceSelected) {

                                $precioCompra = number_format($precioCompra + ($precioCompra * $rango->incremento) / 100, $pricetype->decimalrounded, '.', '');
                                $precioVenta = number_format($precioCompra + ($precioCompra * $rango->pivot->ganancia) / 100, $pricetype->decimalrounded, '.', '');
                                $amountDescuento = number_format(($precioVenta * $descuento) / 100,  $pricetype->decimalrounded, '.', '');
                                $rangoexists = true;
                                $decimal = $pricetype->decimalrounded;

                                if ($pricetype->default) {
                                    $precioVentaRounded = self::round_up($precioVenta, 0);
                                    $precioVenta = number_format($precioVentaRounded, $pricetype->decimalrounded, '.', '');
                                    $precioDescuento = number_format($precioVenta - $amountDescuento, $pricetype->decimalrounded, '.', '');
                                } else {
                                    $precioVenta = number_format($precioVenta, $pricetype->decimalrounded, '.', '');
                                    $precioDescuento = number_format($precioVenta - $amountDescuento, $pricetype->decimalrounded, '.', '');
                                }
                                // }
                            }
                        }
                    }
                }
            } else {
                $amountDescuento = number_format(($producto->pricesale * $descuento) / 100,  2, '.', '');
                $precioVenta = number_format($producto->pricesale, 2, '.', '');
                $precioDescuento = number_format($producto->pricesale - $amountDescuento, 2, '.', '');
            }

            $json = [
                'success' => true,
                'pricebuy' => $precioCompra,
                'pricesale' => $precioVenta,
                'pricewithdescount' => $precioDescuento,
                'amountDescuento' => $amountDescuento,
                'decimal' => $decimal,
                'rango' => $rangoexists
            ];

            return response()->json($json, 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
            throw $e;
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
            throw $e;
        }
    }

    public static function round_up($value, $places)
    {
        $mult = pow(10, abs($places));
        return $places < 0 ? ceil($value / $mult) * $mult : ceil($value * $mult) / $mult;
    }
}
