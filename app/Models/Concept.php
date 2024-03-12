<?php

namespace App\Models;

use App\Enums\DefaultConceptsEnum;
use App\Enums\MovimientosEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Concept extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['name', 'typemovement', 'default'];

    protected $casts = [
        'typemovement' => MovimientosEnum::class,
        'default' => DefaultConceptsEnum::class
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function cajamovimientos()
    {
        return $this->hasMany(Cajamovimiento::class);
    }

    public function scopeVentas($query)
    {
        return $query->where('default', DefaultConceptsEnum::VENTAS);
    }

    public function scopeInternet($query)
    {
        return $query->where('default', DefaultConceptsEnum::INTERNET);
    }

    public function scopePaycuota($query)
    {
        return $query->where('default', DefaultConceptsEnum::PAYCUOTA);
    }

    public function scopeCompra($query)
    {
        return $query->where('default', DefaultConceptsEnum::COMPRA);
    }

    public function scopePaycuotaCompra($query)
    {
        return $query->where('default', DefaultConceptsEnum::PAYCUOTACOMPRA);
    }

    public function scopeAdelantoemployer($query)
    {
        return $query->where('default', DefaultConceptsEnum::ADELANTOEMPLOYER);
    }

    public function scopePayemployer($query)
    {
        return $query->where('default', DefaultConceptsEnum::PAYEMPLOYER);
    }

    public function isDefault()
    {
        return $this->default == DefaultConceptsEnum::DEFAULT;
    }

    public function isIngreso()
    {
        return $this->typemovement == MovimientosEnum::INGRESO;
    }

    public function isDeletemanual()
    {
        return $this->default == DefaultConceptsEnum::DEFAULT ||
            $this->default == DefaultConceptsEnum::ADELANTOEMPLOYER;
    }
}
