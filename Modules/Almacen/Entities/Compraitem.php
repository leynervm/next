<?php

namespace Modules\Almacen\Entities;

use App\Models\Almacencompra;
use App\Models\Producto;
use App\Traits\KardexTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compraitem extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'cantidad',
        'price',
        'oldprice',
        'oldpricesale',
        'igv',
        'descuento',
        'subtotaligv',
        'subtotaldescuento',
        'subtotal',
        'total',
        'producto_id',
        'compra_id'
    ];

    public function almacencompras(): HasMany
    {
        return $this->hasMany(Almacencompra::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class);
    }
}
