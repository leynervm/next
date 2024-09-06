<?php

namespace App\Models;

use App\Models\Cajamovimiento;
use App\Models\User;
use App\Traits\CajamovimientoTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;


class Cuota extends Model
{
    use HasFactory;
    use CajamovimientoTrait;

    protected $guarded = ['created_at', 'updated_at'];

    public function cuotable(): MorphTo
    {
        return $this->morphTo();
    }

    // public function cajamovimiento(): MorphOne
    // {
    //     return $this->morphOne(Cajamovimiento::class, 'cajamovimientable');
    // }

    public function cajamovimientos(): MorphMany
    {
        return $this->morphMany(Cajamovimiento::class, 'cajamovimientable');
    }

    public function userpay(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userpayment_id')->withTrashed();
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class)->withTrashed();
    }
}
