<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caja extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    const ACTIVO = "0";
    const INACTIVO = "1";

    protected $fillable = ['name', 'status', 'sucursal_id'];

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

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function opencajas(): HasMany
    {
        return $this->hasMany(Opencaja::class);
    }

    public function scopeOpencajasdisponibles()
    {
        return $this->opencajas()->whereNull('expiredate');
    }

    public function scopeOpencajasdisponiblesuser()
    {
        return $this->opencajasdisponibles()
            ->where('user_id', auth()->user()->id);
    }


    public function isUsing()
    {
        return $this->sucursal_id ==  auth()->user()->sucursalDefault()->first()->id;
    }
}
