<?php

namespace App\Traits;

use App\Models\Almacen;
use App\Models\Kardex;
use App\Models\Producto;
use App\Models\Tvitem;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Almacen\Entities\Compraitem;
use Nwidart\Modules\Facades\Module;

trait KardexTrait
{

    public function saveKardex($producto_id, $almacen_id, $oldstock, $newstock, $cantidad, $simbolo, $detalle, $reference, $promocion_id = null)
    {

        if (Module::isEnabled('Almacen')) {
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
                'sucursal_id' => auth()->user()->sucursal_id ?? null,
                'user_id' => auth()->user()->id,
                'promocion_id' => $promocion_id,
                'kardeable_id' => $this->id,
                'kardeable_type' => get_class($this),
            ]);
        }
    }

    public function updateKardex($compraitem_id, $cantidad)
    {
        if (Module::isEnabled('Almacen')) {
            $compraitem = Compraitem::with('kardex')->find($compraitem_id);
            if ($compraitem->kardex) {
                $compraitem->kardex->cantidad = $compraitem->kardex->cantidad + $cantidad;
                $compraitem->kardex->newstock = $compraitem->kardex->newstock + $cantidad;
                $compraitem->kardex->save();
            }
        }
    }

    public function deleteKardex($compraitem_id)
    {
        if (Module::isEnabled('Almacen')) {
            $compraitem = Compraitem::with('kardex')->find($compraitem_id);
            if ($compraitem->kardex) {
                $compraitem->kardex->delete();
            }
        }
    }

    public function saveKardexOutCarshoop($stock, $promocion_id = null)
    {
        if (Module::isEnabled('Almacen')) {
            $this->registrarKardex(
                $stock,
                $stock - $this->cantidad,
                Almacen::SALIDA_ALMACEN,
                Kardex::ADD_VENTAS,
                null,
                $promocion_id
            );
        }
    }

    public function updateStockAlmacen($stock, $cantidad)
    {
        if ($this->isDiscountStock()) {
            $this->producto->almacens()->updateExistingPivot($this->almacen_id, [
                'cantidad' => $stock - $cantidad,
            ]);
        }
        if ($this->isIncrementStock()) {
            $this->producto->almacens()->updateExistingPivot($this->almacen_id, [
                'cantidad' => $stock + $cantidad,
            ]);
        }
    }

    public function registrarKardex($oldstock, $newstock, $simbolo, $detalle, $reference, $promocion_id = null)
    {
        return $this->kardex()->create([
            'date' => now('America/Lima'),
            'cantidad' => $this->cantidad,
            'oldstock' => $oldstock,
            'newstock' => $newstock,
            'simbolo' => $simbolo,
            'detalle' => $detalle,
            'reference' => $reference,
            'producto_id' => $this->producto_id,
            'almacen_id' => $this->almacen_id,
            'sucursal_id' => isset($this->sucursal_id) ? $this->sucursal_id : auth()->user()->sucursal_id,
            'user_id' => auth()->user()->id,
            'promocion_id' => $promocion_id,
        ]);
    }

    public function kardex(): MorphOne
    {
        return $this->morphOne(Kardex::class, 'kardeable');
    }

    public function getGratuitoAttribute($value)
    {
        return (int) $value;
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isGratuito()
    {
        return $this->gratuito == Tvitem::GRATUITO;
    }

    public function isDiscountStock()
    {
        return $this->alterstock == Almacen::DISMINUIR_STOCK;
    }

    public function isIncrementStock()
    {
        return $this->alterstock == Almacen::INCREMENTAR_STOCK;
    }

    public function isReservedStock()
    {
        return $this->alterstock == Almacen::RESERVAR_STOCK;
    }

    public function isNoAlterStock()
    {
        return $this->alterstock == Almacen::NO_ALTERAR_STOCK;
    }
}
