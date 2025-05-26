<?php

namespace Modules\Soporte\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parte extends Model
{
    use HasFactory;

    // Condicion
    const NUEVO = '0';
    const USADO = '0';
    const AVERIADO = '0';

    // Estado
    const DISPONIBLE = '0';
    const AGOTADO = '1';
}
