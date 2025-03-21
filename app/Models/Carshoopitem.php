<?php

namespace App\Models;

use App\Traits\KardexTrait;
use App\Traits\TvitemTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Carshoopitem extends Model
{
    use HasFactory;
    use KardexTrait;
    use TvitemTrait;

    public $timestamps = false;
    protected $fillable = [
        'cantidad',
        'pricebuy',
        'price',
        'igv',
        'subtotaligv',
        'subtotal',
        'total',
        'itempromo_id',
        'producto_id',
        'tvitem_id'
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function tvitem(): BelongsTo
    {
        return $this->belongsTo(Tvitem::class);
    }

    public function itempromo(): BelongsTo
    {
        return $this->belongsTo(Itempromo::class);
    }

    public function kardexes(): MorphMany
    {
        return $this->morphMany(Kardex::class, 'kardeable');
    }

    public function itemseries(): MorphMany
    {
        return $this->morphMany(Itemserie::class, 'seriable')->orderBy('id', 'asc');
    }
}
