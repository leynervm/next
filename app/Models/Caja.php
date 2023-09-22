<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caja extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    const ACTIVO = "0";
    const INACTIVO = "1";

    protected $fillable = ['name', 'status', 'user_id'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    // public function scopeDefaultCaja($query)
    // {
    //     return $query->where('datemonth', Carbon::now('America/Lima')->format('Y-m'))
    //         ->where('startdate', '<=',  Carbon::now('America/Lima')->format('Y-m-d H:i:s'))
    //         ->where('expiredate', '>', Carbon::now('America/Lima')->format('Y-m-d H:i:s'));
    // }

    public function scopeDisponibles($query)
    {
        return $query->where('status', 0)->whereNull('user_id');
    }

    public function scopeAbiertas($query)
    {
        return $query->where('status', 1);
    }
}
