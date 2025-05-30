<?php

namespace Modules\Soporte\Entities;

use App\Models\Areawork;
use App\Models\Direccion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Soporte\Entities\Atencion;
use Modules\Soporte\Entities\Condition;
use Modules\Soporte\Entities\Entorno;
use Modules\Soporte\Entities\Priority;

class Ticket extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['created_at', 'updated_at'];

    public function setDetalleAttribute($value)
    {
        $this->attributes['detalle'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function repairs(): HasOne
    {
        return $this->hasOne(Repair::class);
    }

    public function direccion(): HasOne
    {
        return $this->hasOne(Direccion::class);
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }

    public function atencion(): BelongsTo
    {
        return $this->belongsTo(Atencion::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Areawork::class);
    }

    public function entorno(): BelongsTo
    {
        return $this->belongsTo(Entorno::class);
    }

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }
}
