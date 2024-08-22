<?php

namespace App\Models;

use App\Traits\KardexTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Almacen\Entities\Compraitem;

class Almacencompra extends Model
{
    use HasFactory;
    use KardexTrait;

    public $timestamps = false;
    protected $fillable = ['cantidad', 'almacen_id', 'compraitem_id'];

    public function compraitem(): BelongsTo
    {
        return $this->belongsTo(Compraitem::class);
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }

    public function series(): HasMany
    {
        return $this->hasMany(Serie::class);
    }

    public function kardex(): MorphOne
    {
        return $this->morphOne(Kardex::class, 'kardeable');
    }
}
