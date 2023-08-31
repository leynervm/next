<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Soporte\Entities\Atencion;
use Modules\Soporte\Entities\Entorno;

class Area extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function atencions()
    {
        return $this->belongsToMany(Atencion::class);
    }

    public function entornos()
    {
        return $this->belongsToMany(Entorno::class);
    }

}
