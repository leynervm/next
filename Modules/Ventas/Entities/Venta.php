<?php

namespace Modules\Ventas\Entities;

use App\Models\Cajamovimiento;
use App\Models\Client;
use App\Models\Cuota;
use App\Models\Guia;
use App\Models\Moneda;
use App\Models\Seriecomprobante;
use App\Models\Sucursal;
use App\Models\Tvitem;
use App\Models\User;
use App\Models\Typepayment;
use App\Traits\CajamovimientoTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Facturacion\Entities\Comprobante;
use Modules\Marketplace\Database\factories\VentaFactory;
use Modules\Marketplace\Entities\Tracking;
use Modules\Marketplace\Entities\TvitemMarketplace;

class Venta extends Model
{
    use HasFactory;
    use SoftDeletes;

    use CajamovimientoTrait;

    protected $fillable = [
        'date', 'seriecompleta', 'direccion', 'exonerado', 'gravado', 'gratuito',
        'descuento', 'otros', 'inafecto', 'igv', 'igvgratuito',
        'subtotal', 'total', 'tipocambio', 'increment', 'paymentactual',
        'moneda_id', 'typepayment_id', 'client_id', 'seriecomprobante_id', 'user_id', 'sucursal_id'
    ];

    protected static function newFactory()
    {
        return VentaFactory::new();
    }

    // public function nextpaymentcuotas(): MorphMany
    // {
    //     return $this->morphMany(Cuota::class, 'cuotable')
    //         ->whereDoesntHave('cajamovimiento')->orderBy('expiredate', 'asc');
    // }

    public function setDireccionAttribute($value)
    {
        $this->attributes['direccion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function cuotas(): MorphMany
    {
        return $this->morphMany(Cuota::class, 'cuotable')->orderBy('id', 'asc');
    }

    public function tvitems(): MorphMany
    {
        return $this->morphMany(Tvitem::class, 'tvitemable')->orderBy('id', 'asc');
    }

    public function cajamovimientos(): MorphMany
    {
        return $this->morphMany(Cajamovimiento::class, 'cajamovimientable');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function typepayment(): BelongsTo
    {
        return $this->belongsTo(Typepayment::class);
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class);
    }

    public function seriecomprobante(): BelongsTo
    {
        return $this->belongsTo(Seriecomprobante::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function comprobante(): MorphOne
    {
        return $this->morphOne(Comprobante::class, 'facturable')->withTrashed();
    }

    public function guia(): MorphOne
    {
        return $this->morphOne(Guia::class, 'guiable')->withTrashed();
    }

    public function scopeWhereDateBetween($query, $fieldName, $date, $dateto)
    {
        return $query->whereDate($fieldName, '>=', $date)->whereDate($fieldName, '<=', $dateto);
    }
}
