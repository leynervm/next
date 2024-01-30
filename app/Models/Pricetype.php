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
    use SoftDeletes;

    public $timestamps = false;

    const DEFAULT = '1';

    protected $fillable = [
        'name', 'rounded', 'decimals', 'default',
        'web', 'defaultlogin', 'temporal', 'startdate', 'expiredate'
    ];

    public function getRoundedAttribute($value)
    {
        return (int) $value;
    }

    public function getDefaultAttribute($value)
    {
        return (int) $value;
    }

    public function getWebAttribute($value)
    {
        return (int) $value;
    }

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

    public function scopeDefaultPricetype($query)
    {
        return $query->where('default', self::DEFAULT);
    }

    public function scopeDefault($query)
    {
        return $query->where('default', self::DEFAULT);
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class)->withPivot('price');
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }
}
