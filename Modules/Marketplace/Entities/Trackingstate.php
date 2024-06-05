<?php

namespace Modules\Marketplace\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trackingstate extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    const DEFAULT = '1';
    const FINISH = '1';


    protected $fillable = [
        'name', 'icono', 'finish', 'background', 'default'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setBackgroundAttribute($value)
    {
        $this->attributes['background'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function scopeDefault($query)
    {
        return $query->where('default', self::DEFAULT);
    }

    public function isDefault()
    {
        return $this->default == self::DEFAULT;
    }

    public function isFinalizado()
    {
        return $this->finish == self::FINISH;
    }
}
