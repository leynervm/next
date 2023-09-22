<?php

namespace App\Helpers;

class FormatoPersonalizado
{
    public static function getValue($numero)
    {
        return intval($numero) == floatval($numero) ? intval($numero) : $numero;
    }

    public static function getExtencionFile($filename)
    {
        $array = explode('.', $filename);
        return $array[count($array) - 1];
    }
}
