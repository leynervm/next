<?php

namespace Modules\Soporte\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estate extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $guarded = ['created_at', 'updated_at'];
    const DEFAULT = '1';

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function atencions()
    {
        return $this->belongsToMany(Atencion::class);
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
