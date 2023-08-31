<?php

namespace Modules\Soporte\Entities;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function setDetalleAttribute($value)
    {
        $this->attributes['detalle'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function orderequipo():HasOne
    {
        return $this->hasOne(Orderequipo::class);
    }

    public function orderdireccion():HasOne
    {
        return $this->hasOne(Orderdireccion::class);
    }

    public function priority():BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }

    public function atencion():BelongsTo
    {
        return $this->belongsTo(Atencion::class);
    }

    public function area():BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function entorno():BelongsTo
    {
        return $this->belongsTo(Entorno::class);
    }

    public function condition():BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }
}
