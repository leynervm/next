<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nwidart\Modules\Facades\Module;

class Areawork extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;

    public $timestamps = false;
    protected $fillable = ['name', 'addtickets', 'slug'];
    const VISIBLE = '1';

    protected $casts = [
        'addtickets' => 'integer',
    ];


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function employers(): HasMany
    {
        return $this->hasMany(Employer::class);
    }

    public function addTickets()
    {
        return $this->addtickets == self::VISIBLE;
    }

    public function scopeTickets($query)
    {
        return $query->where('addtickets', self::VISIBLE);
    }

    public function atencions(): ?BelongsToMany
    {
        if (!Module::isEnabled('Soporte')) {
            return null;
        }

        return $this->belongsToMany(\Modules\Soporte\Entities\Atencion::class);
    }

    public function entornos(): ?BelongsToMany
    {
        if (!Module::isEnabled('Soporte')) {
            return null;
        }

        return $this->belongsToMany(\Modules\Soporte\Entities\Entorno::class);
    }
}
