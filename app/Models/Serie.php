<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Almacen;
use Modules\Almacen\Entities\Compraitem;
use App\Models\Producto;

class Serie extends Model
{
    use HasFactory;
    use SoftDeletes;

    const DISPONIBLE = "0";
    const RESERVADA = "1";
    const SALIDA = "2";

    protected $guarded = ['created_at', 'updated_at'];

    public function setSerieAttribute($value)
    {
        $this->attributes['serie'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function compraitem(): BelongsTo
    {
        return $this->belongsTo(Compraitem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSalidas($query)
    {
        return $query->where('status', $this::SALIDA);
    }

    public function scopeReservadas($query)
    {
        return $query->where('status', $this::RESERVADA);
    }

    public function scopeDisponibles($query)
    {
        return $query->where('status', $this::DISPONIBLE);
    }

    public function itemserie(): HasOne
    {
        return $this->hasOne(Itemserie::class);
    }
}
