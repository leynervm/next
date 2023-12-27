<?php

namespace Modules\Almacen\Entities;

use App\Models\Cajamovimiento;
use App\Models\Cuota;
use App\Models\Moneda;
use App\Models\Proveedor;
use App\Models\Typepayment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Compra extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'date', 'referencia', 'guia', 'detalle', 'tipocambio', 'gravado',
        'exonerado', 'igv', 'descuento', 'otros', 'total', 'moneda_id',
        'typepayment_id', 'proveedor_id', 'user_id',
        'sucursal_id'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = Auth::user()->id;
        });
    }

    public function setReferenciaAttribute($value)
    {
        $this->attributes['referencia'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setGuiaAttribute($value)
    {
        $this->attributes['guia'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class);
    }

    public function typepayment(): BelongsTo
    {
        return $this->belongsTo(Typepayment::class);
    }

    public function cajamovimiento(): MorphOne
    {
        return $this->morphOne(Cajamovimiento::class, 'cajamovimientable');
    }

    public function compraitems(): HasMany
    {
        return $this->hasMany(Compraitem::class)->orderBy('id', 'asc');
    }

    public function cuotas(): MorphMany
    {
        return $this->morphMany(Cuota::class, 'cuotable')->orderBy('id', 'asc');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cuotaspendientes(): MorphMany
    {
        return $this->morphMany(Cuota::class, 'cuotable')
            ->doesntHave('cajamovimiento')
            ->orderBy('id', 'asc');
    }
}
