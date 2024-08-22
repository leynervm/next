<?php

namespace App\Models;

use App\Traits\EnviarGuiaRemisionSunat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Facturacion\Entities\Comprobante;
use Modules\Ventas\Entities\Venta;

class Guia extends Model
{
    use HasFactory;
    use SoftDeletes;
    use EnviarGuiaRemisionSunat;

    protected $guarded = ['created_at', 'updated_at'];

    // indicadortransbordo
    // indicadorvehiculosml
    // indicadorvehretorenvacios
    // indicadorvehretorvacio
    // indicadordamds
    // indicadorconductor
    const IND_TRA_PROG = 'SUNAT_Envio_IndicadorTransbordoProgramado';
    const NAME_IND_TRA_PROG = 'Realiza transbordo programado';
    const IND_TRASL_VEHIC_CAT_ML = 'SUNAT_Envio_IndicadorTrasladoVehiculoM1L';
    const NAME_IND_TRASL_VEHIC_CAT_ML = 'Traslado en vehículos de categoría M1 o L';
    const IND_RET_VEH_ENV_EMB_VAC = 'SUNAT_Envio_IndicadorRetornoVehiculoEnvaseVacio';
    const NAME_IND_RET_VEH_ENV_EMB_VAC = 'Retorno de vehículo con envases o embalajes vacíos';
    const IND_RET_VEH_VAC = 'SUNAT_Envio_IndicadorRetornoVehiculoVacio';
    const NAME_IND_RET_VEH_VAC = 'Retorno de vehículo vacío';
    const IND_TOT_MERC_DAM_DS = 'SUNAT_Envio_IndicadorTrasladoTotalDAMoDS';
    const NAME_IND_TOT_MERC_DAM_DS = '';
    const IND_REG_VEHIC_COND_TRANSP = 'SUNAT_Envio_IndicadorVehiculoConductoresTransp';
    const NAME_IND_REG_VEHIC_COND_TRANSP = 'Registrar vehículos y conductores del transportista';

    const ENVIADO_SUNAT = '0';

    public function getDatetrasladoAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getPesoAttribute($value)
    {
        return formatDecimalOrInteger($value, 2);
    }

    public function setNametransportAttribute($value)
    {
        $this->attributes['nametransport'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setNamedestinatarioAttribute($value)
    {
        $this->attributes['namedestinatario'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setNoteAttribute($value)
    {
        $this->attributes['note'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setReferenciaAttribute($value)
    {
        $this->attributes['referencia'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setPlacavehiculoAttribute($value)
    {
        $this->attributes['placavehiculo'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDireccionorigenAttribute($value)
    {
        $this->attributes['direccionorigen'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDirecciondestinoAttribute($value)
    {
        $this->attributes['direcciondestino'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    // public function comprobante(): BelongsTo
    // {
    //     return $this->belongsTo(Comprobante::class);
    // }

    public function guiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeGuias($query)
    {
        return $query->where('cartable_type', Guia::class);
    }

    public function scopeVentas($query)
    {
        return $query->where('cartable_type', Venta::class);
    }

    public function scopeComprobantes($query)
    {
        return $query->where('cartable_type', Comprobante::class);
    }

    public function tvitems(): MorphMany
    {
        return $this->morphMany(Tvitem::class, 'tvitemable')->orderBy('id', 'asc');
    }

    public function transportvehiculos(): HasMany
    {
        return $this->hasMany(Transportvehiculo::class)
            ->orderBy('principal', 'desc')->orderBy('id', 'asc');
    }

    public function transportdrivers(): HasMany
    {
        return $this->hasMany(Transportdriver::class)
            ->orderBy('principal', 'desc')->orderBy('id', 'asc');
    }

    public function motivotraslado(): BelongsTo
    {
        return $this->belongsTo(Motivotraslado::class)->withTrashed();
    }

    public function modalidadtransporte(): BelongsTo
    {
        return $this->belongsTo(Modalidadtransporte::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class)->withTrashed();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function seriecomprobante(): BelongsTo
    {
        return $this->belongsTo(Seriecomprobante::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function ubigeoorigen(): BelongsTo
    {
        return $this->belongsTo(Ubigeo::class, 'ubigeoorigen_id');
    }

    public function ubigeodestino(): BelongsTo
    {
        return $this->belongsTo(Ubigeo::class, 'ubigeodestino_id');
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

    public function isVehiculosml()
    {
        return $this->indicadorvehiculosml == '1';
    }
}
