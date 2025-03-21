<?php

namespace App\Models;

use App\Models\Guiaitem;
use App\Models\Tvitem;
use App\Models\Almacen;
use App\Models\Especificacion;
use App\Models\Image;
use App\Models\Marca;
use App\Models\Pricetype;
use App\Models\Serie;
use App\Models\Unit;
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
use App\Models\Category;
use App\Models\Subcategory;
use App\Traits\CalcularPrecioVenta;
use App\Traits\ObtenerImage;
use App\Traits\TvitemTrait;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Producto extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;
    use TvitemTrait;

    use CalcularPrecioVenta;
    use ObtenerImage;

    const VER_DETALLES = '1';
    const PUBLICADO = '1';
    const MOSTRAR = '0';
    const OCULTAR = '1';
    const ACTIVE = '1';

    // protected $appends = ['precio_real_compra'];
    // protected $appends = ['precio_lista'];

    // protected static function newFactory()
    // {
    //     return \Database\Factories\ProductoFactory::new();
    // }

    protected $guarded = ['created_at', 'updated_at'];
    protected $casts = [
        'requireserie' => 'integer',
        'publicado' => 'integer',
        'viewespecificaciones' => 'integer',
        'visivility' => 'integer',
        'pricebuy' => 'decimal:2',
        'pricesale' => 'decimal:2',
        'minstock' => 'integer',
        'viewdetalle' => 'integer',
        'novedad' => 'integer',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    // public function precioVenta($pricetype_id, $descuento = 0)
    // {
    //     return $this->calcularPrecioVentaLista($pricetype_id, $descuento);
    // }

    // public function getDescuento()
    // {
    //     return $this->promocions()->descuentos()->disponibles()->availables()->first();
    // }

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

    public function setSkuAttribute($value)
    {
        $this->attributes['sku'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setPartnumberAttribute($value)
    {
        $this->attributes['partnumber'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function almacens(): BelongsToMany
    {
        return $this->belongsToMany(Almacen::class)
            ->withPivot('cantidad')->orderBy('id', 'asc');
        // ->orderByPivot('cantidad', 'desc');
    }

    public function scopeDisponibles($query)
    {
        return $query->wherePivot('cantidad', '>', 0);
    }

    public function series(): HasMany
    {
        return $this->hasMany(Serie::class)->orderBy('serie', 'asc');
    }

    public function seriesdisponibles(): HasMany
    {
        return $this->hasMany(Serie::class)
            ->disponibles()->orderBy('serie', 'asc');
    }

    public function garantiaproductos(): HasMany
    {
        return $this->hasMany(Garantiaproducto::class);
    }

    public function kardexes(): HasMany
    {
        return $this->hasMany(Kardex::class);
    }

    public function tvitems(): HasMany
    {
        return $this->hasMany(Tvitem::class)->orderBy('id', 'asc');
    }

    public function compraitems(): HasMany
    {
        return $this->hasMany(Compraitem::class)->orderBy('id', 'desc');
    }

    public function guiaitems(): HasMany
    {
        return $this->hasMany(Guiaitem::class)->orderBy('created_at', 'desc');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')
            ->orderBy('orden', 'asc')->orderBy('id', 'asc');
    }

    public function imagen(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')
            ->orderBy('orden', 'asc')->orderBy('id', 'asc');
    }

    // public function getImagenSecondaryAttribute()
    // {
    //     return $this->images->get(1);
    // }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withTrashed();
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

    public function especificacions(): BelongsToMany
    {
        return $this->belongsToMany(Especificacion::class)
            ->withPivot('orden')->orderByPivot('orden', 'asc');
    }

    public function detalleproducto(): HasOne
    {
        return $this->hasOne(Detalleproducto::class);
    }

    public function promocions(): HasMany
    {
        return $this->hasMany(Promocion::class);
    }

    public function scopeQueryFilter($query, $filters)
    {
        $query->when($filters['producto_id'] ?? null, function ($query, $producto_id) {
            $query->where('producto_id', $producto_id);
        })->when($filters['category_id'] ?? null, function ($query, $category_id) {
            $query->where('category_id', $category_id);
        })->when($filters['subcategory_id'] ?? null, function ($query, $subcategory_id) {
            $query->where('subcategory_id', $subcategory_id);
        });
    }

    public function scopePublicados($query)
    {
        return $query->where('publicado', self::PUBLICADO);
    }

    public function scopeVisibles($query)
    {
        return $query->where('visivility', self::MOSTRAR);
    }

    public function scopeOcultos($query)
    {
        return $query->where('visivility', self::OCULTAR);
    }

    public function scopeWhereRangoBetween($query, $desde, $hasta)
    {
        return $query->where('pricebuy', '<=', $hasta)
            ->where('pricebuy', '>=', $desde);
    }

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

    public function verDetalles()
    {
        return $this->viewdetalle == Self::VER_DETALLES;
    }

    public function verEspecificaciones()
    {
        return $this->viewespecificaciones == Self::PUBLICADO;
    }

    public function isVisible()
    {
        return $this->visivility == self::MOSTRAR;
    }

    public function isNovedad()
    {
        return $this->novedad == self::ACTIVE;
    }

    public function isOculto()
    {
        return $this->visivility == self::OCULTAR;
    }

    public function isPublicado()
    {
        return $this->publicado == self::PUBLICADO;
    }

    public function isRequiredserie()
    {
        return $this->requireserie == self::PUBLICADO;
    }
}
