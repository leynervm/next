<?php

namespace App\Models;

use App\Traits\CajamovimientoTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employer extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CajamovimientoTrait;

    protected $fillable = [
        'document', 'name', 'nacimiento', 'sexo', 'sueldo', 'horaingreso',
        'horasalida', 'areawork_id', 'sucursal_id', 'user_id'
    ];
    public $timestamps = false;

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function getHoraingresoAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function getHorasalidaAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function areawork(): BelongsTo
    {
        return $this->belongsTo(Areawork::class);
    }

    public function telephone(): MorphOne
    {
        return $this->morphOne(Telephone::class, 'telephoneable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employerpayments(): HasMany
    {
        return $this->hasMany(Employerpayment::class);
    }

    public function cajamovimientos(): MorphMany
    {
        return $this->morphMany(Cajamovimiento::class, 'cajamovimientable');
    }
}
