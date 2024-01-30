<?php

namespace App\Traits;

use App\Models\Kardex;
use Modules\Almacen\Entities\Compraitem;

trait KardexTrait
{

    public function saveKardex($sucursal_id, $producto_id, $almacen_id, $oldstock, $newstock, $cantidad, $simbolo, $detalle, $reference)
    {
        Kardex::create([
            'date' => now('America/Lima'),
            'cantidad' => $cantidad,
            'oldstock' => $oldstock,
            'newstock' => $newstock,
            'simbolo' => $simbolo,
            'detalle' => $detalle,
            'reference' => $reference,
            'producto_id' => $producto_id,
            'almacen_id' => $almacen_id,
            'sucursal_id' => $sucursal_id,
            'user_id' => auth()->user()->id,
            'kardeable_id' => $this->id,
            'kardeable_type' => get_class($this),
        ]);
    }

    public function updateKardex($compraitem_id, $cantidad)
    {
        $compraitem = Compraitem::with('kardex')->find($compraitem_id);
        if ($compraitem->kardex) {
            $compraitem->kardex->cantidad = $compraitem->kardex->cantidad + $cantidad;
            $compraitem->kardex->newstock = $compraitem->kardex->newstock + $cantidad;
            $compraitem->kardex->save();
        }
    }

    public function deleteKardex($compraitem_id)
    {
        $compraitem = Compraitem::with('kardex')->find($compraitem_id);
        if ($compraitem->kardex) {
            $compraitem->kardex->delete();
        }
    }
}