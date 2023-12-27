<?php

namespace App\Models;

use App\Models\Carshoop;
use App\Models\Serie;
use App\Models\Sucursal;
use App\Models\Tvitem;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Almacen\Entities\Compraitem;

class Almacen extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['name', 'default', 'sucursal_id'];
    const DEFAULT = "1";

    protected $casts = [
        'default' => 'integer',
    ];

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

    public function tvitems(): HasMany
    {
        return $this->hasMany(Tvitem::class);
    }

    public function compraitems(): HasMany
    {
        return $this->hasMany(Compraitem::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function isDefault()
    {
        return $this->where('default', $this::DEFAULT );
    }

    public function carshoops(): HasMany
    {
        return $this->hasMany(Carshoop::class);
    }

    // public function disponibles()
    // {
    //     return $this->wherePivot('cantidad', '>', 0);
    // }
}
