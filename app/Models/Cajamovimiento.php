<?php

namespace App\Models;

use App\Enums\MovimientosEnum;
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

    public function scopeSumatorias($query, $monthbox_id, $openbox_id, $user_id)
    {
        return $query->where('sucursal_id', $user_id)
            ->where('monthbox_id', $monthbox_id)->where('openbox_id', $openbox_id)
            ->selectRaw('moneda_id, typemovement, SUM(totalamount) as total')->groupBy('moneda_id')
            ->groupBy('typemovement')->orderBy('total', 'desc');
    }

    public function scopeDiferencias($query, $monthbox_id, $openbox_id, $user_id)
    {
        return $query->where('sucursal_id', $user_id)
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
}
