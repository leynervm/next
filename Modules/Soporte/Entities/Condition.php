<?php

namespace Modules\Soporte\Entities;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Condition extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    // protected static function newFactory()
    // {
    //     return \Modules\Soporte\Database\factories\PruebaFactory::new();
    // }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function centerservices(): HasMany
    {
        return $this->hasMany(Centerservice::class);
    }

    public function equipamentRequire()
    {
        return $this->equipamentrequire === 1;
    }
}
