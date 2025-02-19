<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Almacen\Entities\Compra;
use Modules\Ventas\Entities\Venta;

class Moneda extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    const DEFAULT = '1';

    protected $fillable = ['currency', 'code', 'simbolo', 'default'];

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setCurrencyAttribute($value)
    {
        $this->attributes['currency'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function cajamovimientos(): HasMany
    {
        return $this->hasMany(Cajamovimiento::class);
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class);
    }

    public function scopeDefault($query)
    {
        return $query->where('default', self::DEFAULT);
    }

    public function isDefault()
    {
        return $this->default == self::DEFAULT;
    }

    public function isLocal()
    {
        return $this->code == 'PEN';
    }

    public function isSoles()
    {
        return $this->code == 'PEN';
    }

    public function isDolar()
    {
        return $this->code == 'USD';
    }
}
