<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Facturacion\Entities\Comprobante;
use Modules\Ventas\Entities\Venta;

class Seriecomprobante extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['serie', 'code', 'contador', 'default', 'typecomprobante_id', 'sucursal_id'];
    const DEFAULT = '1';

    public function setSerieAttribute($value)
    {
        $this->attributes['serie'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function typecomprobante(): BelongsTo
    {
        return $this->belongsTo(typecomprobante::class);
    }

    public function comprobantes(): HasMany
    {
        return $this->hasMany(Comprobante::class);
    }

    public function guias(): HasMany
    {
        return $this->hasMany(Guia::class);
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function sucursals(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function scopeDefault($query)
    {
        return $query->withPivot('default', self::DEFAULT);
    }
}
