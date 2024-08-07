<?php

namespace App\Enums;

enum MethodPaymentOnlineEnum: String
{
    case TARJETA_CREDITO = '1';
    case TARJETA_DEBITO = '2';
    case DEPOSITO_BANCARIO = '3';
    case YAPE = '4';
    case PLIN = '5';
    case PAYPAL = '6';
}
