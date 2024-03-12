<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caracteristica extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'view'];
    public $timestamps = false;

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function especificacions(): HasMany
    {
        return $this->hasMany(Especificacion::class);
    }
}
