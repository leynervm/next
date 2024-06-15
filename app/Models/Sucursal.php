<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Almacen;
use Modules\Almacen\Entities\Compra;
use Modules\Facturacion\Entities\Comprobante;
use Modules\Ventas\Entities\Venta;

class Sucursal extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = [
        'name', 'direccion', 'codeanexo', 'default', 'ubigeo_id',
        'status', 'typesucursal_id', 'empresa_id'
    ];
    const ACTIVO = "0";
    const BAJA = "1";
    const DEFAULT = "1";

    const LIMITE = '2';

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDireccionAttribute($value)
    {
        $this->attributes['direccion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function scopeDefault($query)
    {
        return $query->where('default', self::DEFAULT);
    }

    public function scopeActives($query)
    {
        return $query->where('status', self::ACTIVO);
    }

    public function isDefault()
    {
        return $this->default == self::DEFAULT;
    }

    public function isActive()
    {
        return $this->status == self::ACTIVO;
    }

    public function ubigeo(): BelongsTo
    {
        return $this->belongsTo(Ubigeo::class);
    }

    public function typesucursal(): BelongsTo
    {
        return $this->belongsTo(Typesucursal::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class)->withTrashed();
    }

    public function almacens(): BelongsToMany
    {
        return $this->belongsToMany(Almacen::class)
            ->orderBy('default', 'desc')->orderBy('almacens.id', 'asc');
    }

    public function monthboxes(): HasMany
    {
        return $this->hasMany(Monthbox::class)->orderBy('name', 'asc');
    }

    public function almacenDefault()
    {
        return $this->almacens()->where('default', self::DEFAULT);
    }

    public function boxes(): HasMany
    {
        return $this->hasMany(Box::class)->withTrashed()->orderBy('name', 'asc');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function comprobantes(): HasMany
    {
        return $this->hasMany(Comprobante::class);
    }

    public function openboxes(): HasMany
    {
        return $this->hasMany(Openbox::class);
    }

    public function guias(): HasMany
    {
        return $this->hasMany(Guia::class);
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class);
    }

    public function cajamovimientos()
    {
        return $this->hasMany(Cajamovimiento::class);
    }

    public function seriecomprobantes(): HasMany
    {
        return $this->hasMany(Seriecomprobante::class);
    }

    public function employers(): HasMany
    {
        return $this->hasMany(Employer::class);
    }

    public function kardexes()
    {
        return $this->hasMany(Kardex::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
