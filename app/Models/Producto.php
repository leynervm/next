<?php

namespace App\Models;

use App\Models\Carshoop;
use App\Models\Guiaitem;
use App\Models\Tvitem;
use App\Models\Almacen;
use App\Traits\KardexTrait;
use Cviebrock\EloquentSluggable\Sluggable;

use App\Models\Caracteristica;
use App\Models\Especificacion;
use App\Models\Image;
use App\Models\Marca;
use App\Models\Pricetype;
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
use Modules\Almacen\Entities\Compraitem;
use App\Models\Detalleproducto;
use App\Models\Garantiaproducto;
use App\Models\Oferta;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Producto extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;
    use KardexTrait;

    // protected static function newFactory()
    // {
    //     return \Database\Factories\ProductoFactory::new();
    // }

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

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setCodefabricanteAttribute($value)
    {
        $this->attributes['codefabricante'] = trim(mb_strtoupper($value, "UTF-8"));
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
        return $this->belongsToMany(Almacen::class)->withPivot('cantidad')->orderByPivot('cantidad', 'desc',);
    }

    public function disponibles()
    {
        return $this->almacens()->wherePivot('cantidad', '>', 0);
    }

    public function series(): HasMany
    {
        return $this->hasMany(Serie::class)->orderBy('serie', 'asc');
    }

    public function seriesdisponibles(): HasMany
    {
        return $this->hasMany(Serie::class)->where('status', Serie::DISPONIBLE);
    }

    public function garantiaproductos(): HasMany
    {
        return $this->hasMany(Garantiaproducto::class);
    }

    public function tvitems(): HasMany
    {
        return $this->hasMany(Tvitem::class)->orderBy('id', 'asc');
    }

    public function compraitems(): HasMany
    {
        return $this->hasMany(Compraitem::class)->orderBy('created_at', 'desc');
    }

    public function guiaitems(): HasMany
    {
        return $this->hasMany(Guiaitem::class)->orderBy('created_at', 'desc');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')
            ->orderBy('default', 'desc')->orderBy('id', 'asc');
    }

    public function defaultImage(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->where('default', 1);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
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

    public function detalleproducto(): HasOne
    {
        return $this->hasOne(Detalleproducto::class);
    }

    public function carshoops(): HasMany
    {
        return $this->hasMany(Carshoop::class)->orderBy('id', 'asc');
    }

    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class);
    }

    // public function ofertasdisponibles(): HasMany
    // {
    //     return $this->hasMany(Oferta::class)
    //         ->whereDate('datestart', '<=', Carbon::now('America/Lima')->format('Y-m-d'))
    //         ->whereDate('dateexpire', '>=', Carbon::now('America/Lima')->format('Y-m-d'))
    //         ->where('status', 0);
    // }

    public function promocions(): HasMany
    {
        return $this->hasMany(Promocion::class);
    }

    public function ofertasdisponibles(): HasMany
    {
        return $this->hasMany(Promocion::class)->disponibles();
    }

    public function descuentosactivos()
    {
        return $this->hasMany(Promocion::class)->descuentosDisponibles();
    }

    // public function scoopeCombos()
    // {
    //     return $this->hasMany(Promocion::class)->descuentosDisponibles();
    // }

    public function existStock(): BelongsToMany
    {
        return $this->belongsToMany(Almacen::class)
            ->withPivot('cantidad')
            ->wherePivot('cantidad', '>', 0);
    }

    public function pricetypes(): BelongsToMany
    {
        return $this->belongsToMany(Pricetype::class)->withPivot('price');
    }
}
