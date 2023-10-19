<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Typecomprobante extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    public function isDefault()
    {
        return $this->default === 1;
    }

    public function scopeDefaultTypecomprobante($query)
    {
        return $query->where('default', 1);
    }
}
