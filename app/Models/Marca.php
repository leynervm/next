<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marca extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = ['name'];
    public $timestamps = false;

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function image()
    {
        return $this->morphOne(Image::class, "imageable");
    }


    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
}
