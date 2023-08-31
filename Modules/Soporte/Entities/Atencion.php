<?php

namespace Modules\Soporte\Entities;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atencion extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function atenciontypes()
    {
        return $this->hasMany(Atenciontype::class);
    }

    // public function estates()
    // {
    //     return $this->belongsToMany(Estate::class);
    // }

    public function areas()
    {
        return $this->belongsToMany(Area::class);
    }

    public function entornos()
    {
        return $this->belongsToMany(Entorno::class);
    }

}
