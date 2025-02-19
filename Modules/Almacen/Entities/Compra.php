<?php

namespace Modules\Almacen\Entities;

use App\Enums\FilterReportsEnum;
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
use Illuminate\Support\Carbon;

class Compra extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CajamovimientoTrait;

    protected $fillable = [
        'date',
        'referencia',
        'guia',
        'detalle',
        'tipocambio',
        'gravado',
        'exonerado',
        'igv',
        'descuento',
        'otros',
        'total',
        'moneda_id',
        'typepayment_id',
        'proveedor_id',
        'user_id',
        'status',
        'afectacion',
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

    public function setDetalleAttribute($value)
    {
        $this->attributes['detalle'] = trim(mb_strtoupper($value, "UTF-8"));
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

    public function isExonerado()
    {
        return $this->afectacion == 'E';
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
            ->doesntHave('cajamovimientos')
            ->orderBy('id', 'asc');
    }

    public function scopeQueryFilter($query, $filters)
    {
        $query->when($filters['sucursal_id'] ?? null, function ($query, $sucursal_id) {
            $query->where('sucursal_id', $sucursal_id);
        })->when($filters['proveedor_id'] ?? null, function ($query, $proveedor_id) {
            $query->where('proveedor_id', $proveedor_id);
        })->when($filters['user_id'] ?? null, function ($query, $user_id) {
            $query->where('user_id', $user_id);
        })->when($filters['moneda_id'] ?? null, function ($query, $moneda_id) {
            $query->where('moneda_id', $moneda_id);
        })->when($filters['typereporte'] == FilterReportsEnum::DIARIO->value, function ($query) use ($filters) {
            $query->whereDate('date', $filters['date']);
        })->when($filters['typereporte'] == FilterReportsEnum::MENSUAL->value, function ($query) use ($filters) {
            $query->whereRaw("TO_CHAR(date, 'YYYY-MM') = ?", [
                Carbon::parse($filters['month'])->format('Y-m'),
            ]);
        })->when($filters['typereporte'] == FilterReportsEnum::ANUAL->value, function ($query) use ($filters) {
            $query->whereYear('date', $filters['year']);
        })->when($filters['typereporte'] == FilterReportsEnum::ULTIMO_MES->value, function ($query) {
            $query->whereRaw("TO_CHAR(date, 'YYYY-MM') = ?", [
                Carbon::now('America/Lima')->subMonth()->format('Y-m')
            ]);
        })->when($filters['typereporte'] == FilterReportsEnum::ULTIMO_ANIO->value, function ($query) {
            $query->whereYear('date', Carbon::now('America/Lima')->subYear()->year);
        });
    }
}
