<?php

namespace Modules\Soporte\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estate extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['name', 'descripcion', 'finish', 'color', 'default'];
    const DEFAULT = '1';

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function atencions(): BelongsToMany
    {
        return $this->belongsToMany(Atencion::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function procesos(): HasMany
    {
        return $this->hasMany(Proceso::class);
    }

    public function scopeDefault($query)
    {
        return $query->where('default', Self::DEFAULT);
    }

    public function isDefault(): bool
    {
        return $this->default == Self::DEFAULT;
    }

    public function isFinalizado(): bool
    {
        return $this->finish == Self::DEFAULT;
    }
}
