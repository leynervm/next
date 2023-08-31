<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Typemovement extends Model
{
    use HasFactory;

    public function scopeDefaultIngreso($query)
    {
        return $query->where('signo', '+');
    }

    public function scopeDefaultEgreso($query)
    {
        return $query->where('signo', '-');
    }
}
