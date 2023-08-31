<?php

namespace App\Helpers;

class FormatoPersonalizado
{
    public static function getValue($numero)
    {
        return intval($numero) == floatval($numero) ? intval($numero) : $numero;
    }
}
