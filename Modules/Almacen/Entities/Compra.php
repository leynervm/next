<?php

namespace Modules\Almacen\Entities;

use App\Models\Cajamovimiento;
use App\Models\Cuota;
use App\Models\Moneda;
use App\Models\Proveedor;
use App\Models\Sucursal;
use App\Models\Typepayment;
use App\Models\User;
use App\Traits\CajamovimientoTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compra extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CajamovimientoTrait;

    protected $fillable = [
        'date', 'referencia', 'guia', 'detalle', 'tipocambio', 'gravado',
        'exonerado', 'igv', 'descuento', 'otros', 'total', 'moneda_id',
        'typepayment_id', 'proveedor_id', 'user_id', 'status',
        'sucursal_id'
    ];

    const OPEN = '0';
    const CLOSE = '1';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->user()->id;
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

    public function isOpen()
    {
        return $this->status == self::OPEN;
    }

    public function isClose()
    {
        return $this->status == self::CLOSE;
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class)->withTrashed();
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class)->withTrashed();
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class);
    }

    public function typepayment(): BelongsTo
    {
        return $this->belongsTo(Typepayment::class);
    }

    public function cajamovimientos(): MorphMany
    {
        return $this->morphMany(Cajamovimiento::class, 'cajamovimientable');
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
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function cuotaspendientes(): MorphMany
    {
        return $this->morphMany(Cuota::class, 'cuotable')
            ->doesntHave('cajamovimiento')
            ->orderBy('id', 'asc');
    }
}
