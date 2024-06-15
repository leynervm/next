<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acceso extends Model
{
    use HasFactory;


    const ACTIVO = '0';
    const SUSPENDIDO = '1';
    const LIMIT_SUCURSALS = '2';

    public $timestamps = false;

    protected $fillable = [
        'access', 'limitsucursals', 'validatemail', 'dominio', 'descripcion'
    ];
}
