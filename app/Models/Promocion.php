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

    const ACTIVO = '0';
    const DESACTIVADO = '1';
    const FINALIZADO = '2';

    protected $fillable = ['type', 'descuento', 'limit', 'outs', 'startdate', 'expiredate', 'producto_id'];

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
        return $query->where('type', self::REMATE);
    }

    // public function scopeActivos($query)
    // {
    //     return $query->where('status', self::ACTIVO)
    //         ->orWhere('expiredate', '>=', Carbon::now('America/Lima')->format('Y-m-d'));
    // }

    public function scopeDisponibles($query)
    {
        return
            $query->where('status', self::ACTIVO)->whereNull('startdate')->whereNull('expiredate')
            ->orWhere('status', self::ACTIVO)->whereNull('startdate')->whereDate('expiredate', '>=', Carbon::now('America/Lima')->format('Y-m-d'))
            ->orWhere('status', self::ACTIVO)->whereDate('startdate', '<=', Carbon::now('America/Lima')->format('Y-m-d'))->whereNull('expiredate')
            ->orWhere('status', self::ACTIVO)->whereDate('startdate', '<=', Carbon::now('America/Lima')->format('Y-m-d'))->whereDate('expiredate', '>=', Carbon::now('America/Lima')->format('Y-m-d'))
            ->orderBy('startdate', 'desc');
    }

    public function isDisponible()
    {
        return
            $this->status == self::ACTIVO && $this->startdate == null && $this->expiredate == null ||
            $this->status == self::ACTIVO && $this->startdate == null && $this->expiredate >= Carbon::now('America/Lima')->format('Y-m-d') ||
            $this->status == self::ACTIVO && $this->startdate <= Carbon::now('America/Lima')->format('Y-m-d') && $this->expiredate == null ||
            $this->status == self::ACTIVO && Carbon::parse($this->startdate)->lte(Carbon::now('America/Lima')->format('Y-m-d')) && Carbon::parse($this->expiredate)->gte(Carbon::now('America/Lima')->format('Y-m-d'));
    }

    public function isAvailable()
    {
        return $this->limit == null || $this->limit > 0 && $this->outs < $this->limit;
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
        return $this->type == self::REMATE;
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
}
