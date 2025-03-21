<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Ventas\Entities\Venta;

class Cotizable extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'date',
        'status',
        'cotizacion_id',
        'user_id',
        'cotizable_id',
        'cotizable_type'
    ];


    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cotizable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isVenta()
    {
        return $this->cotizable->getMorphClass() == Venta::class;
    }
}
