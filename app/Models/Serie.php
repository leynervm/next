<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Almacen;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Almacen\Entities\Compraitem;

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

    // Eliminar en futuro
    public function almacencompra(): BelongsTo
    {
        return $this->belongsTo(Almacencompra::class);
    }
    // 

    public function compraitem(): BelongsTo
    {
        return $this->belongsTo(Compraitem::class);
    }

    public function carshoopserie(): HasOne
    {
        return $this->hasOne(Carshoopserie::class);
    }

    public function itemseries(): HasMany
    {
        return $this->hasMany(Itemserie::class);
    }

    // public function itemserie(): HasOne
    // {
    //     return $this->hasOne(Itemserie::class);
    // }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function scopeSalidas($query)
    {
        return $query->where('status', self::SALIDA);
    }

    public function scopeReservadas($query)
    {
        return $query->where('status', $this::RESERVADA);
    }

    public function scopeDisponibles($query)
    {
        return $query->where('status', self::DISPONIBLE);
    }

    public function scopeAlmacen($query, $almacen_id)
    {
        return $query->where('almacen_id', $almacen_id);
    }

    public function isDisponible()
    {
        return $this->status == self::DISPONIBLE;
    }

    public function isSalida()
    {
        return $this->status == self::SALIDA;
    }

    public function isReservada()
    {
        return $this->status == self::RESERVADA;
    }

    public function scopeWhereDateBetween($query, $fieldName, $date, $dateto)
    {
        return $query->whereDate($fieldName, '>=', $date)->whereDate($fieldName, '<=', $dateto);
    }
}
