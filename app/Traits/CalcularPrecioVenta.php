<?php

namespace App\Traits;

use App\Enums\PromocionesEnum;
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
        // $usarLista = Empresa::query()->select('uselistprice')->first()->usarlista() ?? false;
        $usarLista = view()->shared('empresa')->usarlista() ?? false;
        // $descuento = getDscto($promocion);

        if ($usarLista) {
            $rango = Rango::query()->with(['pricetypes' => function ($query) {
                $query->select('pricetypes.id', 'rounded', 'decimals', 'campo_table')
                    ->addSelect('pricetype_rango.ganancia')->orderBy('id', 'asc');
            }])->whereRangoBetween($this->pricebuy)->first();

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
}
