<?php

namespace App\Models;

use App\Traits\CajamovimientoTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Modules\Facturacion\Entities\Comprobante;
use Modules\Ventas\Entities\Venta;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Support\Str;
use Modules\Marketplace\Entities\Order;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SoftDeletes;

    use CajamovimientoTrait;


    const DEFAULT = "1";
    const SUPER_ADMIN = '1';
    const ACTIVO = '0';
    const BAJA = '1';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'document',
        'name',
        'email',
        'password',
        'admin',
        'theme_id',
        'sucursal_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = trim(mb_strtolower($value, "UTF-8"));
    }

    public function openboxes(): HasMany
    {
        return $this->hasMany(Openbox::class);
    }

    public function carshoop()
    {
        return $this->hasMany(Carshoop::class);
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function cajamovimientos()
    {
        return $this->hasMany(Cajamovimiento::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class)->withTrashed();
    }

    public function employer(): HasOne
    {
        return $this->hasOne(Employer::class);
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    public function comprobantes(): HasMany
    {
        return $this->hasMany(Comprobante::class);
    }

    public function kardexes(): HasMany
    {
        return $this->hasMany(Kardex::class);
    }

    public function telephones(): MorphMany
    {
        return $this->morphMany(Telephone::class, 'telephoneable');
    }

    public function direccions(): MorphMany
    {
        return $this->morphMany(Direccion::class, 'direccionable');
    }

    public function scopeWeb($query)
    {
        return $query->whereNull('sucursal_id');
    }

    public function scopeDashboard($query)
    {
        return $query->whereNotNull('sucursal_id');
    }

    public function isAdmin()
    {
        return $this->admin == self::SUPER_ADMIN;
    }

    public function isDashboard()
    {
        return $this->sucursal_id != null;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
