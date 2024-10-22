<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOUCHER PAGO - N°{{ $cajamovimiento->id }}</title>
</head>
<style>
    @font-face {
        font-family: "Ubuntu";
        font-style: normal;
        font-weight: 300;
        font-display: swap;
        src: url("{{ asset('/fonts/Ubuntu-Light.ttf') }}") format("truetype");
    }

    @font-face {
        font-family: "Ubuntu";
        font-style: italic;
        font-weight: 300;
        font-display: swap;
        src: url("{{ asset('/fonts/Ubuntu-LightItalic.ttf') }}") format("truetype");
    }

    @font-face {
        font-family: "Ubuntu";
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url("{{ asset('/fonts/Ubuntu-Regular.ttf') }}") format("truetype");
    }

    @font-face {
        font-family: "Ubuntu";
        font-style: italic;
        font-weight: 400;
        font-display: swap;
        src: url("{{ asset('/fonts/Ubuntu-Italic.ttf') }}") format("truetype");
    }

    @font-face {
        font-family: "Ubuntu";
        font-style: normal;
        font-weight: 500;
        font-display: swap;
        src: url("{{ asset('/fonts/Ubuntu-Medium.ttf') }}") format("truetype");
    }

    @font-face {
        font-family: "Ubuntu";
        font-style: italic;
        font-weight: 500;
        font-display: swap;
        src: url("{{ asset('/fonts/Ubuntu-MediumItalic.ttf') }}") format("truetype");
    }

    @font-face {
        font-family: "Ubuntu";
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url("{{ asset('/fonts/Ubuntu-Bold.ttf') }}") format("truetype");
    }

    @font-face {
        font-family: "Ubuntu";
        font-style: Italic;
        font-weight: 700;
        font-display: swap;
        src: url("{{ asset('/fonts/Ubuntu-BoldItalic.ttf') }}") format("truetype");
    }

    * {
        font-family: 'Ubuntu';
        font-size: 9px;
    }

    @page {
        margin: 0.35cm 0.5cm 0.35cm 0.35cm;
        padding: 0;
    }

    p {
        margin: 0;
        padding: 0;
    }

    .body {
        width: 100%;
        border-collapse: collapse;
    }

    .border {
        border: 1px solid black;
    }

    .border-2 {
        border: 2px solid black;
    }

    .content-image {
        width: 80%;
        margin: auto;
    }

    .content-image>.image {
        /* border: 1px solid #000; */
        width: auto;
        max-width: 100%;
        height: auto;
        max-height: 2cm;
        margin: auto;
        text-align: center;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .p-1 {
        padding: 2px;
    }

    .p-2 {
        padding: 4px;
    }

    .rounded {
        border-radius: 2rem;
    }

    .mt-1 {
        margin-top: 2px;
    }

    .mt-2 {
        margin-top: 4px;
    }

    .mt-3 {
        margin-top: 6px !important;
    }

    .font-light {
        font-weight: 300;
        font-style: normal;
    }

    .font-normal {
        font-weight: 400;
        font-style: normal;
    }

    .font-medium {
        font-weight: 500;
        font-style: normal;
    }

    .font-bold {
        font-weight: 700;
        font-style: normal;
    }

    .text-8 {
        font-size: 8px !important;
    }

    .text-9 {
        font-size: 9px !important;
    }

    .text-10 {
        font-size: 10px !important;
    }

    .text-start {
        text-align: left !important;
    }

    .text-center {
        text-align: center !important;
    }

    .text-end {
        text-align: right !important;
    }

    .leading-3 {
        line-height: .6rem !important;
    }

    .leading-4 {
        line-height: .7rem !important;
    }

    .leading-5 {
        line-height: .9rem !important;
    }

    .leading-6 {
        line-height: 1rem !important;
    }
</style>

<body class="">
    @if ($cajamovimiento->sucursal->empresa->image || $cajamovimiento->sucursal->empresa->logoimpresion)
        <div class="text-center content-image">
            @if ($cajamovimiento->sucursal->empresa->logoimpresion)
                <img src="{{ getLogoEmpresa($cajamovimiento->sucursal->empresa->logoimpresion, false) }}" alt=""
                    class="image" />
            @else
                <img src="{{ getLogoEmpresa($cajamovimiento->sucursal->empresa->image->url, false) }}" alt=""
                    class="image" />
            @endif
        </div>
    @endif

    <p class="font-bold text-center" style="font-size: 11px;">
        {{ $cajamovimiento->sucursal->empresa->name }}</p>

    <p class="text-center font-medium" style="line-height: 1rem;">
        {{ $cajamovimiento->sucursal->empresa->document }}</p>

    <p class="font-normal text-center leading-3 mt-2" style="font-size: 10px;">
        {{ $cajamovimiento->sucursal->direccion }}
    </p>

    @if ($cajamovimiento->sucursal->ubigeo)
        <p class="font-normal text-center leading-3 mt-2" style="font-size: 10px;">
            {{ $cajamovimiento->sucursal->ubigeo->region }}
            - {{ $cajamovimiento->sucursal->ubigeo->provincia }}
            - {{ $cajamovimiento->sucursal->ubigeo->distrito }}
        </p>
    @endif

    @if (count($cajamovimiento->sucursal->empresa->telephones) > 0)
        <p class="font-normal text-center leading-4 mt-2" style="font-size: 10px;">
            TELÉFONO:
            <span>{{ formatTelefono($cajamovimiento->sucursal->empresa->telephones->first()->phone) }}</span>
        </p>
    @endif

    <p class="text-center font-bold mt-3" style="font-size: 11px; line-height: 1rem;">
        VOUCHER DE PAGO
    </p>
    <p class="text-center font-normal" style="font-size: 10px;">
        N° OPERACIÓN: {{ $cajamovimiento->id }}
    </p>

    <p class="text-center font-bold mt-3" style="font-size: 20px;">
        <small class="font-medium">{{ $cajamovimiento->moneda->simbolo }}</small>
        {{ number_format($cajamovimiento->totalamount, 2, '.', ', ') }}
        <small class="font-medium">{{ $cajamovimiento->moneda->currency }}</small>
    </p>

    @if ($cajamovimiento->tipocambio > 0)
        {{-- <p class="text-colorminicard text-xl font-semibold text-center">
            <small class="text-[10px] font-medium">{{ $cajamovimiento->moneda->simbolo }}</small>
            {{ number_format($cajamovimiento->totalamount, 3, '.', ', ') }}
            <small class="text-[10px] font-medium">{{ $cajamovimiento->moneda->currency }}</small>
        </p> --}}

        <p class="text-center">
            {{ number_format($cajamovimiento->amount, 2, '.', ', ') }}
            <small class="font-medium">
                @if ($cajamovimiento->moneda->isDolar())
                    SOLES
                @else
                    DÓLARES
                @endif
            </small>
        </p>
        <p class="text-center">
            <small class="">TIPO CAMBIO :</small>
            {{ $cajamovimiento->tipocambio }}
        </p>
    @endif


    <div class="body">
        <table class="table rounded mt-3">
            <tbody style="">
                <tr>
                    <td class="font-medium" style="width: 65px;">
                        FECHA </td>
                    <td class="font-bold" style="">
                        : {{ formatDate($cajamovimiento->date, 'DD/MM/Y HH:mm A') }}</td>
                </tr>
                <tr>
                    <td class="font-medium" style="width: 65px;">
                        FORMA PAGO </td>
                    <td class="font-bold" style="">
                        : {{ $cajamovimiento->methodpayment->name }}</td>
                </tr>
                <tr>
                    <td class="font-medium" style="width: 65px;">
                        CONCEPTO </td>
                    <td class="font-bold" style="">
                        : {{ $cajamovimiento->concept->name }}</td>
                </tr>
                <tr>
                    <td class="font-medium" style="width: 65px;">
                        REFERENCIA </td>
                    <td class="font-bold" style="">
                        : {{ $cajamovimiento->referencia }}</td>
                </tr>
                <tr>
                    @if (!empty($cajamovimiento->detalle))
                        <td class="font-medium" style="width: 65px;">
                            DETALLE </td>
                        <td class="font-bold" style="">
                            : {{ $cajamovimiento->detalle }}</td>
                    @endif
                </tr>
            </tbody>
        </table>
    </div>

    <p class="text-center font-normal mt-3">***USUARIO : {{ substr($cajamovimiento->user->name, 0, 6) }} ***</p>

    @if (!empty($cajamovimiento->sucursal->empresa->web))
        <p class="text-center font-normal">{{ $cajamovimiento->sucursal->empresa->web }}</p>
    @endif
</body>

</html>
