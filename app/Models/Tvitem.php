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
}
