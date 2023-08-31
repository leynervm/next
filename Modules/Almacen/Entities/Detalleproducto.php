<?php

namespace Modules\Almacen\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalleproducto extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    // public function setNameAttribute($value)
    // {
    //     $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    // }
}
