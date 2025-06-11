<?php

namespace Modules\Soporte\Entities;

use App\Enums\EstadoEquipoEnum;
use App\Models\Marca;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipo extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = ['created_at', 'updated_at'];

    protected $casts = [
        'stateinicial' => EstadoEquipoEnum::class,
        'statefinal' => EstadoEquipoEnum::class,
    ];

    public function setSerieAttribute($value)
    {
        $this->attributes['serie'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setModeloAttribute($value)
    {
        $this->attributes['modelo'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setServicioAttribute($value)
    {
        $this->attributes['servicio'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function typeequipo(): BelongsTo
    {
        return $this->belongsTo(Typeequipo::class);
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
