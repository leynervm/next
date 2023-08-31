<?php

namespace Modules\Soporte\Entities;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entorno extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function areas()
    {
        return $this->belongsToMany(Area::class);
    }
}
