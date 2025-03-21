<?php

namespace App\Traits;

use App\Models\Almacen;
use App\Models\Kardex;

trait KardexTrait
{

    // PARA SER EJECUTADO DESDE UNA INSTANCIA Kardex
    public static function updateOrNewKardex($almacen_id, $producto_id, $stock, $cantidad)
    {
        $kardex = Self::where('almacen_id', $almacen_id)
            ->where('newstock', $stock)->where('producto_id', $producto_id)->first();

        if (empty($kardex)) {
            $data = [
                'date' => now('America/Lima'),
                'cantidad' => $cantidad,
                'oldstock' => 0,
                'newstock' => $cantidad,
                // 'reference' => null,
                'simbolo' => Almacen::INGRESO_ALMACEN,
                'detalle' => Kardex::ACTUALIZACION_MANUAL,
                'producto_id' => $producto_id,
                'almacen_id' => $almacen_id,
                'sucursal_id' => auth()->user()->sucursal_id,
                'user_id' => auth()->user()->id,
                'kardeable_id' => 0,
                'kardeable_type' => Self::class,
            ];
            $kardex = Self::create($data);
            $kardex->kardeable_id = $kardex->id;
        } else {
            $kardex->cantidad = $cantidad;
            $kardex->oldstock = $kardex->newstock;
            $kardex->newstock = $cantidad;
            $kardex->save();
        }

        return $kardex;
    }

    /**
     * @incrementOrDecrementStock INCREMENTAR o DESCONTAR stock de almacén relacionado, cuando el TVITEM ha alterado stock.
     * @param Producto $producto Instancia del modelo Producto.
     * @param Tvitem $tvitem Instancia del modelo Tvitem incluyendo la relacion Modelo Producto.
     */
    public function incrementOrDecrementStock($producto, $tvitem)
    {
        if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
            if ($this->simbolo == Almacen::SALIDA_ALMACEN) {
                // SI TVITEM REGISTRADO HA DISMINUIDO STOCK, REPONER CANT.
                Self::incrementarStock($producto);
            } else if ($this->simbolo == Almacen::INGRESO_ALMACEN) {
                // SI TVITEM REGISTRADO HA INGRESADO STOCK, DISMINUIR CANT.
                Self::descontarStock($producto);
            }
        }
    }

    /**
     * @incrementarStock INCREMENTAR stock de almacén relacionado.
     * @param Producto $producto Instancia del modelo Producto.
     */
    private function incrementarStock($producto)
    {
        $stock = $producto->almacens()->find($this->almacen_id)->pivot->cantidad;
        $producto->almacens()->updateExistingPivot($this->almacen_id, [
            'cantidad' => $stock + $this->cantidad,
        ]);
    }

    /**
     * @descontarStock DESCONTAR stock de almacén relacionado.
     * @param Producto $producto Instancia del modelo Producto.
     */
    private function descontarStock($producto)
    {
        $stock = $producto->almacens()->find($this->almacen_id)->pivot->cantidad;
        $producto->almacens()->updateExistingPivot($this->almacen_id, [
            'cantidad' => $stock - $this->cantidad,
        ]);
    }
}
