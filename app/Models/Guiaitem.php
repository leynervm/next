<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guiaitem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item',
        'linereference',
        'cantidad',
        'descripcion',
        'code',
        'unit',
        'guia_id'
    ];


    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = trim(mb_strtoupper($value, "UTF-8"));
    }
}
