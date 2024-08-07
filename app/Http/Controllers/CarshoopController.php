<?php

namespace App\Http\Controllers;

use App\Models\Carshoop;
use App\Models\Moneda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarshoopController extends Controller
{

    public function delete(Carshoop $carshoop, $isDeleteAll = false)
    {
        DB::beginTransaction();
        try {
            if (count($carshoop->carshoopitems) > 0) {
                foreach ($carshoop->carshoopitems as $carshoopitem) {
                    $stockCombo = $carshoopitem->producto->almacens->find($carshoop->almacen_id)->pivot->cantidad;
                    $carshoopitem->producto->almacens()->updateExistingPivot($carshoop->almacen_id, [
                        'cantidad' => $stockCombo + $carshoop->cantidad,
                    ]);
                    $carshoopitem->delete();
                }
            }

            if ($carshoop->promocion) {
                $carshoop->promocion->outs = $carshoop->promocion->outs - $carshoop->cantidad;
                $carshoop->promocion->save();
                if ($carshoop->promocion->limit ==  $carshoop->cantidad + $carshoop->promocion->outs) {
                    $carshoop->producto->assignPriceProduct();
                }
            }

            if (count($carshoop->carshoopseries) > 0) {
                $carshoop->carshoopseries()->each(function ($carshoopserie) use ($carshoop) {
                    if ($carshoop->isDiscountStock() || $carshoop->isReservedStock()) {
                        $carshoopserie->serie->dateout = null;
                        $carshoopserie->serie->status = 0;
                        $carshoopserie->serie->save();
                    }
                    $carshoopserie->delete();
                });
            }

            if ($carshoop->isDiscountStock() || $carshoop->isReservedStock()) {
                $stock = $carshoop->producto->almacens->find($carshoop->almacen_id)->pivot->cantidad;
                $carshoop->producto->almacens()->updateExistingPivot($carshoop->almacen_id, [
                    'cantidad' => $stock + $carshoop->cantidad,
                ]);
            }

            if ($carshoop->kardex) {
                $carshoop->kardex->delete();
            }

            $carshoop->delete();
            DB::commit();
            if (!$isDeleteAll) {
                // $this->setTotal();
                return response()->json([
                    'success' => true,
                    'mensaje' => 'ELIMINADO CORRECTAMENTE',
                    'form_id' => NULL
                ])->getData();
                // $this->dispatchBrowserEvent('show-resumen-venta', $datos);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteall()
    {
        try {
            DB::beginTransaction();
            Carshoop::with(['carshoopseries'])->ventas()->where('user_id', auth()->user()->id)
                ->where('sucursal_id', auth()->user()->sucursal_id)->each(function ($carshoop) {
                    self::delete($carshoop, true);
                });
            DB::commit();
            // $this->resetValidation();
            return response()->json([
                'success' => true,
                'mensaje' => 'CARRITO ELIMINADO CORRECTAMENTE',
                'form_id' => null
            ])->getData();
            // $this->dispatchBrowserEvent('show-resumen-venta', $datos);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function updatemoneda(Request $request)
    {
        $moneda_id = $request->get('moneda_id');

        try {
            DB::beginTransaction();
            $carshoops = Carshoop::with(['producto', 'moneda'])
                ->ventas()->where('user_id', auth()->user()->id)
                ->where('sucursal_id', auth()->user()->sucursal_id)
                ->where('moneda_id', '<>', $moneda_id)
                ->orderBy('id', 'asc')->get();

            if (count($carshoops) > 0) {
                $empresa = mi_empresa();
                foreach ($carshoops as $item) {
                    $price = number_format($item->price + $item->igv, 3, '.', '');
                    $pricesale = $empresa->isAfectacionIGV() ? getPriceIGV($price, $empresa->igv)->price : $price;
                    $igvsale = $empresa->isAfectacionIGV() ? getPriceIGV($price, $empresa->igv)->igv : 0;

                    if ($item->moneda->code == 'USD') {
                        $pricesale = convertMoneda($pricesale, 'PEN', mi_empresa()->tipocambio ?? 1, 3);
                        $igvsale = convertMoneda($igvsale, 'PEN', mi_empresa()->tipocambio ?? 1, 3);
                    } else {
                        $pricesale = convertMoneda($pricesale, 'USD', mi_empresa()->tipocambio ?? 1, 3);
                        $igvsale = convertMoneda($igvsale, 'USD', mi_empresa()->tipocambio ?? 1, 3);
                    }
                    $item->update([
                        'price' => $pricesale,
                        'igv' => $igvsale,
                        'subtotal' => number_format($pricesale * $item->cantidad, 3, '.', ''),
                        'total' => number_format(convertMoneda($price * $item->cantidad, $item->moneda->code == 'USD' ? 'PEN' : 'USD', mi_empresa()->tipocambio ?? 1, 3), 3, '.', ''),
                        'moneda_id' => $moneda_id,
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'mensaje' => 'CARRITO ACTUALIZADO CORRECTAMENTE',
                'totales'   => self::total(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function total()
    {
        $sumatorias = Carshoop::select(
            DB::raw("COALESCE(SUM(total),0) as total"),
            DB::raw("COALESCE(SUM(CASE WHEN igv > 0 AND gratuito = '0' THEN igv * cantidad ELSE 0 END),0) as igv"),
            DB::raw("COALESCE(SUM(CASE WHEN igv > 0 AND gratuito = '1' THEN igv * cantidad ELSE 0 END), 0) as igvgratuito"),
            DB::raw("COALESCE(SUM(CASE WHEN igv > 0 AND gratuito = '0' THEN price * cantidad ELSE 0 END), 0) as gravado"),
            DB::raw("COALESCE(SUM(CASE WHEN igv = 0 AND gratuito = '0' THEN price * cantidad ELSE 0 END), 0) as exonerado"),
            DB::raw("COALESCE(SUM(CASE WHEN gratuito = '1' THEN price * cantidad ELSE 0 END), 0) as gratuito")
        )->ventas()->where('user_id', auth()->user()->id)
            ->where('sucursal_id', auth()->user()->sucursal_id)
            ->get();

        return  $sumatorias[0];
    }
}
