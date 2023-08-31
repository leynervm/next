<?php

namespace Modules\Almacen\Entities;

use Cviebrock\EloquentSluggable\Sluggable;

use App\Models\Caracteristica;
use App\Models\Category;
use App\Models\Especificacion;
use App\Models\Image;
use App\Models\Marca;
use App\Models\Serie;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;

    protected $guarded = ['created_at', 'updated_at'];

    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function setSkuAttribute($value)
    {
        $this->attributes['sku'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setModeloAttribute($value)
    {
        $this->attributes['modelo'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function almacens(): BelongsToMany
    {
        return $this->belongsToMany(Almacen::class)->withPivot('user_id', 'cantidad');
    }

    public function disponiblealmacens(): BelongsToMany
    {
        return $this->belongsToMany(Almacen::class)
            ->withPivot('user_id', 'cantidad')
            ->wherePivot('cantidad', '>', 0);
    }


    public function series(): HasMany
    {
        return $this->hasMany(Serie::class,);
    }

    public function seriesdisponibles(): HasMany
    {
        return $this->hasMany(Serie::class)->where('status', 0);
    }

    public function garantiaproductos(): HasMany
    {
        return $this->hasMany(Garantiaproducto::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('id');
    }

    public function defaultImage(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->where('default', 1);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class)->withTrashed();
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class)->withTrashed();
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class)->withTrashed();
    }

    public function almacenarea(): BelongsTo
    {
        return $this->belongsTo(Almacenarea::class)->withTrashed();
    }

    public function estante(): BelongsTo
    {
        return $this->belongsTo(Estante::class)->withTrashed();
    }

    public function especificaciones(): BelongsToMany
    {
        return $this->belongsToMany(Especificacion::class)->withPivot('user_id');
    }

    public function caracteristica(): BelongsTo
    {
        return $this->belongsTo(Caracteristica::class)->withTrashed();
    }

    public function detalleproductos(): HasMany
    {
        return $this->hasMany(Detalleproducto::class);
    }

    public function ofertasdisponibles(): HasMany
    {
        return $this->hasMany(Oferta::class)
            ->whereDate('datestart', '<=', Carbon::now('America/Lima')->format('Y-m-d'))
            ->whereDate('dateexpire', '>=', Carbon::now('America/Lima')->format('Y-m-d'))
            ->where('status', 0);
    }

    public function existStock(): BelongsToMany
    {
        return $this->belongsToMany(Almacen::class)
            ->withPivot('user_id', 'cantidad')
            ->wherePivot('cantidad', '>', 0);
    }
}
