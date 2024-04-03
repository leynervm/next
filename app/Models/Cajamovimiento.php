<?php

namespace App\Models;

use App\Enums\MovimientosEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cajamovimiento extends Model
{
    use HasFactory;
    use SoftDeletes;

    // const INGRESO = "INGRESO";
    // const EGRESO = "EGRESO";

    protected $fillable = [
        'date', 'amount', 'typemovement', 'referencia', 'detalle', 'moneda_id',
        'methodpayment_id', 'concept_id', 'monthbox_id', 'openbox_id',
        'sucursal_id', 'user_id', 'cajamovimientable_id', 'cajamovimientable_type'
    ];

    protected $casts = [
        'typemovement' => MovimientosEnum::class
    ];

    public function setDetalleAttribute($value)
    {
        $this->attributes['detalle'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function typepayment(): BelongsTo
    {
        return $this->belongsTo(Typepayment::class);
    }

    public function methodpayment(): BelongsTo
    {
        return $this->belongsTo(methodpayment::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class);
    }

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    public function openbox(): BelongsTo
    {
        return $this->belongsTo(Openbox::class);
    }

    public function monthbox(): BelongsTo
    {
        return $this->belongsTo(Monthbox::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cajamovimientable(): MorphTo
    {
        return $this->morphTo();
    }

    public function ingresos($query)
    {
        return $query->where('typemovement', MovimientosEnum::INGRESO);
    }

    public function scopeWhereDateBetween($query, $fieldName, $date, $dateto)
    {
        return $query->whereDate($fieldName, '>=', $date)->whereDate($fieldName, '<=', $dateto);
    }

    public function isIngreso()
    {
        return $this->typemovement == MovimientosEnum::INGRESO;
    }

    public function isEgreso()
    {
        return $this->typemovement == MovimientosEnum::EGRESO;
    }
    
}
