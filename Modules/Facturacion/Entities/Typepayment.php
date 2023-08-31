<?php

namespace Modules\Facturacion\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Typepayment extends Model
{
    use HasFactory;

    public function isDefault()
    {
        return $this->default === 1;
    }

    public function isContado()
    {
        return $this->paycuotas === 1;
    }

    public function scopeDefaultTypepayment($query)
    {
        return $query->where('default', 1);
    }
}
