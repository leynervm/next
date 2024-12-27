<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Empresa extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'document',
        'name',
        'estado',
        'condicion',
        'direccion',
        'urbanizacion',
        'email',
        'web',
        'icono',
        'logofooter',
        'logoimpresion',
        'whatsapp',
        'facebook',
        'youtube',
        'instagram',
        'tiktok',
        'usuariosol',
        'clavesol',
        'montoadelanto',
        'uselistprice',
        'viewpriceantes',
        'viewlogomarca',
        'viewtextopromocion',
        'viewespecificaciones',
        'viewalmacens',
        'viewalmacensdetalle',
        'viewproductosweb',
        'generatesku',
        'usemarkagua',
        'markagua',
        'alignmark',
        'widthmark',
        'heightmark',
        'usepricedolar',
        'viewpricedolar',
        'tipocambio',
        'tipocambioauto',
        'status',
        'igv',
        'default',
        'ubigeo_id',
        'cert',
        'sendmode',
        'passwordcert',
        'clientid',
        'clientsecret',
        'limitsucursals',
        'afectacionigv',
        'textnovedad'
    ];

    const DEFAULT = '1';
    const PRUEBA = '0';
    const PRODUCCION = '1';
    const OPTION_ACTIVE = '1';

    const TITLE_PROMO_DEFAULT = '0';
    const TITLE_PROMOCION = '1';
    const TITLE_PROMO_LIQUIDACION = '2';

    const USER_SOL_PRUEBA = 'MODDATOS';
    const PASSWORD_SOL_PRUEBA = 'MODDATOS';
    const CLIENT_ID_GRE_PRUEBA = 'test-85e5b0ae-255c-4891-a595-0b98c65c9854';
    const CLIENT_SECRET_GRE_PRUEBA = 'test-Hty/M6QshYvPgItX2P0+Kw==';
    const PASSWORD_CERT_PRUEBA = '12345678';
    const URL_CERT_PRUEBA = '12345678';

    // public function getViewpriceantesAttribute($value)
    // {
    //     return (bool) $value; 
    // }

    // public function getViewlogomarcaAttribute($value)
    // {
    //     return (bool) $value; 
    // }

    // public function getTipocambioautoAttribute($value)
    // {
    //     return (bool) $value; 
    // }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = trim(mb_strtolower($value, "UTF-8"));
    }

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

    public function isAfectacionIGV()
    {
        return $this->afectacionigv == self::OPTION_ACTIVE;
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

    public function usarMarkagua()
    {
        return $this->usemarkagua == self::OPTION_ACTIVE;
    }

    public function verEspecificaciones()
    {
        return $this->viewespecificaciones == Self::OPTION_ACTIVE;
    }

    public function verDolar()
    {
        return $this->viewpricedolar == self::OPTION_ACTIVE;
    }

    public function verOldprice()
    {
        return $this->viewpriceantes == self::OPTION_ACTIVE;
    }

    public function verLogomarca()
    {
        return $this->viewlogomarca == self::OPTION_ACTIVE;
    }

    public function isTitleDefault()
    {
        return $this->viewtextopromocion == self::TITLE_PROMO_DEFAULT;
    }

    public function isTitlePromocion()
    {
        return $this->viewtextopromocion == self::TITLE_PROMOCION;
    }

    public function isTitleLiquidacion()
    {
        return $this->viewtextopromocion == self::TITLE_PROMO_LIQUIDACION;
    }

    public function viewAlmacens()
    {
        return $this->viewalmacens == self::OPTION_ACTIVE;
    }
    
    public function viewAlmacensDetalle()
    {
        return $this->viewalmacensdetalle == self::OPTION_ACTIVE;
    }

    public function viewOnlyDisponibles()
    {
        return $this->viewproductosweb == self::OPTION_ACTIVE;
    }

    public function autogenerateSku()
    {
        return $this->generatesku == self::OPTION_ACTIVE;
    }

    public function getMarkAguaURL()
    {
        return asset('storage/images/company/' . $this->markagua);
    }

    public function getIconoURL()
    {
        return asset('storage/images/company/' . $this->icono);
    }

    public function getLogoFooterURL()
    {
        return asset('storage/images/company/' . $this->logofooter);
    }

    public function getLogoImpresionURL()
    {
        return asset('storage/images/company/' . $this->logoimpresion);
    }
}
