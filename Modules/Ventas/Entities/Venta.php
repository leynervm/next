<?php

namespace Modules\Ventas\Entities;

use App\Enums\FilterReportsEnum;
use App\Models\Cajamovimiento;
use App\Models\Client;
use App\Models\Cuota;
use App\Models\Guia;
use App\Models\Moneda;
use App\Models\Seriecomprobante;
use App\Models\Sucursal;
use App\Models\Tvitem;
use App\Models\User;
use App\Models\Typepayment;
use App\Traits\CajamovimientoTrait;
use App\Traits\GenerarComprobante;
use App\Traits\RegistrarCuotas;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Facturacion\Entities\Comprobante;
use Modules\Marketplace\Database\factories\VentaFactory;

class Venta extends Model
{
    use HasFactory;
    use SoftDeletes;

    use CajamovimientoTrait;
    use GenerarComprobante;
    use RegistrarCuotas;

    protected $fillable = [
        'date',
        'seriecompleta',
        'direccion',
        'exonerado',
        'gravado',
        'gratuito',
        'descuento',
        'otros',
        'inafecto',
        'igv',
        'igvgratuito',
        'subtotal',
        'total',
        'tipocambio',
        'increment',
        'paymentactual',
        'observaciones',
        'moneda_id',
        'typepayment_id',
        'client_id',
        'seriecomprobante_id',
        'user_id',
        'sucursal_id'
    ];

    protected static function newFactory()
    {
        return VentaFactory::new();
    }

    // public function nextpaymentcuotas(): MorphMany
    // {
    //     return $this->morphMany(Cuota::class, 'cuotable')
    //         ->whereDoesntHave('cajamovimiento')->orderBy('expiredate', 'asc');
    // }

