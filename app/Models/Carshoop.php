<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Almacen\Entities\Almacen;
use Modules\Almacen\Entities\Producto;

class Carshoop extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function carshoopseries(): HasMany
    {
        return $this->hasMany(Carshoopserie::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }
}
