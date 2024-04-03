<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Turno extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'name', 'horaingreso', 'horasalida'
    ];

    public function getHoraingresoAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function getHorasalidaAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    // public function sucursal(): BelongsTo
    // {
    //     return $this->belongsTo(Sucursal::class);
    // }
}
