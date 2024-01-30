<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promocion extends Model
{
    use HasFactory;
    public $timestamps = false;

    const DESCUENTO = '0';
    const COMBO = '1';
    const REMATE = '2';

    protected $fillable = ['type', 'descuento', 'limit', 'outs', 'startdate', 'expiredate', 'producto_id'];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function itempromos(): HasMany
    {
        return $this->hasMany(Itempromo::class);
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

    public function scopeActivos($query)
    {
        return $query->where('status', '0');
    }

    public function isActivo()
    {
        return $this->status == '0';
    }

    public function isDisponible()
    {
        return $this->expiredate >= Carbon::now('America/Lima')->format('Y-m-d');
    }

    public function scopeDisponibles($query)
    {
        return $query->activos()->whereDate('startdate', '<=', Carbon::now('America/Lima')->format('Y-m-d'))
            ->whereDate('expiredate', '>=', Carbon::now('America/Lima')->format('Y-m-d'))
            ->orWhereNull('startdate')->whereNull('expiredate')
            ->orderBy('startdate', 'asc')->orderBy('status', 'asc')
            ->orderBy('id', 'asc');
    }
}
