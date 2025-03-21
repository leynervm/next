<?php

namespace Modules\Almacen\Entities;

use App\Models\Almacencompra;
use App\Models\Kardex;
use App\Models\Producto;
use App\Models\Serie;
use App\Traits\KardexTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
        'typedescuento',
        'producto_id',
        'compra_id'
    ];

    const SIN_DESCUENTO = '0';
    const PRECIO_UNIT_DSCTO_APLICADO = '1';
    const PRECIO_UNIT_SIN_DSCTO_APLICADO = '2';
    const DSCTO_IMPORTE_TOTAL = '3';


    //Eliminar despues de actualizar
    public function almacencompras(): HasMany
    {
        return $this->hasMany(Almacencompra::class);
    } //End

    public function kardexes(): MorphMany
    {
        return $this->morphMany(Kardex::class, 'kardeable');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class);
    }

    public function series(): HasMany
    {
        return $this->hasMany(Serie::class);
    }
}
