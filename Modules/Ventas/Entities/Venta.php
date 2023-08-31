<?php

namespace Modules\Ventas\Entities;

use App\Models\Cajamovimiento;
use App\Models\Client;
use App\Models\Moneda;
use App\Models\Tribute;
use App\Models\Tvitem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Facturacion\Entities\Comprobante;
use Modules\Facturacion\Entities\Typepayment;

class Venta extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function nextpagos()
    {
        return $this->hasMany(Cuota::class)
            ->whereNull('cajamovimiento_id')->orderBy('expiredate', 'asc');
    }

    public function cuotas()
    {
        return $this->hasMany(Cuota::class)->orderBy('id', 'asc');
    }

    public function tvitems(): MorphMany
    {
        return $this->morphMany(Tvitem::class, 'tvitemable');
    }

    public function cajamovimiento()
    {
        return $this->belongsTo(Cajamovimiento::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function typepayment(): BelongsTo
    {
        return $this->belongsTo(Typepayment::class);
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
