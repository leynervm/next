<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Almacen\Entities\Almacen;
use Modules\Almacen\Entities\Producto;
use Modules\Facturacion\Entities\Typepayment;

class Tvitem extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

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
}
