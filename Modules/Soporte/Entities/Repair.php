<?php

namespace Modules\Soporte\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;


    protected $guarded = ['created_at', 'updated_at'];
    public $timestamps = false;

    const ACTIVO = '0';
    const INACTIVO = '1';
}
