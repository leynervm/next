<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Concept extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['name', 'default'];

    const VENTAS = "1";
    const INTERNET = "2";
    const PAYCUOTA = "3";
    const COMPRA = "4";
    const PAYCUOTACOMPRA = "5";

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function scopeDefaultConceptVentas($query)
    {
        return $query->where('default', $this::VENTAS);
    }

    public function scopeDefaultConceptInternet($query)
    {
        return $query->where('default', $this::INTERNET);
    }

    public function scopeDefaultConceptPaycuota($query)
    {
        return $query->where('default', $this::PAYCUOTA);
    }

    public function scopeDefaultConceptCompra($query)
    {
        return $query->where('default', $this::COMPRA);
    }

    public function scopeDefaultConceptPaycuotaCompra($query)
    {
        return $query->where('default', $this::PAYCUOTACOMPRA);
    }
}
