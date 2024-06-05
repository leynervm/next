<?php

namespace App\Models;

use App\Traits\CajamovimientoTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Openbox extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CajamovimientoTrait;

    const ACTIVO = '0';
    const INACTIVO = '1';

    protected $guarded = ['created_at', 'updated_at'];

    public function getExpiredateAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function box(): BelongsTo
    {
        return $this->belongsTo(Box::class)->withTrashed();
    }

    public function monthbox(): BelongsTo
    {
        return $this->belongsTo(Monthbox::class)->withTrashed();
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class)->withTrashed();
    }

    public function cajamovimiento(): MorphOne
    {
        return $this->morphOne(cajamovimiento::class, 'cajamovimientable');
    }

    public function cajamovimientos(): HasMany
    {
        return $this->hasMany(Cajamovimiento::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', self::ACTIVO)->whereNull('closedate');
    }

    public function scopeMybox($query, $sucursal_id)
    {
        return $query->open()->where('user_id', auth()->user()->id)
            ->where('sucursal_id', $sucursal_id)
            ->orderBy('startdate', 'desc');
    }

    public function isClosed()
    {
        return !is_null($this->closedate);
    }

    public function isExpired()
    {
        return $this->status == 0 && now('America/Lima') >= $this->expiredate;
    }

    public function isActivo()
    {
        return $this->status == 0 && now('America/Lima') <= $this->expiredate;
    }

    public function isUsing()
    {
        return $this->user_id == auth()->user()->id;
    }
}
