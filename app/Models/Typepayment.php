<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Facturacion\Entities\Comprobante;

class Typepayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = ['name', 'paycuotas', 'default'];

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
        return $this->hasMany(Ventaonline::class);
    }

    public function isDefault()
    {
        return $this->default == 1;
    }

    public function isContado()
    {
        return $this->paycuotas == 0;
    }

    public function isCredito()
    {
        return $this->paycuotas == 1;
    }

    public function scopeDefaultTypepayment($query)
    {
        return $query->where('default', 1);
    }
}
