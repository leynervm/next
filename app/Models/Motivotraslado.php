<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motivotraslado extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['code', 'name', 'typecomprobante_id'];

    public $palabrasclave = [
        'Otro', 'Otros', 'Traslado', 'Traslados', 'Transp', 'Transporte',
        'Transportes', 'Varios', 'Venta', 'Ventas', 'Vta',
        'Vta.', 'Venta entrega a terceros', 'Venta a terceros', 'Venta con entrega a terceros',
        'Compra', 'Compras', 'Traslado entre establecimientos de la misma empresa',
        'Traslado entre establecimiento de la misma empresa', 'Traslado entre establecimiento de la misma emp',
        'Traslado establecimientos de la misma emp', 'Traslado establecimientos de misma emp',
        'Traslado entre establecimiento misma emp', 'Consignación',
        'Consignacion', 'Devolución', 'Devolucion', 'Recojo de bienes transformados',
        'Recojo bienes transformados', 'Recojo bs transformados', 'Recojo de bien transformado',
        'Recojo bien transformado', 'Importación', 'Importacion', 'Exportación',
        'Exportacion', 'Venta sujeta a confirmación del comprador', 'Venta sujeta a confirmación',
        'Vta sujeta a confirmación', 'Venta sujeta a confirmacion del comprador',
        'Venta sujeta a confirmacion', 'Vta sujeta a confirmacion', 'Traslado de bienes para transformación',
        'Traslado bienes para transformación', 'Traslado bs para transformación',
        'Traslado de bien para transformación', 'Traslado bien para transformación',
        'Traslado de bienes para transformacion', 'Traslado bienes para transformacion',
        'Traslado de bien para transformacion', 'Traslado bien para transformacion',
        'Traslado emisor itinerante de comprobantes de pago', 'Traslado emisor itinerante de comprobantes',
        'Traslado emisor itinerante de cp', 'Traslado emisor itinerante de cdp',
        'Traslado emisor itinerante comprobantes de pago', 'Traslado emisor itinerante comprobantes',
        'Traslado emisor itinerante cp', 'Traslado emisor itinerante cdp',
        'Emisor itinerante de comprobantes de pago', 'Emisor itinerante de comprobantes',
        'Emisor itinerante de cp', 'Emisor itinerante de cdp', 'Emisor itinerante comprobantes de pago',
        'Emisor itinerante comprobantes', 'Emisor itinerante cp', 'Emisor itinerante cdp',
        'Emisor itinerante', 'Traslado itinerante',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function typecomprobante(): BelongsTo
    {
        return $this->belongsTo(Typecomprobante::class);
    }
}
