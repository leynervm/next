<?php

namespace Modules\Soporte\Entities;

use App\Models\Areawork;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entorno extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['name', 'requiredirection', 'default'];
    const ACTIVE_OPTION = '1';

    public $casts = [
        'requiredirection' => 'integer'
    ];


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function areaworks(): BelongsToMany
    {
        return $this->belongsToMany(Areawork::class)->withPivot('user_id');
    }

    public function atencions(): BelongsToMany
    {
        return $this->belongsToMany(Atencion::class)->withPivot('user_id');
    }

    public function isDefault()
    {
        return $this->default == Self::ACTIVE_OPTION;
    }

    public function isDOA()
    {
        return $this->requiredirection == Self::ACTIVE_OPTION;
    }
}
