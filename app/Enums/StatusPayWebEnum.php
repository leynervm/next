<?php

namespace App\Enums;

enum StatusPayWebEnum: String
{
    case PENDIENTE = '0';
    case CONFIRMAR_PAGO = '1';
    case PAGO_CONFIRMADO = '2';
}
