<?php

namespace App\Traits;

use App\Models\Almacen;
use App\Models\Kardex;
use App\Models\Producto;
use App\Models\Serie;
use App\Models\Tvitem;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Almacen\Entities\Compraitem;
use Nwidart\Modules\Facades\Module;

trait KardexTrait
{

    public function saveKardex($producto_id, $almacen_id, $oldstock, $newstock, $cantidad, $simbolo, $detalle, $reference, $promocion_id = null)
    {
        if (Module::isEnabled('Almacen')) {
            return Kardex::create([
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

    public function updateCantidad($instance, $new_qty, $newstock)
    {
        if ($instance->kardex) {
            $instance->kardex->cantidad = $new_qty;
            $instance->kardex->newstock = $newstock;
            $instance->kardex->save();
        } else {
        }
    }

    public function saveKardexTvitem($stock, $cantidad = null, $detalle, $referencia = null)
    {
        if ($this->kardex) {
        } else {
            $kardex = $this->kardex()->create([
                'date' =>  now('America/Lima'),
                'cantidad' => $this->cantidad,
                'oldstock' => $stock,
                'newstock' => $stock - $this->cantidad,
                'simbolo' => Almacen::SALIDA_ALMACEN,
                'detalle' => $detalle,
                'reference' => $referencia,
                'producto_id' => $this->producto_id,
                'almacen_id' => $this->almacen_id,
                'sucursal_id' => auth()->user()->sucursal_id ?? null,
                'user_id' => auth()->user()->id,
                'promocion_id' => $this->promocion_id,
            ]);
        }

        return $kardex;
    }

    public function saveKardexCarshoop($stock = null, $outspromocion)
    {
        if ($this->kardex) {
            $this->kardex->cantidad = $this->cantidad;
            $this->kardex->newstock = $this->kardex->oldstock - $this->cantidad;
            $this->kardex->save();
            if ($this->promocion) {
                $this->promocion->outs = $this->promocion->outs + $outspromocion;
                $this->promocion->save();
            }
        } else {
            $kardex = $this->kardex()->create([
                'date' => now('America/Lima'),
                'cantidad' => $this->cantidad,
                'oldstock' => $stock,
                'newstock' => $stock - $this->cantidad,
                'simbolo' => Almacen::SALIDA_ALMACEN,
                'detalle' => Kardex::ADD_VENTAS,
                'reference' => null,
                'producto_id' => $this->producto_id,
                'almacen_id' => $this->almacen_id,
                'sucursal_id' => auth()->user()->sucursal_id ?? null,
                'user_id' => auth()->user()->id,
                'promocion_id' => $this->promocion_id,
                // 'kardeable_id' => $this->id,
                // 'kardeable_type' => get_class($this),
            ]);

            if ($kardex->promocion) {
                $kardex->promocion->outs = $kardex->promocion->outs + $outspromocion;
                $kardex->promocion->save();
            }
            return $kardex;
        }
    }

    public function descontarSerieCarshoop($serie_id)
    {
        $carshoopserie = $this->carshoopseries()->create([
            'date' =>  $this->date,
            'serie_id' => $serie_id,
            'user_id' => auth()->user()->id
        ]);
        $carshoopserie->serie->status = Serie::SALIDA;
        $carshoopserie->serie->save();
        return $carshoopserie;
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

    public function kardexes(): MorphMany
    {
        return $this->morphMany(Kardex::class, 'kardeable');
    }

    public function getGratuitoAttribute($value)
    {
        return (int) $value;
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class)->withTrashed();
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
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
