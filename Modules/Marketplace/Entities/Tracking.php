<?php

namespace Modules\Marketplace\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tracking extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'date', 'descripcion', 'trackingstate_id', 'ventaonline_id', 'user_id'
    ];

    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function trackingstate(): BelongsTo
    {
        return $this->belongsTo(Trackingstate::class)->withTrashed();
    }

    public function scopeFinalizados($query)
    {
        return $query->whereHas('trackingstate', function ($query) {
            $query->where('finish', Trackingstate::FINISH);
        });
    }
}
