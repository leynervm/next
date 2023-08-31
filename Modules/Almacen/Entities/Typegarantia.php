<?php

namespace Modules\Almacen\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Typegarantia extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['created_at', 'updated_at'];


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setTimestringAttribute($value)
    {
        $this->attributes['timestring'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
