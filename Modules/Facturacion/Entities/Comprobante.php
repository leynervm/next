<?php

namespace Modules\Facturacion\Entities;

use App\Models\Tribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comprobante extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function facturable(): MorphTo
    {
        return $this->morphTo();
    }

    public function tribute(): BelongsTo
    {
        return $this->belongsTo(Tribute::class);
    }

    public function typecomprobante()
    {
        return $this->belongsTo(Typecomprobante::class);
    }

    public function facturableitems(): HasMany
    {
        return $this->hasMany(Facturableitem::class);
    }
}
