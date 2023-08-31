<?php

namespace Modules\Almacen\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Garantiaproducto extends Model
{
    use HasFactory;
    

    protected $guarded = ['created_at', 'updated_at'];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function typegarantia(): BelongsTo
    {
        return $this->belongsTo(Typegarantia::class);
    }
}
