<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pricetype extends Model
{
    use HasFactory;

    public $timestamps = false;

    const DEFAULT = '1';
    const ACTIVO = '0';
    const DISABLED = '1';
    const IS_TEMPORAL = '1';

    protected $fillable = [
        'name',
        'rounded',
        'decimals',
        'default',
        'web',
        'status',
        'defaultlogin',
        'temporal',
        'startdate',
        'expiredate',
        'campo_table'
    ];

    public function getRoundedAttribute($value)
    {
        return (int) $value;
    }

    public function getDefaultAttribute($value)
    {
        return (int) $value;
    }

    // public function getWebAttribute($value)
    // {
    //     return (int) $value;
    // }

    public function getDefaultloginAttribute($value)
    {
        return (int) $value;
    }

    public function getTemporalAttribute($value)
    {
        return (int) $value;
    }

    public function getStartdateAttribute($value)
    {
        return !is_null($value) ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function getExpiredateAttribute($value)
    {
        return !is_null($value) ? Carbon::parse($value)->format('Y-m-d') : null;
    }


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function rangos(): BelongsToMany
    {
        return $this->belongsToMany(Rango::class)
            ->withPivot('id', 'ganancia')->orderBy('ganancia', 'desc');
    }

    public function scopeActivos($query)
    {
        return $query->where('status', self::ACTIVO);
    }

    //web: Lista por defecto en panel administrativo
    public function scopeDefault($query)
    {
        return $query->where('default', self::DEFAULT);
    }

    //web: Lista por defecto en tienda web sin Logearse
    // public function scopeWeb($query)
    // {
    //     return $query->where('web', self::DEFAULT);
    // }

    //defaultlogin: Lista por defecto en tienda web despues Logearse
    public function scopeLogin($query)
    {
        return $query->where('defaultlogin', self::DEFAULT);
    }

    public function isActivo()
    {
        return $this->status == self::ACTIVO;
    }

    public function isInactivo()
    {
        return $this->status == self::DISABLED;
    }

    public function isDefault()
    {
        return $this->default == self::DEFAULT;
    }

    public function isDefaultLogin()
    {
        return $this->defaultlogin == self::DEFAULT;
    }

    public function isTemporal()
    {
        return $this->temporal == self::IS_TEMPORAL;
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }
}
