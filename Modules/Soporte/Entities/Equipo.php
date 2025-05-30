<?php

namespace Modules\Soporte\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $guarded = ['created_at', 'updated_at'];

    const ACTIVO = '0';
    const INACTIVO = '1';
}
