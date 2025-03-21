<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cotizaciongarantia extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'time',
        'datecode',
        'typegarantia_id',
        'cotizacion_id'
    ];


    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function typegarantia(): BelongsTo
    {
        return $this->belongsTo(Typegarantia::class);
    }
}
