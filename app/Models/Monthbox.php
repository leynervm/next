<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Monthbox extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    const REGISTRADO = '0';
    const EN_USO = '1';
    const CERRADO = '2';


    protected $fillable = [
        'month', 'name', 'startdate',
        'expiredate', 'status', 'sucursal_id'
    ];

    public function getExpiredateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i');
    }

    public function openboxes(): HasMany
    {
        return $this->hasMany(Openbox::class);
    }

    public function cajamovimientos(): HasMany
    {
        return $this->hasMany(Cajamovimiento::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function scopeUsando($query, $sucursal_id)
    {
        return $query->where('status', self::EN_USO)
            ->where('sucursal_id', $sucursal_id);
    }

    // public function scopeActive($query, $sucursal_id)
    // {
    //     return $query->where('status', self::EN_USO)
    //         ->where('sucursal_id', $sucursal_id)
    //         ->where('startdate', '<=', now('America/Lima')->format('Y-m-d H:i'))
    //         ->where('expiredate', '>=', now('America/Lima')->format('Y-m-d H:i'));
    // }

    public function isRegister()
    {
        return $this->status == self::REGISTRADO;
    }

    public function isActive()
    {
        return $this->status == self::EN_USO;
    }

    public function isUsing()
    {
        return Carbon::parse(Carbon::parse($this->expiredate)->format('Y-m-d H:i'))
            ->greaterThanOrEqualTo(Carbon::now('America/Lima')->format('Y-m-d H:i'))
            && Carbon::parse(Carbon::parse($this->startdate)->format('Y-m-d H:i'))
            ->lessThanOrEqualTo(Carbon::now('America/Lima')->format('Y-m-d H:i'))
            &&  $this->status == self::EN_USO;
    }

    public function isClose()
    {
        return $this->status ==  self::CERRADO;
    }

    public function isExpired()
    {
        return Carbon::parse($this->expiredate)->lessThanOrEqualTo(now('America/Lima')->format('Y-m-d H:i'));
    }

    public function isDisponible()
    {
        return Carbon::parse($this->expiredate)->greaterThanOrEqualTo(now('America/Lima')->format('Y-m-d H:i'))
            && $this->status == self::REGISTRADO;
    }
}
