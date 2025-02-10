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
        'date',
        'cantidad',
        'oldstock',
        'newstock',
        'simbolo',
        'detalle',
        'reference',
        'producto_id',
        'almacen_id',
        'sucursal_id',
        'user_id',
        'promocion_id',
        'kardeable_id',
        'kardeable_type'
    ];


    const ENTRADA_ALMACEN = 'INGRESO COMPRA ALMACÉN';
    const ENTRADA_PRODUCTO = 'INGRESO NUEVO ALMACÉN';
    const ENTRADA_DEVOLUCION = 'INGRESO POR DEVOLUCIÓN';
    const SALIDA_VENTA = 'SALIDA VENTA FÍSICA';
    const SALIDA_VENTA_WEB = 'SALIDA VENTA WEB';
    const SALIDA_COMBO_VENTA = 'SALIDA COMBO VENTA FÍSICA';
    const SALIDA_SOPORTE = 'SALIDA SOPORTE TÉCNICO';
    const SALIDA_GUIA = 'SALIDA GUÍA REMISIÓN';
    const RESERVADO_GUIA = 'RESERVADO GUÍA REMISIÓN';
    const ADD_GUIA = 'AGREGADO GUIA REMISIÓN ';
    const ADD_VENTAS = 'AGREGADO CARRITO VENTAS ';
    const ENTRADA_GUIA = 'ENTRADA GUÍA REMISIÓN';
    const SALIDA_PATRIMONIO = 'SALIDA PATRIMONIO EMPRESA';
    const ACTUALIZACION_MANUAL = 'ACTUALIZACIÓN MANUAL ALMACÉN';
    const REPOSICION_ANULACION = 'ANULACIÓN COMPROBANTE';
    const SALIDA_ANULACION_COMPRA = 'ANULACIÓN COMPRA';
    const SALIDA_ANULACION_ITEMCOMPRA = 'ANULACIÓN ITEM COMPRA';
    const REPOSICION_ALMACEN = 'REPOSICIÓN ALMACEN';


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

    public function promocion(): BelongsTo
    {
        return $this->belongsTo(Promocion::class);
    }

    public function isEntrada()
    {
        return $this->simbolo == '+';
    }
    
    public function isSalida()
    {
        return $this->simbolo == '-';
    }

    public function scopeWhereDateBetween($query, $fieldName, $date, $dateto)
    {
        return $query->whereDate($fieldName, '>=', $date)->whereDate($fieldName, '<=', $dateto);
    }
}
