<?php

namespace App\Models;

use App\Enums\FilterReportsEnum;
use App\Enums\MovimientosEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Cajamovimiento extends Model
{
    use HasFactory;
    use SoftDeletes;

    // const INGRESO = "INGRESO";
    // const EGRESO = "EGRESO";

    protected $fillable = [
        'date',
        'amount',
        'totalamount',
        'tipocambio',
        'typemovement',
        'referencia',
        'detalle',
        'moneda_id',
        'methodpayment_id',
        'concept_id',
        'monthbox_id',
        'openbox_id',
        'sucursal_id',
        'user_id',
        'cajamovimientable_id',
        'cajamovimientable_type'
    ];

    protected $casts = [
        'typemovement' => MovimientosEnum::class
    ];

    public function setDetalleAttribute($value)
    {
        $this->attributes['detalle'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function typepayment(): BelongsTo
    {
        return $this->belongsTo(Typepayment::class);
    }

    public function methodpayment(): BelongsTo
    {
        return $this->belongsTo(Methodpayment::class)->withTrashed();
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class)->withTrashed();
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class);
    }

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class)->withTrashed();
    }

    public function openbox(): BelongsTo
    {
        return $this->belongsTo(Openbox::class)->withTrashed();
    }

    public function monthbox(): BelongsTo
    {
        return $this->belongsTo(Monthbox::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function cajamovimientable(): MorphTo
    {
        return $this->morphTo();
    }

    public function ingresos($query)
    {
        return $query->where('typemovement', MovimientosEnum::INGRESO);
    }

    public function scopeWhereDateBetween($query, $fieldName, $date, $dateto)
    {
        return $query->whereDate($fieldName, '>=', $date)->whereDate($fieldName, '<=', $dateto);
    }

    public function isIngreso()
    {
        return $this->typemovement == MovimientosEnum::INGRESO;
    }

    public function isEgreso()
    {
        return $this->typemovement == MovimientosEnum::EGRESO;
    }

    public function scopeSumatorias($query, $monthbox_id, $openbox_id, $sucursal_id)
    {
        return $query->where('sucursal_id', $sucursal_id)
            ->where('monthbox_id', $monthbox_id)->where('openbox_id', $openbox_id)
            ->selectRaw('moneda_id, typemovement, SUM(totalamount) as total')->groupBy('moneda_id')
            ->groupBy('typemovement')->orderBy('total', 'desc');
    }

    public function scopeDiferencias($query, $monthbox_id, $openbox_id, $sucursal_id)
    {
        return $query->where('sucursal_id', $sucursal_id)
            ->where('monthbox_id', $monthbox_id)->where('openbox_id', $openbox_id)
            ->selectRaw("moneda_id, SUM(CASE WHEN typemovement = 'INGRESO' THEN totalamount ELSE -totalamount END) as diferencia")
            ->groupBy('moneda_id')->orderBy('diferencia', 'desc');
    }

    public function scopeSaldo($query, $typepayment, $monthbox_id, $openbox_id, $sucursal_id, $moneda_id)
    {
        return $query->withWhereHas('methodpayment', function ($query) use ($typepayment) {
            $query->where('type', $typepayment);
        })->where('monthbox_id', $monthbox_id)->where('openbox_id', $openbox_id)
            ->where('sucursal_id', $sucursal_id)->where('moneda_id', $moneda_id)
            ->selectRaw("COALESCE(SUM(CASE WHEN typemovement = '" . MovimientosEnum::INGRESO->value . "' THEN totalamount ELSE -totalamount END), 0) as diferencia");
        // ->where('sucursal_id', auth()->user()->sucursal_id)
    }

    public function scopeDiferenciasByType($query, $openbox_id, $sucursal_id)
    {
        return $query->where('sucursal_id', $sucursal_id)
            ->where('openbox_id', $openbox_id)
            ->join('methodpayments', 'methodpayments.id', '=', 'cajamovimientos.methodpayment_id')
            ->selectRaw("moneda_id, (CASE WHEN type = '" . Methodpayment::EFECTIVO . "' THEN 'EFECTIVO' ELSE 'TRANSFERENCIA' END) as type, 
                SUM(CASE WHEN typemovement = 'INGRESO' THEN totalamount ELSE -totalamount END) as diferencia")
            ->groupBy('moneda_id', 'type')->orderBy('diferencia', 'desc');
    }

    public function scopeQueryFilter($query, $filters)
    {
        $query->when($filters['sucursal_id'] ?? null, function ($query, $sucursal_id) {
            $query->where('sucursal_id', $sucursal_id);
        })->when($filters['typemovement'] ?? null, function ($query, $typemovement) {
            $query->where('typemovement', $typemovement);
        })->when($filters['concept_id'] ?? null, function ($query, $concept_id) {
            $query->where('concept_id', $concept_id);
        })->when($filters['methodpayment_id'] ?? null, function ($query, $methodpayment_id) {
            $query->where('methodpayment_id', $methodpayment_id);
        })->when($filters['user_id'] ?? null, function ($query, $user_id) {
            $query->where('user_id', $user_id);
        })->when($filters['monthbox_id'] ?? null, function ($query, $monthbox_id) {
            $query->where('monthbox_id', $monthbox_id);
        })->when($filters['openbox_id'] ?? null, function ($query, $openbox_id) {
            $query->where('openbox_id', $openbox_id);
        })->when($filters['moneda_id'] ?? null, function ($query, $moneda_id) {
            $query->where('moneda_id', $moneda_id);
        });


        if ($filters['typereporte'] == FilterReportsEnum::DIARIO->value) {
            $query->whereDate('date', $filters['date']);
        } elseif ($filters['typereporte'] == FilterReportsEnum::RANGO_DIAS->value) {
            $query->WhereDateBetween('date', $filters['date'], $filters['dateto']);
        } elseif ($filters['typereporte'] == FilterReportsEnum::RANGO_MESES->value) {
            $query->whereRaw("TO_CHAR(date, 'YYYY-MM')>= ? AND TO_CHAR(date, 'YYYY-MM')<= ?", [
                Carbon::parse($filters['month'])->format('Y-m'),
                Carbon::parse($filters['monthto'])->format('Y-m'),
            ]);
        } else if ($filters['typereporte'] == FilterReportsEnum::SEMANAL->value) {
            [$year, $weekNumber] = explode('-W', $filters['week']);
            $query->whereRaw(
                "date >= DATE_TRUNC('week', TO_DATE(?, 'IYYY-IW')) AND date < DATE_TRUNC('week', TO_DATE(?, 'IYYY-IW')) + INTERVAL '1 week'",
                [$year . '-' . $weekNumber, $year . '-' . $weekNumber]
            );
        } else if ($filters['typereporte'] == FilterReportsEnum::SEMANA_ACTUAL->value) {
            $query->whereBetween('date', [
                Carbon::now('America/Lima')->startOfWeek(),
                Carbon::now('America/Lima')->endOfWeek(),
            ]);
            // $query->whereRaw('date >= DATE_TRUNC(\'week\', CURRENT_DATE)')
            //     ->whereRaw('date < DATE_TRUNC(\'week\', CURRENT_DATE) + INTERVAL \'1 week\'');
        } elseif ($filters['typereporte'] == FilterReportsEnum::MES_ACTUAL->value) {
            $query->whereRaw("TO_CHAR(date, 'YYYY-MM') = ?", [
                Carbon::now('America/Lima')->format('Y-m'),
            ]);
        } elseif ($filters['typereporte'] == FilterReportsEnum::MENSUAL->value) {
            $query->whereRaw("TO_CHAR(date, 'YYYY-MM') = ?", [
                Carbon::parse($filters['month'])->format('Y-m'),
            ]);
        } elseif ($filters['typereporte'] == FilterReportsEnum::ANUAL->value) {
            $query->whereYear('date', $filters['year']);
        } elseif ($filters['typereporte'] == FilterReportsEnum::ANIO_ACTUAL->value) {
            $query->whereYear('date', Carbon::now('America/Lima')->year);
        } elseif ($filters['typereporte'] == FilterReportsEnum::ULTIMA_SEMANA->value) {
            $query->whereBetween('date', [
                Carbon::now('America/Lima')->subWeek()->startOfWeek(),
                Carbon::now('America/Lima')->subWeek()->endOfWeek(),
            ]);
            // $query->whereRaw('date >= DATE_TRUNC(\'week\', CURRENT_DATE) - INTERVAL \'1 week\'')
            //     ->whereRaw('date < DATE_TRUNC(\'week\', CURRENT_DATE)');
        } elseif ($filters['typereporte'] == FilterReportsEnum::ULTIMO_MES->value) {
            $query->whereRaw("TO_CHAR(date, 'YYYY-MM') = ?", [
                Carbon::now('America/Lima')->subMonth()->format('Y-m')
            ]);
        } elseif ($filters['typereporte'] == FilterReportsEnum::ULTIMO_ANIO->value) {
            $query->whereYear('date', Carbon::now('America/Lima')->subYear()->year);
        } elseif ($filters['typereporte'] == FilterReportsEnum::DIAS_SELECCIONADOS->value) {
            $query->whereRaw("TO_CHAR(date, 'YYYY-MM-DD') IN (" . implode(',', array_fill(0, count($filters['days']), '?')) . ")", $filters['days']);
        } elseif ($filters['typereporte'] == FilterReportsEnum::MESES_SELECCIONADOS->value) {
            $query->whereRaw("TO_CHAR(date, 'YYYY-MM') IN (" . implode(',', array_fill(0, count($filters['months']), '?')) . ")", $filters['months']);
        }
    }
}
