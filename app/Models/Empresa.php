<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function scopeDefaultEmpresa($query)
    {
        return $query->where('default', 1);
    }
}
