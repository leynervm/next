<?php

namespace Modules\Marketplace\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipmenttype extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    const RECOJO_TIENDA = '0';
    const ENVIO_DOMICILIO = '1';

    protected $fillable = [
        'name', 'descripcion', 'isenvio',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = trim(mb_strtoupper($value, "UTF-8"));
    }


    public function isEnviodomicilio()
    {
        return $this->isenvio == self::ENVIO_DOMICILIO;
    }

    public function isRecojotienda()
    {
        return $this->isenvio == self::RECOJO_TIENDA;
    }
}
