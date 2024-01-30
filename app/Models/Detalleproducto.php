<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalleproducto extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['descripcion', 'producto_id'];

    // public function setNameAttribute($value)
    // {
    //     $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    // }
}
