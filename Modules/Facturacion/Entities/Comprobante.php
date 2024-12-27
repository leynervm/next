<?php

namespace Modules\Facturacion\Entities;

use App\Models\Client;
use App\Models\Cuota;
use App\Models\Empresa;
use App\Models\Guia;
use App\Models\Moneda;
use App\Models\Seriecomprobante;
use App\Models\Sucursal;
use App\Models\Typepayment;
use App\Models\User;
use App\Traits\EnviarComprobanteSunat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Ventas\Entities\Venta;

class Comprobante extends Model
{
    use HasFactory;
    use SoftDeletes;
    use EnviarComprobanteSunat;

    const ENVIADO_SUNAT = '0';

    protected $guarded = ['created_at', 'updated_at'];
    protected $policyNamespace = '\Modules\Facturacion\Policies\PolicyComprobante';


    public function setDireccionAttribute($value)
    {
        $this->attributes['direccion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setObservacionesAttribute($value)
    {
        $this->attributes['observaciones'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setLeyendaAttribute($value)
    {
        $this->attributes['leyenda'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function facturable(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

    public function scopeVentas($query)
    {
        return $query->where('facturable_type', Venta::class);
    }

    public function seriecomprobante(): BelongsTo
    {
        return $this->belongsTo(Seriecomprobante::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class)->withTrashed();
    }

    public function client()
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class)->withTrashed();
    }

    public function facturableitems(): HasMany
    {
        return $this->hasMany(Facturableitem::class)->orderBy('item', 'asc');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function typepayment()
    {
        return $this->belongsTo(Typepayment::class);
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class);
    }

    // public function guias(): HasMany
    // {
    //     return $this->hasMany(Guia::class);
    // }

    public function guia(): MorphOne
    {
        return $this->morphOne(Guia::class, 'guiable');
    }


    public function cuotas(): MorphMany
    {
        return $this->morphMany(Cuota::class, 'cuotable');
    }

    public function scopeWhereDateBetween($query, $fieldName, $date, $dateto)
    {
        return $query->whereDate($fieldName, '>=', $date)->whereDate($fieldName, '<=', $dateto);
    }

    public function scopeSendSunat($query)
    {
        return $query->where('codesunat', self::ENVIADO_SUNAT);
    }

    public function scopeNoEnviadoSunat($query)
    {
        return $query->where('codesunat', '<>', self::ENVIADO_SUNAT)
            ->orWhereNull('codesunat');
    }

    public function isSendSunat()
    {
        return trim($this->codesunat) == self::ENVIADO_SUNAT;
    }

    public function isProduccion()
    {
        return $this->sendmode == Empresa::PRODUCCION;
    }
}
