<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Concept extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['created_at', 'updated_at'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function scopeDefaultConceptVentas($query)
    {
        return $query->where('default', 1);
    }

    public function scopeDefaultConceptInternet($query)
    {
        return $query->where('default', 2);
    }

    public function scopeDefaultConceptPaycuota($query)
    {
        return $query->where('default', 3);
    }
}