    public function setDireccionAttribute($value)
    {
        $this->attributes['direccion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setObservacionesAttribute($value)
    {
        $this->attributes['observaciones'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function cuotas(): MorphMany
    {
        return $this->morphMany(Cuota::class, 'cuotable')->orderBy('id', 'asc');
    }

    public function tvitems(): MorphMany
    {
        return $this->morphMany(Tvitem::class, 'tvitemable')->orderBy('id', 'asc');
    }

    public function cajamovimientos(): MorphMany
    {
        return $this->morphMany(Cajamovimiento::class, 'cajamovimientable');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function typepayment(): BelongsTo
    {
        return $this->belongsTo(Typepayment::class);
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class);
    }

    public function seriecomprobante(): BelongsTo
    {
        return $this->belongsTo(Seriecomprobante::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function comprobante(): MorphOne
    {
        return $this->morphOne(Comprobante::class, 'facturable')->withTrashed();
    }

    public function guia(): MorphOne
    {
        return $this->morphOne(Guia::class, 'guiable')->withTrashed();
    }

    public function scopeWhereDateBetween($query, $fieldName, $date, $dateto)
    {
        return $query->whereDate($fieldName, '>=', $date)->whereDate($fieldName, '<=', $dateto);
    }

    public function scopeQueryFilter($query, $filters)
    {
        $query->when($filters['sucursal_id'] ?? null, function ($query, $sucursal_id) {
            $query->where('sucursal_id', $sucursal_id);
        })->when($filters['client_id'] ?? null, function ($query, $client_id) {
            $query->where('client_id', $client_id);
        })->when($filters['user_id'] ?? null, function ($query, $user_id) {
            $query->where('user_id', $user_id);
        })->when($filters['moneda_id'] ?? null, function ($query, $moneda_id) {
            $query->where('moneda_id', $moneda_id);
        })->when($filters['typereporte'] == FilterReportsEnum::DIARIO->value, function ($query) use ($filters) {
            $query->whereDate('date', $filters['date']);
        })->when($filters['typereporte'] == FilterReportsEnum::RANGO_DIAS->value, function ($query) use ($filters) {
            $query->WhereDateBetween('date', $filters['date'], $filters['dateto']);
        })->when($filters['typereporte'] == FilterReportsEnum::RANGO_MESES->value, function ($query) use ($filters) {
            $query->whereRaw("TO_CHAR(date, 'YYYY-MM')>= ? AND TO_CHAR(date, 'YYYY-MM')<= ?", [
                Carbon::parse($filters['month'])->format('Y-m'),
                Carbon::parse($filters['monthto'])->format('Y-m'),
            ]);
        })->when($filters['typereporte'] == FilterReportsEnum::SEMANAL->value, function ($query) use ($filters) {
            [$year, $weekNumber] = explode('-W', $filters['week']);
            $query->whereRaw(
                "date >= DATE_TRUNC('week', TO_DATE(?, 'IYYY-IW')) AND date < DATE_TRUNC('week', TO_DATE(?, 'IYYY-IW')) + INTERVAL '1 week'",
                [$year . '-' . $weekNumber, $year . '-' . $weekNumber]
            );
        })->when($filters['typereporte'] == FilterReportsEnum::SEMANA_ACTUAL->value, function ($query) {
            $query->whereBetween('date', [
                Carbon::now('America/Lima')->startOfWeek(),
                Carbon::now('America/Lima')->endOfWeek(),
            ]);
        })->when($filters['typereporte'] == FilterReportsEnum::MES_ACTUAL->value, function ($query) {
            $query->whereRaw("TO_CHAR(date, 'YYYY-MM') = ?", [
                Carbon::now('America/Lima')->format('Y-m'),
            ]);
        })->when($filters['typereporte'] == FilterReportsEnum::MENSUAL->value, function ($query) use ($filters) {
            $query->whereRaw("TO_CHAR(date, 'YYYY-MM') = ?", [
                Carbon::parse($filters['month'])->format('Y-m'),
            ]);
        })->when($filters['typereporte'] == FilterReportsEnum::ANUAL->value, function ($query) use ($filters) {
            $query->whereYear('date', $filters['year']);
        })->when($filters['typereporte'] == FilterReportsEnum::ANIO_ACTUAL->value, function ($query) {
            $query->whereYear('date', Carbon::now('America/Lima')->year);
        })->when($filters['typereporte'] == FilterReportsEnum::ULTIMA_SEMANA->value, function ($query) {
            $query->whereBetween('date', [
                Carbon::now('America/Lima')->subWeek()->startOfWeek(),
                Carbon::now('America/Lima')->subWeek()->endOfWeek(),
            ]);
        })->when($filters['typereporte'] == FilterReportsEnum::ULTIMO_MES->value, function ($query) {
            $query->whereRaw("TO_CHAR(date, 'YYYY-MM') = ?", [
                Carbon::now('America/Lima')->subMonth()->format('Y-m')
            ]);
        })->when($filters['typereporte'] == FilterReportsEnum::ULTIMO_ANIO->value, function ($query) {
            $query->whereYear('date', Carbon::now('America/Lima')->subYear()->year);
        })->when($filters['typereporte'] == FilterReportsEnum::DIAS_SELECCIONADOS->value, function ($query) use ($filters) {
            $query->whereRaw("TO_CHAR(date, 'YYYY-MM-DD') IN (" . implode(',', array_fill(0, count($filters['days']), '?')) . ")", $filters['days']);
        })->when($filters['typereporte'] == FilterReportsEnum::MESES_SELECCIONADOS->value, function ($query) use ($filters) {
            $query->whereRaw("TO_CHAR(date, 'YYYY-MM') IN (" . implode(',', array_fill(0, count($filters['months']), '?')) . ")", $filters['months']);
        });

        // ->when($filters['typecomprobante_id'] ?? null, function ($query, $typecomprobante_id) {
        //     $query->where('typecomprobante_id', $typecomprobante_id);
        // })->when($filters['methodpayment_id'] ?? null, function ($query, $methodpayment_id) {
        //     $query->where('methodpayment_id', $methodpayment_id);
        // })->when($filters['monthbox_id'] ?? null, function ($query, $monthbox_id) {
        //     $query->where('monthbox_id', $monthbox_id);
        // })->when($filters['openbox_id'] ?? null, function ($query, $openbox_id) {
        //     $query->where('openbox_id', $openbox_id);
        // })
    }
}
