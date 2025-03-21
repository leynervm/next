<?php

namespace App\Models;

use App\Traits\TvitemTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Ventas\Entities\Venta;

class Tvitem extends Model
{
    use HasFactory;
    use SoftDeletes;
    use TvitemTrait;

    public $timestamps = false;
    protected $fillable = [
        'date',
        'cantidad',
        'pricebuy',
        'price',
        'igv',
        'subtotaligv',
        'subtotal',
        'total',
        'status',
        'increment',
        'alterstock',
        'gratuito',
        'promocion_id',
        'almacen_id',
        'producto_id',
        'user_id',
        'moneda_id',
        'sucursal_id',
        'tvitemable_id',
        'tvitemable_type',
    ];

    const NO_GRATUITO = '0';
    const GRATUITO = '1';

    protected static function newFactory()
    {
        return \Modules\Marketplace\Database\factories\TvitemMarketplaceFactory::new();
    }

    public function tvitemable(): MorphTo
    {
        return $this->morphTo();
    }

    public function itemseries(): MorphMany
    {
        return $this->morphMany(Itemserie::class, 'seriable')->orderBy('id', 'asc');
    }


    public function carshoopitems(): HasMany
    {
        return $this->hasMany(Carshoopitem::class)->orderBy('id', 'asc');
    }

    public function promocion(): BelongsTo
    {
        return $this->belongsTo(Promocion::class);
    }

    public function scopeGratuitos($query)
    {
        return $query->where('gratuito', Self::GRATUITO);
    }

    public function scopeMicart($query)
    {
        return $query->where('user_id', auth()->user()->id)
            ->where('sucursal_id', auth()->user()->sucursal_id)
            // ->where('status', 0)
            ->whereNull('tvitemable_id');
    }

    public function scopeCarshoops($query)
    {
        return $query->micart()->where('tvitemable_type', Self::class);
    }

    public function scopeVentas($query)
    {
        return $query->where('tvitemable_type', Venta::class);
    }

    public function scopeGuias($query)
    {
        return $query->where('tvitemable_type', Guia::class);
    }

    //ACTUAL
    public function scopeInCart($query, $productoId, $gratuito, $alterstock, $monedaId = null, $promocionId = null)
    {
        return $query->where('producto_id', $productoId)
            ->where('gratuito', $gratuito)
            ->where('alterstock', $alterstock)
            ->where('moneda_id', $monedaId)
            ->where('promocion_id', $promocionId);
    }

    public function scopeExistsInCarshoop($query, $productoId, $monedaId, $promocionId)
    {
        return $query->micart()->where('producto_id', $productoId)
            // ->where('almacen_id', $almacenId)
            ->where('gratuito', Tvitem::NO_GRATUITO)
            ->where('alterstock', Almacen::DISMINUIR_STOCK)
            ->where('moneda_id', $monedaId)
            ->where('promocion_id', $promocionId)
            ->where('tvitemable_type', Self::class);
    }
}
