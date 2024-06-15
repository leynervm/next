<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Typesucursal extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'code', 'descripcion'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function sucursals(): HasMany
    {
        return $this->hasMany(Sucursal::class);
    }
}
