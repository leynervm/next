<?php

namespace Modules\Soporte\Entities;

use App\Models\Areawork;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atencion extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $guarded = ['created_at', 'updated_at'];
    const OPTION_ACTIVE = '1';

    public $casts = [
        'equipamentrequire' => 'integer'
    ];


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function atenciontypes(): HasMany
    {
        return $this->hasMany(Atenciontype::class);
    }

    // public function estates()
    // {
    //     return $this->belongsToMany(Estate::class);
    // }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function areaworks(): BelongsToMany
    {
        return $this->belongsToMany(Areawork::class)->withPivot('user_id');
    }

    public function entornos(): BelongsToMany
    {
        return $this->belongsToMany(Entorno::class)->withPivot('user_id');
    }

    public function addequipo(): bool
    {
        return $this->equipamentrequire == Self::OPTION_ACTIVE;
    }
}
