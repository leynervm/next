<?php

namespace Modules\Ventas\Entities;

use App\Models\Cajamovimiento;
use App\Models\Client;
use App\Models\Cuota;
use App\Models\Moneda;
use App\Models\Tvitem;
use App\Models\Typecomprobante;
use App\Models\User;
use App\Models\Typepayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Facturacion\Entities\Comprobante;


class Venta extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function nextpagos()
    {
        return $this->hasMany(Cuota::class)
            ->whereNull('cajamovimiento_id')->orderBy('expiredate', 'asc');
    }

    public function cuotas(): MorphMany
    {
        return $this->morphMany(Cuota::class, 'cuotable')->orderBy('id', 'asc');
    }

    public function tvitems(): MorphMany
    {
        return $this->morphMany(Tvitem::class, 'tvitemable');
    }

    public function cajamovimiento(): MorphOne
    {
        return $this->morphOne(Cajamovimiento::class, 'cajamovimientable');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function typepayment(): BelongsTo
    {
        return $this->belongsTo(Typepayment::class);
    }

    public function typecomprobante(): BelongsTo
    {
        return $this->belongsTo(Typecomprobante::class);
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comprobante(): MorphOne
    {
        return $this->morphOne(Comprobante::class, 'facturable');
    }
}
