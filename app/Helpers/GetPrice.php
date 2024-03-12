<?php

namespace App\Helpers;

use App\Models\Itempromo;
use App\Models\Pricetype;
use App\Models\Producto;

class GetPrice
{

    // public static function getPriceventa($preciocompra, $incremento, $ganancia, $descuento, $decimal = 2, $default = false)
    // {
    //     $priceCompra = number_format($preciocompra + ($preciocompra * $incremento) / 100, $decimal, '.', '');

    //     $price = number_format($priceCompra + ($priceCompra * $ganancia) / 100, $decimal, '.', '');
    //     $amountDescuento = number_format(($price * $descuento) / 100, $decimal, '.', '');

    //     if ($default) {
    //         $priceRounded = self::round_up($price - $amountDescuento, 0);
    //         return number_format($priceRounded, $decimal, '.', '');
    //     } else {
    //         return number_format($price - $amountDescuento, $decimal, '.', '');
    //     }
    // }


    public static function getPriceProducto($producto, $priceSelected = null, $preciotipocambio = null, $dscto = null)
    {

        try {
            $datosRango = [];
            $decimal = 2;
            $roundedTo = 0;
            $rangoexists = false;
            $precioManual = null;
            $oldPrecioSalida = null;
            $precioCompra = number_format($producto->pricebuy, $decimal, '.', '');
            $descuento = number_format(0, 2);
            $amountDescuento = number_format(0, 2);
            $tipocambio = $preciotipocambio == null ? 0 : (float) $preciotipocambio;

            $precioVenta = number_format($producto->pricebuy, $decimal, '.', '');
            $precioDescuento = number_format($precioVenta, $decimal, '.', '');

            $amountDescuentoDolar = number_format(0, $decimal, '.', '');
            $precioVentaDolar = number_format(0, $decimal, '.', '');
            $precioDescuentoDolar = number_format($precioVentaDolar, $decimal, '.', '');

            // if (count($producto->ofertasdisponibles)) {
            //     $descuento = $producto->ofertasdisponibles()->first()->descuento;
            // }
            $descuentos = $producto->promocions()->descuentos()->disponibles();
            if ($descuentos->count() > 0) {
                $descuento = $descuentos->first()->descuento;
            }

            if ($priceSelected) {

                $pricetype = Pricetype::findOrFail($priceSelected);

                if ($pricetype) {

                    $decimal = $pricetype->decimals;
                    $roundedTo = $pricetype->rounded;
                    $priceManual = $producto->pricetypes()
                        ->where('pricetype_id', $priceSelected)->first();

                    if ($priceManual) {
                        $precioManual = number_format($priceManual->pivot->price, $pricetype->decimals, '.', '');

                        $amountDescuento = number_format(($priceManual->pivot->price * $descuento) / 100, $pricetype->decimals, '.', '');
                        $precioVenta = number_format($priceManual->pivot->price, $pricetype->decimals, '.', '');
                        $precioDescuento = number_format($priceManual->pivot->price - $amountDescuento, $pricetype->decimals, '.', '');

                        if ($tipocambio > 0) {
                            $amountDescuentoDolar = number_format((($precioVenta / $tipocambio) * $descuento) / 100, $pricetype->decimals, '.', '');
                            $precioVentaDolar = number_format($precioVenta / $tipocambio, $pricetype->decimals, '.', '');
                            $precioDescuentoDolar = number_format($precioVentaDolar - $amountDescuentoDolar, $pricetype->decimals, '.', '');
                            $oldPrecioSalida = number_format($priceManual->pivot->price, $pricetype->decimals, '.', '');
                        }
                    }
                    // else {

                    $rangoPrice = $pricetype->rangos()
                        ->where('desde', '<=', $producto->pricebuy)
                        ->where('hasta', '>=', $producto->pricebuy)
                        ->get();

                    if (count($rangoPrice) > 0) {
                        // if (count($pricetype->rangos)) {
                        foreach ($rangoPrice as $rango) {
                            if ($producto->pricebuy >= $rango->desde && $producto->pricebuy <= $rango->hasta) {

                                $ganancia = number_format($rango->pivot->ganancia, 2, '.', '');
                                if ($dscto !== null) {
                                    $disminucion = number_format(($ganancia * $dscto) / 100, 2, '.', '');
                                    $ganancia = number_format($ganancia - $disminucion, 2, '.', '');
                                }

                                $precioCompra = number_format($precioCompra + (($precioCompra * $rango->incremento) / 100), $pricetype->decimals, '.', '');
                                $priceSelect = $priceManual->pivot->price ?? $precioCompra + ($precioCompra * $ganancia) / 100;

                                $precioVenta = number_format($priceSelect, $pricetype->decimals, '.', '');
                                $amountDescuento = number_format(($precioVenta * $descuento) / 100, $pricetype->decimals, '.', '');
                                $precioDescuento = number_format($precioVenta - $amountDescuento, $pricetype->decimals, '.', '');

                                $rangoexists = true;
                                $datosRango = $rango;
                                $oldPrecioSalida = number_format($precioCompra + ($precioCompra * $ganancia) / 100, $pricetype->decimals, '.', '');

                                if ($pricetype->rounded > 0) {
                                    $precioVenta = self::round_decimal($precioVenta, $roundedTo);
                                    $oldPrecioSalida = self::round_decimal($oldPrecioSalida, $roundedTo);
                                    $precioDescuento = number_format(self::round_decimal($precioDescuento, $roundedTo), $pricetype->decimals, '.', '');
                                }

                                if ($tipocambio > 0) {
                                    $precioVenta = number_format($precioVenta, $pricetype->decimals, '.', '');
                                    $precioDescuento = number_format($precioVenta - $amountDescuento, $pricetype->decimals, '.', '');
                                    $amountDescuentoDolar = number_format((($precioVenta / $tipocambio) * $descuento) / 100, $pricetype->decimals, '.', '');
                                    $precioVentaDolar = number_format($precioVenta / $tipocambio, $pricetype->decimals, '.', '');
                                    $precioDescuentoDolar = number_format($precioVentaDolar - $amountDescuentoDolar, $pricetype->decimals, '.', '');
                                    $oldPrecioSalida = number_format($oldPrecioSalida, $pricetype->decimals, '.', '');
                                }
                            } else {

                                // $priceSelect = $priceManual->pivot->price ?? 0;

                                $precioVenta = number_format(0, $pricetype->decimals, '.', '');
                                $precioDescuento = number_format(0, $pricetype->decimals, '.', '');

                                if ($tipocambio > 0) {
                                    $amountDescuentoDolar = number_format(0, $pricetype->decimals, '.', '');
                                    $precioVentaDolar = number_format(0, $pricetype->decimals, '.', '');
                                    $precioDescuentoDolar = number_format(0, $pricetype->decimals, '.', '');
                                    $oldPrecioSalida = number_format(0, $pricetype->decimals, '.', '');
                                }
                            }
                        }
                    } else {

                        $amountDescuento = number_format(($precioVenta * $descuento) / 100, $pricetype->decimals, '.', '');
                        $precioVenta = number_format($precioVenta, $pricetype->decimals, '.', '');
                        $precioDescuento = number_format($precioVenta - $amountDescuento, $pricetype->decimals, '.', '');

                        if ($tipocambio > 0) {
                            $amountDescuentoDolar = number_format((($precioVenta / $tipocambio) * $descuento) / 100, $pricetype->decimals, '.', '');
                            $precioVentaDolar = number_format($precioVenta / $tipocambio, $pricetype->decimals, '.', '');
                            $precioDescuentoDolar = number_format($precioVentaDolar - $amountDescuentoDolar, $pricetype->decimals, '.', '');
                            $oldPrecioSalida = number_format(0, $pricetype->decimals, '.', '');
                        } else {
                        }

                        // }
                    }
                }
            } else {

                $precioVenta = number_format($producto->pricesale, $decimal, '.', '');
                $precioDescuento = number_format($producto->pricesale, $decimal, '.', '');

                if ($producto->pricesale > 0) {
                    $amountDescuento = number_format(($producto->pricesale * $descuento) / 100, $decimal, '.', '');
                    $precioVenta = number_format($producto->pricesale, $decimal, '.', '');
                    $precioDescuento = number_format($producto->pricesale - $amountDescuento, $decimal, '.', '');
                    
                    if ($tipocambio > 0) {
                        $amountDescuentoDolar = number_format((($producto->pricesale / $tipocambio) * $descuento) / 100, $decimal, '.', '');
                        $precioVentaDolar = number_format($precioVenta / $tipocambio, $decimal, '.', '');
                        $precioDescuentoDolar = number_format($precioVentaDolar - $amountDescuentoDolar, $decimal, '.', '');
                    }
                }
            }

            $json = [
                'success' => true,
                'pricebuy' => $precioCompra,
                'pricesale' => $precioVenta,
                'oldPrice' => $oldPrecioSalida,
                'pricewithdescount' => $precioDescuento,
                'pricemanual' => $precioManual,
                'amountDescuento' => $amountDescuento,
                'decimal' => $decimal,
                'descuento' => $descuento,
                'priceDolar' => $tipocambio > 0 ? $precioVentaDolar : '0.00',
                'amountDescuentoDolar' => $tipocambio == 1 ? '0.00' : $amountDescuentoDolar,
                'pricewithdescountDolar' => $tipocambio == 1 ? '0.00' : $precioDescuentoDolar,
                'existsrango' => $rangoexists,
                'roundedInteger' => $roundedTo,
                'dsct_combo' => $ganancia ?? $dscto,
                'rango' => $datosRango,
                'tipocambio' => $tipocambio > 0 ?  $tipocambio : '0.00',
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

    public static function round_decimal($value, $roundedTo = 1)
    {

        $result = $value - floor($value);
        $rounded = $roundedTo == 1 ? 0.5 : 1;

        if ($result > 0) {
            return ($result <= 0.5) ? (float) (floor($value) + $rounded) : round($value);
        } else {
            return (float) $value;
        }
        //floor: extrae solo el numero entero sin redondedear
        //valor ingresado resto al entero del mismo valor
        // return ($value - floor($value) < 0.5) ? (float)(floor($value) + 0.5) : round($value);
    }

    public static function round_up($value, $places)
    {
        $mult = pow(10, abs($places));
        return $places < 0 ? ceil($value / $mult) * $mult : ceil($value * $mult) / $mult;
    }

    public static function getPriceCombos($producto, $priceSelected = null)
    {
        $combos = $producto->promocions()->combos()->disponibles()->get();
        return $combos;
    }
}
