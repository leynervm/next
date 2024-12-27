<?php

namespace App\Models;

use App\Traits\KardexTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Promocion extends Model
{
    use HasFactory;
    use KardexTrait;

    public $timestamps = false;

    const DESCUENTO = '0';
    const COMBO = '1';
    const REMATE = '2';
    const LIQUIDACION = '2';

    const ACTIVO = '0';
    const DESACTIVADO = '1';
    const FINALIZADO = '2';

    protected $casts = [
        'outs' => 'integer',
        'limit' => 'integer',
        // 'startdate' => 'date:Y-m-d',
        // 'expiredate' => 'date:Y-m-d'
    ];

    protected $fillable = ['titulo', 'pricebuy', 'status', 'type', 'descuento', 'limit', 'outs', 'startdate', 'expiredate', 'producto_id'];

    public function setTituloAttribute($value)
    {
        $this->attributes['titulo'] = trim(mb_convert_case(mb_strtolower($value, "UTF-8"), MB_CASE_TITLE, "UTF-8"));
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function itempromos(): HasMany
    {
        return $this->hasMany(Itempromo::class);
    }

    public function kardexes(): MorphMany
    {
        return $this->morphMany(Kardex::class, 'kardeable');
    }

    public function scopeDescuentos($query)
    {
        return $query->where('type', self::DESCUENTO);
    }

    public function scopeCombos($query)
    {
        return $query->where('type', self::COMBO);
    }

    public function scopeRemates($query)
    {
        return $query->where('type', self::LIQUIDACION);
    }

    public function scopeLiquidaciones($query)
    {
        return $query->where('type', self::LIQUIDACION);
    }

    public function scopeAvailables($query)
    {
        return $query->where('status', self::ACTIVO)->whereColumn('limit', '>', 'outs');
    }

    public function scopeDisponibles($query)
    {
        return $query->whereNull('startdate')->whereNull('expiredate')
            ->orWhereNull('startdate')->whereDate('expiredate', '>=', Carbon::now('America/Lima')->format('Y-m-d'))
            ->orWhereDate('startdate', '<=', Carbon::now('America/Lima')->format('Y-m-d'))->whereNull('expiredate')
            ->orWhereDate('startdate', '<=', Carbon::now('America/Lima')->format('Y-m-d'))->whereDate('expiredate', '>=', Carbon::now('America/Lima')->format('Y-m-d'))
            ->orderBy('startdate', 'desc');
    }


    // public function scopeCombosDisponibles($query, $almacenId = null)
    // {
    //     return $query->combos()->disponibles()->whereHas('itempromos', function ($query) use ($almacenId) {
    //         $query->whereHas('producto', function ($query) use ($almacenId) {
    //             $query->whereHas('almacens', function ($query) use ($almacenId) {
    //                 $query->selectRaw('almacen_id, SUM(almacen_producto.cantidad) as stock_total, almacen_id')
    //                     ->groupBy('almacen_id') // Aseguramos que almacen_id esté en GROUP BY
    //                     ->when($almacenId, function ($query) use ($almacenId) {
    //                         $query->where('almacen_id', $almacenId); // Filtra por almacén si se especifica
    //                     })
    //                     ->havingRaw('SUM(almacen_producto.cantidad) > 0');
    //             });
    //         });
    //     });
    // }

    public function isDisponible()
    {
        return
            $this->startdate == null && $this->expiredate == null ||
            $this->startdate == null && $this->expiredate >= Carbon::now('America/Lima')->format('Y-m-d') ||
            $this->startdate <= Carbon::now('America/Lima')->format('Y-m-d') && $this->expiredate == null ||
            Carbon::parse($this->startdate)->lte(Carbon::now('America/Lima')->format('Y-m-d')) && Carbon::parse($this->expiredate)->gte(Carbon::now('America/Lima')->format('Y-m-d'));
    }

    public function isAvailable()
    {
        return $this->limit > 0 && $this->limit > $this->outs;
    }

    public function isAgotado()
    {
        return $this->limit > 0 && $this->outs >= $this->limit;
    }

    public function isDescuento()
    {
        return $this->type == self::DESCUENTO;
    }

    public function isCombo()
    {
        return $this->type == self::COMBO;
    }

    public function isRemate()
    {
        return $this->type == self::LIQUIDACION;
    }

    public function isLiquidacion()
    {
        return $this->type == self::LIQUIDACION;
    }

    public function isDesactivado()
    {
        return $this->status == self::DESACTIVADO;
    }

    public function isFinalizado()
    {
        return $this->status == self::FINALIZADO;
    }

    public function isExpired()
    {
        return !is_null($this->expiredate) && Carbon::now('America/Lima')->gt(Carbon::parse($this->expiredate)->format('d-m-Y'));
    }

    public function isActivo()
    {
        return $this->status == self::ACTIVO;
    }
}
