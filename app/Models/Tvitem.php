<?php

namespace App\Models;

use App\Traits\KardexTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Almacen;
use App\Models\Producto;

class Tvitem extends Model
{
    use HasFactory;
    use SoftDeletes;

    use KardexTrait;

    protected $guarded = ['created_at', 'updated_at'];

    const GRATUITO = '1';
    const PENDING_SERIE = '1';

    public function getGratuitoAttribute($value)
    {
        return (int) $value;
    }

    public function tvitemable(): MorphTo
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

    public function itemseries()
    {
        return $this->hasMany(Itemserie::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kardex(): MorphOne
    {
        return $this->morphOne(Kardex::class, 'kardeable');
    }

    public function isGratuito()
    {
        return $this->gratuito == self::GRATUITO;
    }

    public function isDiscountStock()
    {
        return $this->alterstock == Almacen::DISMINUIR_STOCK;
    }

    public function isIncrementStock()
    {
        return $this->alterstock == Almacen::INCREMENTAR_STOCK;
    }

    public function isReservedStock()
    {
        return $this->alterstock == Almacen::RESERVAR_STOCK;
    }

    public function isNoAlterStock()
    {
        return $this->alterstock == Almacen::NO_ALTERAR_STOCK;
    }

    public function isPendingSerie()
    {
        return $this->requireserie == self::PENDING_SERIE;
    }
}
