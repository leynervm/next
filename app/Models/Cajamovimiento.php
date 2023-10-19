<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Facturacion\Entities\Typepayment;

class Cajamovimiento extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'date', 'amount', 'typemovement', 'referencia', 'detalle',
        'moneda_id', 'methodpayment_id', 'cuenta_id', 'concept_id', 'opencaja_id',
        'user_id', 'cajamovimientable_id', 'cajamovimientable_type'
    ];

    public function typepayment(): BelongsTo
    {
        return $this->belongsTo(Typepayment::class);
    }

    public function methodpayment(): BelongsTo
    {
        return $this->belongsTo(methodpayment::class);
    }

    public function typemovement(): BelongsTo
    {
        return $this->belongsTo(Typemovement::class);
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class);
    }

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    public function opencaja(): BelongsTo
    {
        return $this->belongsTo(Opencaja::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cuenta(): BelongsTo
    {
        return $this->belongsTo(Cuenta::class);
    }

    public function cajamovimientable(): MorphTo
    {
        return $this->morphTo();
    }
}
