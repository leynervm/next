<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Claimbook extends Model
{
    use HasFactory;
    use HasUuids;

    public $timestamps = false;
    protected $fillable = [
        'date',
        'serie',
        'correlativo',
        'document',
        'name',
        'direccion',
        'email',
        'is_menor_edad',
        'document_apoderado',
        'name_apoderado',
        'direccion_apoderado',
        'telefono_apoderado',
        'channelsale',
        'biencontratado',
        'descripcion_producto_servicio',
        'telefono',
        'tipo_reclamo',
        'detalle_reclamo',
        'pedido',
        'sucursal_id'
    ];


    const TIENDA_WEB = 'TIENDA WEB';
    const TIENDA_FISICA = 'TIENDA FISICA';
    const MENOR_EDAD = '1';


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }


    public function setNameApoderadoAttribute($value)
    {
        $this->attributes['name_apoderado'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDireccionAttribute($value)
    {
        $this->attributes['direccion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDescripcionProductoServicioAttribute($value)
    {
        $this->attributes['descripcion_producto_servicio'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDetalleReclamoAttribute($value)
    {
        $this->attributes['detalle_reclamo'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDireccionApoderadoAttribute($value)
    {
        $this->attributes['direccion_apoderado'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    // public function telephones(): MorphMany
    // {
    //     return $this->morphMany(Telephone::class, 'telephoneable');
    // }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class)->withTrashed();
    }

    public function isMenorEdad()
    {
        return $this->is_menor_edad == self::MENOR_EDAD;
    }
}
