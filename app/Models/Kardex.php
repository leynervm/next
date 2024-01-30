<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Kardex extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'date', 'cantidad', 'oldstock', 'newstock', 'simbolo', 'detalle',
        'reference', 'producto_id', 'almacen_id', 'sucursal_id',
        'user_id', 'kardeable_id', 'kardeable_type'
    ];


    const ENTRADA_ALMACEN = 'INGRESO COMPRA ALMACÉN';
    const ENTRADA_PRODUCTO = 'INGRESO NUEVO ALMACÉN';
    const ENTRADA_DEVOLUCION = 'INGRESO POR DEVOLUCIÓN';
    const SALIDA_VENTA = 'SALIDA VENTA FÍSICA';
    const SALIDA_SOPORTE = 'SALIDA SOPORTE TÉCNICO';
    const SALIDA_GUIA = 'SALIDA GUÍA REMISIÓN';
    const SALIDA_PATRIMONIO = 'SALIDA PATRIMONIO EMPRESA';
    const ACTUALIZACION_MANUAL = 'ACTUALIZACIÓN MANUAL ALMACÉN';
    const REPOSICION_ANULACION = 'ACTUALIZACIÓN ANULACIÓN COMPROBANTE';
    const SALIDA_ANULACION_COMPRA = 'ACTUALIZACIÓN ANULACIÓN COMPRA';


    public function kardeable(): MorphTo
    {
        return $this->morphTo();
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }
    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWhereDateBetween($query, $fieldName, $date, $dateto)
    {
        return $query->whereDate($fieldName, '>=', $date)->whereDate($fieldName, '<=', $dateto);
    }
}
