<?php

namespace App\Enums;

enum MethodPaymentOnlineEnum: String
{
    case DEPOSITO_BANCARIO = '1';
    case TARJETA_CREDITO = '2';
    case PAYPAL = '3';
    case YAPE = '4';
}
