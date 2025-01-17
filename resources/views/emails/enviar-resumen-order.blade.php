<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <style>
        body,
        body *:not(html):not(style):not(br):not(tr):not(code) {
            box-sizing: border-box;
            font-family: "Ubuntu";
            position: relative;
        }

        body {
            -webkit-text-size-adjust: none;
            color: #718096;
            height: 100%;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            width: 100% !important;
        }

        p,
        ul,
        ol,
        blockquote {
            line-height: 1.4;
            text-align: left;
        }

        a {
            color: #3869d4;
        }

        a img {
            border: none;
        }

        /* Typography */

        h1 {
            color: #3d4852;
            font-size: 18px;
            font-weight: bold;
            margin-top: 0;
            text-align: left;
        }

        h2 {
            font-size: 16px;
            font-weight: bold;
            margin-top: 0;
            text-align: left;
        }

        h3 {
            font-size: 14px;
            font-weight: bold;
            margin-top: 0;
            text-align: left;
        }

        p {
            font-size: 16px;
            line-height: normal;
            margin: 0;
            text-align: left;
        }

        img {
            max-width: 100%;
        }

        .desk {}

        .mobile {
            display: none;
        }

        .wrapper {
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
            margin: 0;
            padding: 0;
            width: 100%;
            background: #ecf1fb;
        }

        .p-box {
            padding: 20px;
        }

        .p-content {
            padding: 15px;
        }

        .content {
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
            margin: 0;
            padding: 0;
            width: 100%;
            max-width: 890px;
            margin: 0 auto;
        }

        .content-box {
            max-width: 890px;
            margin: 0 auto;
        }

        .box-white {
            background: #fff;
        }

        .content-header {
            background: #000;
        }

        .thanks {
            line-height: 22px;
            letter-spacing: 0.2px;
            font-size: 22px;
            font-weight: 500;
        }

        .info-tracking,
        .info-contact {
            line-height: 11px;
            letter-spacing: 0.025rem;
            font-size: 11px;
            font-weight: 500;
            color: #ffffffce;
            display: block;
        }

        .info-contact {
            color: #718096;
        }

        .shippmentype,
        .typepay {
            color: #fff;
            font-size: 12px;
        }

        .typepay {
            font-weight: 700;
            text-transform: uppercase;
        }

        .content-buttons {
            margin: 10px 0;
            width: 100%;
        }

        .p-0 {
            padding: 0;
        }

        .p-1 {
            padding: 2px;
        }

        .p-2 {
            padding: 4px;
        }

        .m-0 {
            margin: 0;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .mt-3 {
            margin-top: 12px;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .mt-5 {
            margin-top: 20px;
        }

        .mt-8 {
            margin-top: 32px;
        }

        .mt-10 {
            margin-top: 40px;
        }

        .leading-3 {
            line-height: 0.85rem;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .text-justify {
            text-align: justify;
        }

        .color-next {
            color: #0fb9b9;
        }

        .rounded {
            border-radius: 1rem;
        }

        .semibold {
            font-weight: 700;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .header {
            padding: 15px;
            text-align: center;
            margin: 0 auto;
            width: 100%;
        }

        .header-secondary {
            background: #000;
        }

        .header a {
            color: #0fb9b9;
            font-size: 19px;
            font-weight: bold;
            text-decoration: none;
        }

        /* Logo */
        .enlace-logo,
        .logo-footer {
            display: block;
            height: 80px;
            width: 100%;
            max-width: 890px;
            color: white !important;
        }

        .logo-footer {
            height: 35px;
        }

        .logo,
        logo-footer {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: contain;
        }

        .social {
            padding: 4px 8px;
        }

        .social-icon {
            text-decoration: none;
        }

        .social-icon img {
            display: block;
            border: 0;
            outline: 0;
            line-height: 100%;
            -ms-interpolation-mode: bicubic;
            width: 26px;
            height: 26px;
        }

        .body {
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .congratulations {
            color: white;
            background: #000;
        }

        .congratulations h5 {
            text-transform: uppercase;
            font-weight: 400;
            letter-spacing: 0.5px;
            font-size: 15px;
            margin: 0;
            padding: 0;
        }

        .congratulations h2 {
            margin: 0;
            padding: 0;
            margin-top: 5px;
            font-weight: 500;
            clear: both;
            font-size: 22px;
            color: #0fb9b9;
        }

        .col-image {
            width: 100px;
        }

        .image-producto {
            display: block;
            outline: 0;
            line-height: 100%;
            width: 100px;
            height: 100px;
            object-position: center;
            border-radius: 0.75rem;
        }

        .description-item {
            padding-left: 10px;
        }

        .description-item p {
            font-size: 12px;
        }

        .description-producto {
            letter-spacing: 0.025em;
            font-size: 16px;
            font-weight: 700;
            color: #001942;
            text-align: left;
            text-transform: uppercase;
            text-align-last: left;
        }

        .body-content-details .price-item {
            letter-spacing: 0.025em;
            font-size: 14px;
            font-weight: 700;
            color: #555;
            text-align: right;
            text-align-last: right;
        }

        .body-subtotal {
            padding: 12px;
            color: #555;
            font-weight: 400;
        }

        .box {
            background: #fff;
            border-radius: 1.5rem;
            padding: 8px;
        }

        .free {
            font-size: 10px;
            background: #25a244;
            padding: 4px 6px;
            border-radius: 0.5rem;
            color: white;
            font-weight: 600;
            letter-spacing: 1px;
            display: inline-block;
        }

        .icons {
            display: block;
            line-height: 100%;
            width: 28px;
            height: 28px;
            max-width: 100%;
        }

        .contact {
            font-size: 13px;
            font-weight: 700;
        }

        /* Subcopy */

        .subcopy {
            border-top: 1px solid #e8e5ef;
            margin-top: 25px;
            padding-top: 25px;
        }

        .subcopy p {
            font-size: 14px;
            display: block;
            width: 100%;
        }

        .subcopy a {
            color: #0fb9b9;
            word-break: break-word;
        }

        /* Footer */

        .footer {
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 890px;
            text-align: center;
            width: 100%;
            max-width: 890px;
            background: #000;
        }

        .footer p {
            color: #fff;
            font-size: 12px;
            text-align: center;
        }

        .footer a {
            color: #0fb9b9;
            text-decoration: underline;
        }

        /* Tables */

        .table table {
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
            margin: 30px auto;
            width: 100%;
        }

        .table th {
            border-bottom: 1px solid #edeff2;
            margin: 0;
            padding-bottom: 8px;
        }

        .table td {
            color: #74787e;
            font-size: 11px;
            line-height: 11px;
            margin: 0;
            padding: 8px 0;
        }

        .table-button {
            width: 100%;
            max-width: 320px;
        }

        /* Buttons */
        .button {
            border-radius: 0.65rem;
            color: #fff !important;
            display: inline-block;
            outline: 0;
            font-weight: 500;
            padding: 3px;
            font-size: 10px;
            letter-spacing: 0.075rem;
            text-transform: uppercase;
            text-decoration: none;
            overflow: hidden;
            transition: 1s ease-in-out 300ms;
        }

        .button-next {
            /* background-color: #0fb9b9;
            border-bottom: 8px solid #0fb9b9;
            border-left: 18px solid #0fb9b9;
            border-right: 18px solid #0fb9b9;
            border-top: 8px solid #0fb9b9; */
        }

        /* .button-next:hover {
            background-color: #0fb9b9;
            border-bottom: 8px solid #0fb9b9;
            border-left: 18px solid #0fb9b9;
            border-right: 18px solid #0fb9b9;
            border-top: 8px solid #0fb9b9;
            box-shadow: 0 0 0 3px #0fb9b9;
        } */

        .button-next {
            letter-spacing: 0.025rem;
            margin: 0 auto;
            display: inline-block;
            text-align: center;
        }

        /* Utilities */
        .break-all {
            word-break: break-all;
        }

        @media only screen and (max-width: 600px) {

            .content,
            .content-box {
                width: 100% !important;
            }

            .enlace-logo {
                height: 50px;
                max-width: 100%;
            }

            .logo-footer {
                height: 30px;
                max-width: 100%;
            }

            .footer {
                width: 100% !important;
            }

            .p-content {
                padding: 5px;
            }

            .rounded {
                border-radius: 0.85rem;
            }

            .congratulations h2 {
                font-size: 14px;
                line-height: normal;
            }

            .thanks {
                font-size: 18px;
                line-height: 18px;
            }

            .info-tracking,
            .info-contact {
                font-size: 10px;
                line-height: 10px;
                text-align: center;
                display: block;
                width: 100%;
            }

            .desk {
                display: none;
            }

            .mobile {
                display: block;
            }

            .mobile>td {
                width: 100%;
                display: block;
            }

            .icons {
                width: 18px;
                height: 18px;
            }

            .price-item {
                font-size: 14px;
            }

            .table-button {
                max-width: 100%;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>
    @php
        $logofooter = $empresa->logofooter;
        $url_logo = getLogoEmpresa($empresa->logo, request()->isSecure() ? true : false);
        if (!empty($logofooter)) {
            $url_logo = $empresa->getLogoFooterURL();
        }
    @endphp

    <table class="content-header p-content" width="100%" border="0" cellspacing="0" cellpadding="0"
        role="presentation">
        <tbody>
            <td>
                <table class="content-box" width="100%" border="0" cellspacing="0" cellpadding="0"
                    role="presentation">
                    <tbody>
                        <tr>
                            <td align="center">
                                <a href="{{ env('APP_URL') }}" class="enlace-logo" title="{{ $url_logo }}">
                                    @if (!empty($url_logo))
                                        <img class="logo" src="{{ $url_logo }}" class="logo"
                                            alt="{{ $url_logo }}">
                                    @else
                                        {{ $empresa->name }}
                                    @endif
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="">
                                <table class="" width="100%" border="0" cellspacing="0" cellpadding="0"
                                    role="presentation">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <h1 class="thanks text-center mt-5 color-next">
                                                    Gracias por tu compra</h1>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p class="info-tracking" style="text-justify: ">
                                                    Su pedido está listo. Estamos trabajando en ello para realizar su
                                                    corespondiente entrega. Para mas información del seguimiento de su
                                                    pedido, haga click en el
                                                    enlace "Seguimiento de pedido".
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <table align="center" class="mt-8 table-button" width="100%"
                                                    border="0" cellpadding="0" cellspacing="0" role="presentation">
                                                    <tbody>
                                                        <tr>
                                                            <td align="center" valign="center" class="rounded p-2"
                                                                bgcolor="#0fb9b9">
                                                                <a class="button button-next"
                                                                    style="display: block; padding: 6px 10px; text-align: center;"
                                                                    href="{{ route('orders.payment', $order) }}"
                                                                    target="_blank" style="color:#fff !important;">
                                                                    Seguimiento del pedido</a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td valign="top" class="p-content">
                                                <table class="mt-8 table-shipment" width="100%" border="0"
                                                    cellpadding="0" cellspacing="0" role="presentation">
                                                    <tbody>
                                                        <tr class="mobile">
                                                            <td style="width: 100%;" class="thanks color-next semibold"
                                                                align="center">
                                                                RESUMEN DEL PAGO
                                                            </td>
                                                        </tr>
                                                        <tr class="mobile">
                                                            <td style="width: 100%;" align="center">
                                                                <p class="typepay mt-3 text-center">
                                                                    @if ($order->transaccion)
                                                                        {{ $order->transaccion->brand }}
                                                                    @endif
                                                                </p>
                                                                <p class="info-tracking text-center mt-2">
                                                                    @if ($order->transaccion)
                                                                        ID OP. :
                                                                        #{{ $order->transaccion->transaction_id }}
                                                                    @endif
                                                                </p>
                                                                <p class="info-tracking text-center mt-2">
                                                                    @if ($order->transaccion)
                                                                        {{ formatDate($order->transaccion->date, "dddd DD \\d\\e MMMM Y") }}
                                                                    @endif
                                                                </p>
                                                                <p class="info-tracking text-center mt-2">
                                                                    @if ($order->transaccion)
                                                                        {{ number_format($order->transaccion->amount, 2, '.', ', ') }}
                                                                        {{ $order->transaccion->currency }}
                                                                    @endif
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr class="mobile mt-8">
                                                            <td style="width: 100%;"
                                                                class="thanks text-center color-next semibold"
                                                                align="center">
                                                                TIPO DE ENTREGA
                                                            </td>
                                                        </tr>
                                                        <tr class="mobile">
                                                            <td style="width: 100%;" align="center">
                                                                <span
                                                                    class="info-tracking shippmentype text-center mt-2">
                                                                    <b>{{ $order->shipmenttype->name }}</b>
                                                                </span>

                                                                @if ($order->shipmenttype->isEnviodomicilio())
                                                                    <p class="info-tracking text-center mt-2">
                                                                        <b>DIRECCCIÓN :</b>
                                                                        {{ $order->direccion->name }}
                                                                    </p>

                                                                    <p class="info-tracking text-center mt-2">
                                                                        <b>REFERENCIA :</b>
                                                                        {{ $order->direccion->referencia }}
                                                                    </p>

                                                                    <p class="info-tracking text-center mt-2">
                                                                        {{ $order->direccion->ubigeo->distrito }},
                                                                        {{ $order->direccion->ubigeo->provincia }},
                                                                        {{ $order->direccion->ubigeo->region }}
                                                                    </p>
                                                                @else
                                                                    <p class="info-tracking text-center mt-2">
                                                                        <b>FECHA RECOJO :</b>
                                                                        {{ formatDate($order->entrega->date, "dddd DD \\d\\e MMMM Y") }}
                                                                    </p>

                                                                    <p class="info-tracking text-center mt-2">
                                                                        <b>TIENDA :</b>
                                                                        {{ $order->entrega->sucursal->name }}
                                                                    </p>
                                                                    <p class="info-tracking text-center mt-2">
                                                                        <b>DIRECCCIÓN :</b>
                                                                        {{ $order->entrega->sucursal->direccion }}
                                                                    </p>

                                                                    <p class="info-tracking text-center mt-2">
                                                                        {{ $order->entrega->sucursal->ubigeo->distrito }},
                                                                        {{ $order->entrega->sucursal->ubigeo->provincia }},
                                                                        {{ $order->entrega->sucursal->ubigeo->region }}
                                                                    </p>
                                                                @endif
                                                            </td>
                                                        </tr>

                                                        <tr class="desk">
                                                            <td class="thanks color-next semibold" align="left">
                                                                RESUMEN DEL PAGO
                                                            </td>
                                                            <td class="thanks text-right color-next semibold"
                                                                align="right">
                                                                TIPO DE ENTREGA
                                                            </td>
                                                        </tr>
                                                        <tr class="desk">
                                                            <td align="left">
                                                                <p class="typepay mt-3">
                                                                    @if ($order->transaccion)
                                                                        {{ $order->transaccion->brand }}
                                                                    @endif
                                                                </p>
                                                                <p class="info-tracking text-left mt-2">
                                                                    @if ($order->transaccion)
                                                                        ID OP. :
                                                                        #{{ $order->transaccion->transaction_id }}
                                                                    @endif
                                                                </p>
                                                                <p class="info-tracking text-left mt-2">
                                                                    @if ($order->transaccion)
                                                                        {{ formatDate($order->transaccion->date, "dddd DD \\d\\e MMMM Y") }}
                                                                    @endif
                                                                </p>
                                                                <p class="info-tracking text-left mt-2">
                                                                    @if ($order->transaccion)
                                                                        {{ number_format($order->transaccion->amount, 2, '.', ', ') }}
                                                                        {{ $order->transaccion->currency }}
                                                                    @endif
                                                                </p>
                                                            </td>
                                                            <td align="right">
                                                                <span
                                                                    class="info-tracking shippmentype text-right mt-2">
                                                                    <b>{{ $order->shipmenttype->name }}</b>
                                                                </span>
                                                                @if ($order->shipmenttype->isEnviodomicilio())
                                                                    <p class="info-tracking text-right mt-2">
                                                                        <b>DIRECCCIÓN :</b>
                                                                        {{ $order->direccion->name }}
                                                                    </p>

                                                                    <p class="info-tracking text-right mt-2">
                                                                        <b>REFERENCIA :</b>
                                                                        {{ $order->direccion->referencia }}
                                                                    </p>

                                                                    <p class="info-tracking text-right mt-2">
                                                                        {{ $order->direccion->ubigeo->distrito }},
                                                                        {{ $order->direccion->ubigeo->provincia }},
                                                                        {{ $order->direccion->ubigeo->region }}
                                                                    </p>
                                                                @else
                                                                    <p class="info-tracking text-right mt-2">
                                                                        <b>FECHA RECOJO :</b>
                                                                        {{ formatDate($order->entrega->date, "dddd DD \\d\\e MMMM Y") }}
                                                                    </p>

                                                                    <p class="info-tracking text-right mt-2">
                                                                        <b>TIENDA :</b>
                                                                        {{ $order->entrega->sucursal->name }}
                                                                    </p>
                                                                    <p class="info-tracking text-right mt-2">
                                                                        <b>DIRECCCIÓN :</b>
                                                                        {{ $order->entrega->sucursal->direccion }}
                                                                    </p>

                                                                    <p class="info-tracking text-right mt-2">
                                                                        {{ $order->entrega->sucursal->ubigeo->distrito }},
                                                                        {{ $order->entrega->sucursal->ubigeo->provincia }},
                                                                        {{ $order->entrega->sucursal->ubigeo->region }}
                                                                    </p>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tbody>
    </table>

    <table class="wrapper p-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tbody>
            <tr>
                <td>
                    <table class="content congratulations rounded" width="100%" cellpadding="0" cellspacing="0"
                        role="presentation">
                        <tbody>
                            <tr>
                                <td class="p-box">
                                    <h5>ORDEN CONFIRMADA <br>#{{ $order->purchase_number }}</h5>
                                    <h2>Hemos registrado tu compra exitosamente.</h2>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr>
                <td class="">
                    <table class="content box-white rounded mt-3" width="100%" cellpadding="0" cellspacing="0"
                        role="presentation">
                        <tbody>
                            <tr>
                                <td>
                                    <table class="p-content" width="100%" cellpadding="0" cellspacing="0"
                                        role="presentation">
                                        <tbody>
                                            <tr>
                                                <td colspan="3">
                                                    <h1 class="m-0 p-0">Resumen de compra</h1>
                                                </td>
                                            </tr>
                                            @foreach ($order->tvitems as $item)
                                                @php
                                                    $image = !empty($item->producto->imagen)
                                                        ? pathURLProductImage($item->producto->imagen->url)
                                                        : null;
                                                @endphp

                                                <tr>
                                                    <td valign="top" class="col-image p-2">
                                                        <img class="image-producto" src="{{ $image }}"
                                                            alt="{{ $image }}">
                                                    </td>
                                                    <td valign="top" align="left" class="description-item p-2">
                                                        <p class="description-producto">
                                                            @if (!empty($item->promocion) && $item->promocion->isCombo() && $item->isGratuito() == false)
                                                                {{ $item->promocion->titulo }}
                                                            @else
                                                                {{ $item->producto->name }}
                                                            @endif
                                                        </p>
                                                        <p class="p-0 m-0 mt-1">CANT:
                                                            {{ decimalOrInteger($item->cantidad) }}
                                                            {{ $item->producto->unit->name }}</p>

                                                        @if (!$item->isGratuito())
                                                            @if (!empty($item->promocion))
                                                                @if ($item->promocion->isCombo())
                                                                    <p class="p-0 m-0 mt-1">
                                                                        PRECIO: {{ $order->moneda->simbolo }}
                                                                        {{ number_format($item->price, 2, '.', ', ') }}
                                                                    </p>
                                                                    <div>
                                                                        <span class="free">COMBO</span>
                                                                    </div>
                                                                @elseif($item->promocion->isLiquidacion())
                                                                    <p class="p-0 m-0 mt-1">
                                                                        PRECIO: {{ $order->moneda->simbolo }}
                                                                        {{ number_format($item->price, 2, '.', ', ') }}
                                                                    </p>
                                                                    <div>
                                                                        <span class="free"
                                                                            style="background: red !important;">LIQUIDACIÓN</span>
                                                                    </div>
                                                                @else
                                                                    <p class="p-0 m-0 mt-1">
                                                                        PRECIO: {{ $order->moneda->simbolo }}
                                                                        {{ number_format($item->price, 2, '.', ', ') }}
                                                                        <span style="display: inline-block;color:red">
                                                                            {{ number_format(getPriceAntes($item->price, $item->promocion->descuento), 2, '.', ', ') }}</span>
                                                                    </p>
                                                                @endif
                                                            @else
                                                                <p class="p-0 m-0 mt-1">
                                                                    PRECIO: {{ $order->moneda->simbolo }}
                                                                    {{ number_format($item->price, 2, '.', ', ') }}</p>
                                                            @endif
                                                        @endif

                                                        <p class="mobile mt-2">
                                                            @if ($item->isGratuito())
                                                                <span class="free">GRATIS</span>
                                                            @else
                                                                <b class="price-item">
                                                                    {{ $order->moneda->simbolo }}
                                                                    {{ number_format($item->total, 2, '.', ', ') }}
                                                                </b>
                                                            @endif
                                                        </p>
                                                    </td>
                                                    <td class="desk p-2" align="right" valign="center"
                                                        style="min-width: 120px;">
                                                        @if ($item->isGratuito())
                                                            <span class="free">GRATIS</span>
                                                        @else
                                                            <b class="price-item">
                                                                {{ $order->moneda->simbolo }}
                                                                {{ number_format($item->total, 2, '.', ', ') }}</b>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            {{-- <tr>
                                                <td valign="top" class="col-image p-2">
                                                    <img class="image-producto"
                                                        src="https://next.net.pe/storage/images/productos/producto_6733c3d2d64a1.jpg"
                                                        alt="">
                                                </td>
                                                <td valign="top" align="left" class="description-item p-2">
                                                    <p class="description-producto">Jacket Light Blue Boys</p>
                                                    <p class="p-0 m-0 mt-1">CANT: 1 UND</p>
                                                    <p class="p-0 m-0 mt-1">PRECIO: S/. 1, 599.00</p>
                                                    <p class="mobile mt-2">
                                                        <b class="price-item">S/. 2, 599.00</b>
                                                    </p>
                                                </td>
                                                <td class="desk p-2" align="right" valign="center">
                                                    <b class="price-item">S/. 2, 599.00</b>
                                                </td>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr>
                <td align="center">
                    <table class="content  box-white rounded mt-3" width="100%" cellpadding="0" cellspacing="0"
                        role="presentation">
                        <tbody>
                            <tr>
                                <td>
                                    <table class="p-box rounded" width="100%" border="0" cellpadding="0"
                                        cellspacing="0" role="presentation">
                                        <tbody>
                                            @if ($order->shipmenttype->isRecojotienda())
                                                <tr>
                                                    <td valign="top" class="">
                                                        Entrega
                                                    </td>
                                                    <td align="end">
                                                        <span class="free">GRATIS</span>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td valign="top" style="width:100%;" class="mt-2">
                                                    Subtotal
                                                </td>
                                                <td align="end" style="min-width:120px;" class="mt-2">
                                                    {{ $order->moneda->simbolo }}
                                                    {{ number_format($order->subtotal, 2, '.', ', ') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top" style="width:100%;" class="color-next mt-2">
                                                    <b>Total</b>
                                                </td>
                                                <td align="end" style="min-width:120px;" class="color-next mt-2">
                                                    <b>{{ $order->moneda->simbolo }}{{ number_format($order->total, 2, '.', ', ') }}</b>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <table class="content rounded" width="100%" cellpadding="0" cellspacing="0"
                        role="presentation">
                        <tbody>
                            <tr>
                                <td class="p-box" align="center">
                                    <h1 class="text-center thanks color-next m-0 p-0">
                                        <b>¿Problemas con tu orden?</b>
                                    </h1>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <table class="content" width="100%" border="0" cellpadding="0" cellspacing="0"
                        role="presentation">
                        <tbody>
                            <tr class="">
                                @if (!empty($empresa->email))
                                    <td style="padding-right:4px">
                                        <table class="box-white rounded p-content" width="100%" border="0"
                                            cellpadding="0" cellspacing="0" role="presentation">
                                            <tbody>
                                                <tr>
                                                    <td class="contact color-next">
                                                        CORREO
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="info-contact"
                                                        style="display:inline-block; text-decoration:none !important;color:#fff !important;">
                                                        {{ $empresa->email }}
                                                    </td>
                                                    <td align="center" valign="center">
                                                        <img src="{{ asset('assets/settings/icon-email.png') }}"
                                                            class="icons"
                                                            alt="{{ asset('assets/settings/icon-email.png') }}">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                @endif

                                @if (count($empresa->telephones) > 0)
                                    <td style="padding-left:4px">
                                        <table class="box-white rounded p-content" width="100%" border="0"
                                            cellpadding="0" cellspacing="0" role="presentation">
                                            <tbody>
                                                <tr>
                                                    <td class="contact color-next">
                                                        TELÉFONO
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="info-contact">
                                                        @foreach ($empresa->telephones as $phone)
                                                            @if (!$loop->first)
                                                                <br>
                                                            @endif
                                                            {{ formatTelefono($phone->phone) }}
                                                        @endforeach
                                                    </td>
                                                    <td align="center" valign="center">
                                                        <img src="{{ asset('assets/settings/icon-phone.png') }}"
                                                            class="icons"
                                                            alt="{{ asset('assets/settings/icon-phone.png') }}">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <table class="footer rounded p-content mt-3" align="center" width="570" cellpadding="0"
                        cellspacing="0" role="presentation">
                        <tbody>
                            <tr>
                                <td align="center">
                                    <table class="" align="center" width="100%" cellpadding="0"
                                        cellspacing="0" role="presentation">
                                        <tbody>
                                            <tr>
                                                <td align="center">
                                                    <a href="#" class="logo-footer">
                                                        @if (!empty($url_logo))
                                                            <img class="logo" src="{{ $url_logo }}"
                                                                class="logo" alt="{{ $empresa->name }}">
                                                        @else
                                                            {{ $empresa->name }}
                                                        @endif
                                                    </a>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <table class="mt-3" align="center" border="0"
                                                        cellpadding="0" cellspacing="0" role="presentation">
                                                        <tbody>
                                                            <tr>
                                                                @if (!empty($empresa->instagram))
                                                                    <td class="social" valign="top">
                                                                        <a class="social-icon"
                                                                            href="{{ $empresa->instagram }}"
                                                                            target="_blank">
                                                                            <img src="{{ asset('assets/settings/icon-instagram.png') }}"
                                                                                alt="{{ asset('assets/settings/icon-instagram.png') }}"></a>
                                                                    </td>
                                                                @endif

                                                                {{-- <td class="social" valign="top">
                                                                    <a class="social-icon"
                                                                        href="https://designmodo.com/postcards"
                                                                        target="_blank">
                                                                        <img src="https://s1.designmodo.com/postcards/e931e54b1bf5c1e0cac743c437478e90.png"
                                                                            alt=""></a>
                                                                </td> --}}
                                                                @if (!empty($empresa->facebook))
                                                                    <td class="social" valign="top">
                                                                        <a class="social-icon"
                                                                            href="{{ $empresa->facebook }}"
                                                                            target="_blank">
                                                                            <img src="{{ asset('assets/settings/icon-facebook.png') }}"
                                                                                alt="{{ asset('assets/settings/icon-facebook.png') }}"></a>
                                                                    </td>
                                                                @endif

                                                                @if (!empty($empresa->tiktok))
                                                                    <td class="social" valign="top">
                                                                        <a class="social-icon"
                                                                            href="{{ $empresa->tiktok }}"
                                                                            target="_blank">
                                                                            <img src="{{ asset('assets/settings/icon-tiktok.png') }}"
                                                                                alt="{{ asset('assets/settings/icon-tiktok.png') }}"></a>

                                                                    </td>
                                                                @endif

                                                                @if (!empty($empresa->youtube))
                                                                    <td class="social" valign="top">
                                                                        <a class="social-icon"
                                                                            href="{{ $empresa->youtube }}"
                                                                            target="_blank">
                                                                            <img src="{{ asset('assets/settings/icon-youtube.png') }}"
                                                                                alt="{{ asset('assets/settings/icon-youtube.png') }}"></a>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <p class="m-0 mt-3">
                                                        © 2012 {{ $empresa->name }} Todos los derechos
                                                        reservados.
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
