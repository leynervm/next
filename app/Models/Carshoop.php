<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use App\Models\Almacen;
use App\Models\Producto;
use App\Traits\KardexTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Ventas\Entities\Venta;

class Carshoop extends Model
{
    use HasFactory;
    use KardexTrait;

    public $timestamps = false;

    protected $fillable = [
        'date', 'cantidad', 'pricebuy', 'price',  'igv', 'alterstock', 'promocion_id',
        'subtotal', 'total', 'gratuito', 'status', 'producto_id', 'almacen_id',
        'moneda_id', 'user_id', 'sucursal_id', 'cartable_id', 'cartable_type'
    ];

    public function cartable(): MorphTo
    {
        return $this->morphTo();
    }

    public function carshoopseries(): HasMany
    {
        return $this->hasMany(Carshoopserie::class);
    }

    public function carshoopitems(): HasMany
    {
        return $this->hasMany(Carshoopitem::class);
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class);
    }

    public function promocion(): BelongsTo
    {
        return $this->belongsTo(Promocion::class);
    }

    public function scopeMicarrito($query)
    {
        return $query->where('user_id', Auth::user()->id)->where('status', 0);
    }

    public function scopeGuias($query)
    {
        return $query->where('cartable_type', Guia::class);
    }

    public function scopeVentas($query)
    {
        return $query->where('cartable_type', Venta::class);
    }

    
}
