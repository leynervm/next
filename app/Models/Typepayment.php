<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Almacen\Entities\Compra;
use Modules\Facturacion\Entities\Comprobante;
use Modules\Ventas\Entities\Venta;

class Typepayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['name', 'paycuotas', 'default', 'status'];

    const DEFAULT = '1';
    const CREDITO = '1';
    const CONTADO = '0';
    const ACTIVO = '0';

    public function getNameAttribute($value)
    {
        return trim(mb_strtoupper($value, "UTF-8"));
    }

    public function comprobantes(): HasMany
    {
        return $this->hasMany(Comprobante::class);
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class);
    }

    public function isDefault()
    {
        return $this->default == self::DEFAULT;
    }

    public function isContado()
    {
        return $this->paycuotas == self::CONTADO;
    }

    public function isCredito()
    {
        return $this->paycuotas == self::CREDITO;
    }

    public function isActivo()
    {
        return $this->status == self::ACTIVO;
    }

    public function scopeActivos($query)
    {
        return $query->where('status', self::ACTIVO);
    }

    public function scopeDefault($query)
    {
        return $query->where('default', self::DEFAULT);
    }
}
