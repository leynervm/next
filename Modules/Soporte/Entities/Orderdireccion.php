<?php

namespace Modules\Soporte\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderdireccion extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function setDireccionAttribute($value)
    {
        $this->attributes['direccion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setReferenciaAttribute($value)
    {
        $this->attributes['referencia'] = trim(mb_strtoupper($value, "UTF-8"));
    }
}
