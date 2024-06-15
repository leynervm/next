<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'document', 'name', 'estado', 'condicion', 'direccion',
        'urbanizacion', 'email', 'web', 'icono',
        'usuariosol', 'clavesol', 'montoadelanto', 'uselistprice',
        'usepricedolar', 'viewpricedolar', 'tipocambio', 'tipocambioauto',
        'status', 'igv', 'default', 'ubigeo_id', 'cert', 'sendmode', 'passwordcert',
        'clientid', 'clientsecret', 'limitsucursals'
    ];

    const DEFAULT = '1';
    const PRUEBA = '0';
    const PRODUCCION = '1';
    const OPTION_ACTIVE = '1';

    const USER_SOL_PRUEBA = 'MODDATOS';
    const PASSWORD_SOL_PRUEBA = 'MODDATOS';
    const CLIENT_ID_GRE_PRUEBA = 'test-85e5b0ae-255c-4891-a595-0b98c65c9854';
    const CLIENT_SECRET_GRE_PRUEBA = 'test-Hty/M6QshYvPgItX2P0+Kw==';

    // public function getUsepricedolarAttribute($value)
    // {
    //     return (bool) $value; 
    // }

    // public function getViewpricedolarAttribute($value)
    // {
    //     return (bool) $value; 
    // }

    // public function getTipocambioautoAttribute($value)
    // {
    //     return (bool) $value; 
    // }

    public function scopeDefaultEmpresa($query)
    {
        return $query->where('default', 1);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function telephones(): MorphMany
    {
        return $this->morphMany(Telephone::class, 'telephoneable');
    }

    public function ubigeo()
    {
        return $this->belongsTo(Ubigeo::class);
    }

    public function sucursals(): HasMany
    {
        return $this->hasMany(Sucursal::class);
    }

    public function isProduccion()
    {
        return $this->sendmode == self::OPTION_ACTIVE;
    }

    public function isDefault()
    {
        return $this->default == self::DEFAULT;
    }

    public function usarlista()
    {
        return $this->uselistprice == self::OPTION_ACTIVE;
    }

    public function usarDolar()
    {
        return $this->usepricedolar == self::OPTION_ACTIVE;
    }

    public function verDolar()
    {
        return $this->viewpricedolar == self::OPTION_ACTIVE;
    }
}
