<?php

namespace Modules\Soporte\Entities;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Condition extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $guarded = ['created_at', 'updated_at'];
    const OPTION_ACTIVE = '1';

    public $casts = [
        'flagpagable' => 'integer'
    ];

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

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function pagable(): bool
    {
        return $this->flagpagable == Self::OPTION_ACTIVE;
    }
}
