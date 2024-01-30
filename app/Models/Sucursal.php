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
    protected $fillable = ['name', 'direccion', 'codeanexo', 'default', 'ubigeo_id', 'status', 'empresa_id'];
    const ACTIVO = "0";
    const BAJA = "1";
    const DEFAULT = "1";

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDireccionAttribute($value)
    {
        $this->attributes['direccion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function scopeActives($query)
    {
        return $query->where('status', $this::ACTIVO);
    }

    public function isDefault()
    {
        return $this->default == $this::DEFAULT;
    }

    public function isActive()
    {
        return $this->status == $this::ACTIVO;
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function ubigeo(): BelongsTo
    {
        return $this->belongsTo(Ubigeo::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function almacens(): HasMany
    {
        return $this->hasMany(Almacen::class)->orderBy('name', 'asc');
    }

    public function almacenDefault()
    {
        return $this->almacens()->where('default', $this::DEFAULT);
    }

    public function cajas(): HasMany
    {
        return $this->hasMany(Caja::class)->withTrashed()->orderBy('name', 'asc');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function comprobantes(): HasMany
    {
        return $this->hasMany(Comprobante::class);
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

    public function seriecomprobantes(): BelongsToMany
    {
        return $this->belongsToMany(Seriecomprobante::class)->withPivot('default');
    }

    public function kardexes()
    {
        return $this->hasMany(Kardex::class);
    }
    
}
