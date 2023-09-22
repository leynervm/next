<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Facturacion\Entities\Typepayment;

class Cajamovimiento extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

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
}
