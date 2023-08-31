<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Moneda extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function scopeDefaultMoneda($query)
    {
        return $query->where('default', 1);
    }
}
