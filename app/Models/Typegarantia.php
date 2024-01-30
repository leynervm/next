<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Typegarantia extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['name', 'time', 'datecode'];


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function settDatecodeAttribute($value)
    {
        $this->attributes['datecode'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function garantiaproductos(): HasMany
    {
        return $this->hasMany(Garantiaproducto::class);
    }
}
