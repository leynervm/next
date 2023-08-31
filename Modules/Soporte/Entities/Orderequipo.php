<?php

namespace Modules\Soporte\Entities;

use App\Models\Equipo;
use App\Models\Marca;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orderequipo extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function setModeloAttribute($value)
    {
        $this->attributes['modelo'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setSerieAttribute($value)
    {
        $this->attributes['serie'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function equipo():BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }

    public function marca():BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }
}
