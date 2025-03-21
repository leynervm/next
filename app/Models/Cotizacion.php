<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cotizacion extends Model
{
    use HasFactory;
    use SoftDeletes;

    const OPTION_ACTIVE = '1';
    const DEFAULT = '0';
    const APROBED = '1';
    const SYNCRONIZED = '2';
    const SERIE = 'C';

    public $timestamps = false;

    protected $fillable = [
        'date',
        'seriecompleta',
        'direccion',
        'validez',
        'entrega',
        'datecode',
        'afectacionigv',
        'igv',
        'subtotal',
        'total',
        'status',
        'detalle',
        'tipocambio',
        'moneda_id',
        'typepayment_id',
        'client_id',
        'user_id',
        'sucursal_id',
    ];

    public function setDetalleAttribute($value)
    {
        $this->attributes['detalle'] = trim(mb_strtoupper($value, "UTF-8"));
    }


    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function typepayment(): BelongsTo
    {
        return $this->belongsTo(Typepayment::class);
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cotizaciongarantias(): HasMany
    {
        return $this->hasMany(Cotizaciongarantia::class)->orderBy('id', 'asc');
    }

    public function tvitems(): MorphMany
    {
        return $this->morphMany(Tvitem::class, 'tvitemable')->orderBy('id', 'asc');
    }

    public function otheritems(): MorphMany
    {
        return $this->morphMany(Otheritem::class, 'otheritemable')->orderBy('id', 'asc');
    }

    public function lugar(): MorphOne
    {
        return $this->morphOne(Direccion::class, 'direccionable');
        // ->orderByDesc('default')->orderByDesc('id');
    }


    public function cotizables(): HasMany
    {
        return $this->hasMany(Cotizable::class);
        // ->orderByDesc('default')->orderByDesc('id');
    }

    public function isAfectacionIGV()
    {
        return $this->afectacionigv == self::OPTION_ACTIVE;
    }


    public function isAprobbed()
    {
        return $this->status == self::APROBED;
    }
}
