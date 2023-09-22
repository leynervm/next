<?php

namespace Modules\Almacen\Entities;

use App\Models\Serie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Almacen extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['created_at', 'updated_at'];

    protected static function newFactory()
    {
        return \Modules\Almacen\Database\factories\AlmacenFactory::new();
    }


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class)->withPivot('cantidad');
    }

    public function series(): HasMany
    {
        return $this->hasMany(Serie::class);
    }
   
}
