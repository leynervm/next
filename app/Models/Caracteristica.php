<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caracteristica extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'view', 'filterweb', 'orden'];
    public $timestamps = false;
    const OPTION_ACTIVE = '1';

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function especificacions(): HasMany
    {
        return $this->hasMany(Especificacion::class);
    }

    public function scopeFilterweb($query)
    {
        return $query->where('filterweb', self::OPTION_ACTIVE);
    }

    public function isVisibleSupport()
    {
        return $this->view == self::OPTION_ACTIVE;
    }

    public function isFilterWeb()
    {
        return $this->filterweb == self::OPTION_ACTIVE;
    }
}
