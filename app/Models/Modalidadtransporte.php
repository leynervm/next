<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modalidadtransporte extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function getNameAttribute($value)
    {
        return trim(mb_strtoupper($value, "UTF-8"));
    }
}
