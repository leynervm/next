<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>{{ $empresa->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style>
        * {
            font-family: 'Roboto', 'DM Sans', Arial, sans-serif;
        }

        body,
        body *:not(html):not(style):not(br):not(tr):not(code) {
            box-sizing: border-box;
            /* font-family: "Ubuntu"; */
            font-family: 'Roboto', 'DM Sans', Arial, sans-serif;
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
            max-width: 570px;
            margin: 0 auto;
        }

        .content-box {
            max-width: 680px;
            margin: 0 auto;
        }

        .box-white {
            background: #fff;
        }

        .content-header {
            background: #000;
        }

        .content-tracking {
            border-radius: 1rem;
            padding: 1.75rem;
            margin-top: 20px;
        }

        .thanks {
            font-size: 2.5rem;
            line-height: 2.5rem;
            letter-spacing: 0.2px;
            font-weight: 500;
        }

        .titulo {
            text-align: center;
            display: block;
            font-size: 1.75rem;
            line-height: 1.75rem;
            margin-top: 20px;
            letter-spacing: 0.2px;
            font-weight: 500;
        }

        .info-tracking,
        .info-contact {
            line-height: 14px;
            letter-spacing: 0.025rem;
            font-size: 14px;
            line-height: 1.15rem;
            font-weight: 500;
            color: #ffffffce;
            display: block;
        }

        .info-contact {
            color: #718096;
        }

        .button-tracking {
            cursor: pointer;
            background-color: #0fb9b9;
            border-bottom: 0px solid #0fb9b9;
            border-left: 0px solid #0fb9b9;
            border-radius: 0.625rem;
            border-right: 0px solid #0fb9b9;
            border-top: 0px solid #0fb9b9;
            color: #ffffff;
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 500;
            mso-border-alt: none;
            padding-bottom: 10px;
            padding-top: 10px;
            padding-left: 16px;
            padding-right: 16px;
            text-align: center;
            width: auto;
            word-break: keep-all;
            letter-spacing: 0.0625rem;
        }

        .button-tracking:hover {
            background-color: #008b8b;
            border-bottom: 0px solid #008b8b;
            border-left: 0px solid #008b8b;
            border-right: 0px solid #008b8b;
            border-top: 0px solid #008b8b;
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

        /* Logo */
        .enlace-logo,
        .logo-footer {
            display: block;
            height: 80px;
            width: 100%;
            max-width: 570px;
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

        .tracking {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            font-weight: 400;
            text-align: center;
            padding-bottom: 5px;
            padding-top: 5px;
            vertical-align: top;
        }

        .tracking-info {
            color: #aaa;
            direction: ltr;
            font-weight: 700;
            letter-spacing: 0px;
            line-height: 120%;
            text-align: center;
            mso-line-height-alt: 1.599999999999998px;
        }

        .tracking-info p {
            text-align: center;
            font-size: 14px;
        }

        .icon-tracking {
            width: 40px;
            height: 40px;
        }

        .tracking-activo {
            color: #0fb9b9;
        }

        .icons {
            width: 24px;
            height: 24px;
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
            -premailer-width: 570px;
            text-align: center;
            width: 100%;
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

        /* Utilities */
        .break-all {
            word-break: break-all;
        }

        @media only screen and (max-width: 600px) {

            .content,
            .content-box {
                width: 100% !important;
            }

            .content-tracking {
                padding: 0.85rem;
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
                font-size: 1.8rem;
                line-height: 1.8rem;
            }

            .info-tracking,
            .info-contact {
                font-size: 12px;
            }

            .icons {
                width: 16px;
                height: 16px;
            }

            .tracking-info p {
                font-size: 10px;
            }

            .icon-tracking {
                width: 25px;
                height: 25px;
            }

            .titulo {
                font-size: 1.15rem;
                line-height: 1.15rem;
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
        $colspan = count($order->trackings);
        $entregado = false;
    @endphp
    <table class="content-header p-content" style="padding-top:30px" width="100%" border="0" cellspacing="0"
        cellpadding="0">
        <tbody>
            <td>
                <table class="content-box" width="100%" border="0" cellspacing="0" cellpadding="0"
                    role="presentation">
                    <tbody>
                        <tr>
                            <td align="center">
                                <a href="{{ env('APP_URL') }}" class="enlace-logo" title="{{ $url_logo }}">
                                    @if (!empty($url_logo))
                                        <img class="logo" src="{{ $url_logo }}" alt="{{ $empresa->name }}">
                                    @else
                                        {{ $empresa->name }}
                                    @endif
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td align="center">
                                <span class="titulo color-next"> ORDER #{{ $order->purchase_number }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" class="info-tracking text-center mt-2">
                                {{ formatDate($order->date, "dddd DD \\d\\e MMMM Y") }}
                            </td>
                        </tr>

                        <tr>
                            <td class="">
                                <table class="" width="100%" border="0" cellspacing="0" cellpadding="0"
                                    role="presentation">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table width="100%" class="content-box box-white content-tracking">
                                                    <tr>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0"
                                                                cellpadding="0" role="presentation">
                                                                <tr>
                                                                    <td>
                                                                        <h1 class="thanks text-center color-next">Se
                                                                            actualizó el estado de tu pedido</h1>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0"
                                                                cellpadding="0" role="presentation">
                                                                <tr>
                                                                    @foreach ($order->trackings as $tracking)
                                                                        @php
                                                                            $url_icon = asset(
                                                                                'assets/settings/icon-default-tracking-color.png',
                                                                            );
                                                                            if ($tracking->trackingstate->isDefault()) {
                                                                                $url_icon = asset(
                                                                                    'assets/settings/icon-confirmed-color.png',
                                                                                );
                                                                            }
                                                                            if (
                                                                                $tracking->trackingstate->isFinalizado()
                                                                            ) {
                                                                                $entregado = true;
                                                                                $url_icon = asset(
                                                                                    'assets/settings/icon-entregado-color.png',
                                                                                );
                                                                            }
                                                                        @endphp

                                                                        @if (!$loop->first)
                                                                            <td style="width:80px;">
                                                                                <span
                                                                                    style="display:block;height:1px; background:#0fb9b9;width:100%;"></span>
                                                                            </td>
                                                                        @endif

                                                                        <td class="tracking">
                                                                            <table class="" width="100%"
                                                                                border="0" cellpadding="0"
                                                                                cellspacing="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <img class="icon-tracking"
                                                                                                src="{{ $url_icon }}"
                                                                                                alt="ICONO">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div
                                                                                                class="tracking-info tracking-activo">
                                                                                                <p style="margin: 0;">
                                                                                                    {{ $tracking->trackingstate->name }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    @endforeach

                                                                    @if (!$entregado)
                                                                        <td style="width:80px;">
                                                                            <span
                                                                                style="display:block;height:1px; background:#ccc;width:100%;"></span>
                                                                        </td>
                                                                        <td class="tracking">
                                                                            <table class="" width="100%"
                                                                                border="0" cellpadding="0"
                                                                                cellspacing="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <img class="icon-tracking"
                                                                                                src="{{ asset('assets/settings/icon-entregado.png') }}"
                                                                                                alt="ICONO">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div class="tracking-info">
                                                                                                <p style="margin: 0;">
                                                                                                    ENTREGADO
                                                                                                </p>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    @endif
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                                    role="presentation">
                                                    <tr>
                                                        <td align="center">
                                                            <div class="mt-8" align="center">
                                                                <a href="{{ route('orders.payment', $order) }}"
                                                                    target="_blank"
                                                                    style="color:#ffffff;text-decoration:none;">
                                                                    <span class="button-tracking">
                                                                        <span
                                                                            style="word-break: break-word; line-height: 1.15rem;">
                                                                            SEGUIMIENTO DEL PEDIDO
                                                                        </span>
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td valign="top" class="p-content">
                                                <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                                    role="presentation">
                                                    <tr>
                                                        <td align="center">
                                                            <span class="titulo color-next">
                                                                {{ $order->shipmenttype->name }}</span>

                                                            @if ($order->shipmenttype->isEnviodomicilio())
                                                                <p class="info-tracking text-center mt-2">
                                                                    {{ $order->direccion->name }}
                                                                </p>

                                                                @if (!empty($order->direccion->referencia))
                                                                    <p class="info-tracking text-center">
                                                                        {{ $order->direccion->referencia }}</p>
                                                                @endif

                                                                <p class="info-tracking text-center mt-2">
                                                                    {{ $order->direccion->ubigeo->distrito }},
                                                                    {{ $order->direccion->ubigeo->provincia }},
                                                                    {{ $order->direccion->ubigeo->region }}
                                                                </p>
                                                            @else
                                                                <p class="info-tracking text-center mt-2">
                                                                    TIENDA : {{ $order->entrega->sucursal->name }}</p>

                                                                <p class="info-tracking text-center">
                                                                    FECHA :
                                                                    {{ formatDate($order->entrega->date, "dddd DD \\d\\e MMMM Y") }}
                                                                </p>
                                                            @endif

                                                        </td>
                                                    </tr>
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

    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tbody>
            <tr>
                <td>
                    <table class="content-box rounded" width="100%" cellpadding="0" cellspacing="0"
                        role="presentation">
                        <tbody>
                            <tr>
                                <td class="p-box" align="center">
                                    <h1 class="titulo color-next m-0 p-0 semibold">
                                        ¿Problemas con tu orden?
                                    </h1>
                                </td>
                            </tr>

                            @if (!empty($empresa->email))
                                <tr>
                                    <td>
                                        <table class="box-white rounded p-content" style="padding:0.85rem;"
                                            width="100%" border="0" cellpadding="0" cellspacing="0"
                                            role="presentation">
                                            <tbody>
                                                <tr>
                                                    <td class="contact color-next" colspan="2">
                                                        CORREO
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="info-contact">
                                                        <span
                                                            style="display:inline-block; text-decoration:none !important;">
                                                            {{ $empresa->email }}
                                                        </span>
                                                    </td>
                                                    <td class="icons" align="center" valign="center">
                                                        <img src="{{ asset('assets/settings/icon-email.png') }}"
                                                            class="" alt="ICONO DEL TELEFONOS">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif

                            @if (count($empresa->telephones) > 0)
                                <tr>
                                    <td>
                                        <table class="box-white rounded p-content mt-5" width="100%" border="0"
                                            cellpadding="0" cellspacing="0" role="presentation">
                                            <tbody>
                                                <tr>
                                                    <td class="contact color-next" colspan="2">
                                                        TELÉFONO
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="info-contact">
                                                        @foreach ($empresa->telephones as $phone)
                                                            @if (!$loop->first)
                                                                <br>
                                                            @endif

                                                            <span style="display:inline-block;">
                                                                {{ formatTelefono($phone->phone) }}
                                                            </span>
                                                        @endforeach
                                                    </td>
                                                    <td class="icons" align="center" valign="center">
                                                        <img src="{{ asset('assets/settings/icon-phone.png') }}"
                                                            class="" alt="ICONO DE TELÉFONOS">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <table class="footer content-box rounded p-content mt-3" align="center" width="570"
                        cellpadding="0" cellspacing="0" role="presentation">
                        <tbody>
                            <tr>
                                <td align="center">
                                    <table class="" align="center" width="100%" cellpadding="0"
                                        cellspacing="0" role="presentation">
                                        <tbody>
                                            @if (!empty($url_logo))
                                                <tr>
                                                    <td align="center">
                                                        <a href="#" class="logo-footer">
                                                            <img class="logo" src="{{ $url_logo }}"
                                                                alt="{{ $empresa->name }}">
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td>
                                                    <table class="mt-3" align="center" border="0"
                                                        cellpadding="0" cellspacing="0" role="presentation">
                                                        <tbody>
                                                            <tr>
                                                                @if (!empty($empresa->whatsapp))
                                                                    <td class="social" valign="top">
                                                                        <a class="social-icon"
                                                                            href="{{ $empresa->whatsapp }}"
                                                                            target="_blank">
                                                                            <img src="{{ asset('assets/settings/icon-whatsapp.png') }}"
                                                                                alt="icon-whatsapp">
                                                                        </a>
                                                                    </td>
                                                                @endif

                                                                @if (!empty($empresa->facebook))
                                                                    <td class="social" valign="top">
                                                                        <a class="social-icon"
                                                                            href="{{ $empresa->facebook }}"
                                                                            target="_blank">
                                                                            <img src="{{ asset('assets/settings/icon-facebook.png') }}"
                                                                                alt="icon-facebook">
                                                                        </a>
                                                                    </td>
                                                                @endif

                                                                @if (!empty($empresa->instagram))
                                                                    <td class="social" valign="top">
                                                                        <a class="social-icon"
                                                                            href="{{ $empresa->instagram }}"
                                                                            target="_blank">
                                                                            <img src="{{ asset('assets/settings/icon-instagram.png') }}"
                                                                                alt="icon-instagram">
                                                                        </a>
                                                                    </td>
                                                                @endif

                                                                @if (!empty($empresa->tiktok))
                                                                    <td class="social" valign="top">
                                                                        <a class="social-icon"
                                                                            href="{{ $empresa->tiktok }}"
                                                                            target="_blank">
                                                                            <img src="{{ asset('assets/settings/icon-tiktok.png') }}"
                                                                                alt="icon-tiktok">
                                                                        </a>
                                                                    </td>
                                                                @endif

                                                                @if (!empty($empresa->youtube))
                                                                    <td class="social" valign="top">
                                                                        <a class="social-icon"
                                                                            href="{{ $empresa->youtube }}"
                                                                            target="_blank">
                                                                            <img src="{{ asset('assets/settings/icon-youtube.png') }}"
                                                                                alt="icon-youtube">
                                                                        </a>
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
                                                        © 2025 NEXT TECHNOLOGIES E.I.R.L. Todos los derechos reservados.
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
