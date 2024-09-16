<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ $venta->seriecomprobante->typecomprobante->descripcion }} - {{ $venta->seriecompleta }}
    </title>
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
        font-family: 'Ubuntu' !important;
        font-size: 9px;
    }

    @page {
        margin: 0.25cm;
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

    .image {
        /* border: 1px solid #000; */
        width: 80%;
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
    @if ($venta->sucursal->empresa->image)
        <div class="text-center">
            <img src="{{ $venta->sucursal->empresa->image->getLogoEmpresa() }}" alt="" class="image" />
        </div>
    @endif

    <p class="font-bold text-center" style="font-size: 11px;">
        {{ $venta->sucursal->empresa->name }}</p>

    <p class="text-center font-medium" style="line-height: 1rem;">
        {{ $venta->sucursal->empresa->document }}</p>

    <p class="font-normal text-center leading-3 mt-2" style="font-size: 10px;">
        {{ $venta->sucursal->direccion }}
    </p>

    @if ($venta->sucursal->ubigeo)
        <p class="font-normal text-center leading-3 mt-2" style="font-size: 10px;">
            {{ $venta->sucursal->ubigeo->region }}
            - {{ $venta->sucursal->ubigeo->provincia }}
            - {{ $venta->sucursal->ubigeo->distrito }}
        </p>
    @endif

    @if (count($venta->sucursal->empresa->telephones) > 0)
        <p class="font-normal text-center leading-4 mt-2" style="font-size: 10px;">
            TELÉFONO:
            <span>{{ formatTelefono($venta->sucursal->empresa->telephones->first()->phone) }}</span>
        </p>
    @endif

    <p class="text-center font-bold mt-3" style="font-size: 11px;">
        {{ $venta->seriecomprobante->typecomprobante->descripcion }}
    </p>
    <p class="text-center font-bold" style="font-size: 11px; line-height: 1rem;">{{ $venta->seriecompleta }}</p>

    <div class="body">
        <table class="table rounded mt-3">
            <tbody style="">
                <tr>
                    <td class="font-medium" style="width: 50px;text-align: left; vertical-align:middle;">
                        FECHA </td>
                    <td class="font-bold" style="text-align: left; vertical-align:middle;">
                        : {{ formatDate($venta->date, 'DD/MM/Y HH:mm A') }}</td>
                </tr>
                <tr>
                    <td class="font-medium" style="width: 50px;text-align: left; vertical-align:middle;">
                        CLIENTE </td>
                    <td class="font-bold" style="text-align: left; vertical-align:middle;">
                        : {{ $venta->client->name }}</td>
                </tr>
                <tr>
                    <td class="font-medium" style="width: 50px;text-align: left; vertical-align:middle">
                        DIRECCIÓN </td>
                    <td class="font-bold" style="text-align: left; vertical-align:middle;">
                        : {{ $venta->direccion }}</td>
                </tr>
                <tr>
                    <td class="font-medium" style="width: 50px;text-align: left; vertical-align:middle">
                        MONEDA </td>
                    <td class="font-bold" style="text-align: left; vertical-align:middle;">
                        : {{ $venta->moneda->currency }}</td>
                </tr>
            </tbody>
        </table>

        @if (count($venta->tvitems) > 0)
            <table class="table mt-3">
                <thead style="">
                    <tr>
                        <th class="font-bold text-start"
                            style="border-bottom:1px solid black;border-top:1px solid black; ">
                            DESCRIPCIÓN</th>
                        <th class="font-bold text-end"
                            style="border-bottom:1px solid black;border-top:1px solid black; ">
                            P. UNIT.</th>
                        <th class="font-bold text-end"
                            style="border-bottom:1px solid black;border-top:1px solid black; ">
                            IMPORTE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venta->tvitems as $item)
                        <tr>
                            <td class="p-1 text-9 leading-5 text-start" style="width:100%">
                                <p>
                                    <small class="font-medium">[
                                        {{ formatDecimalOrInteger($item->cantidad) }}
                                        {{ $item->producto->unit->code }}
                                        ]</small>
                                    {{ $item->producto->name }}
                                </p>
                            </td>
                            <td class="p-1 text-9 text-end" style="width: 45px;">
                                {{ number_format($item->price + $item->igv, 2, '.', ', ') }}</td>
                            <td class="p-1 text-9 text-end" style="width: 45px;">
                                {{ number_format($item->total, 2, '.', ', ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="table">
                <thead>
                    <tr>
                        <td class="font-bold p-1 text-end" style="">
                            SUBTOTAL :
                        </td>
                        <td class="font-bold p-1 text-end" style="width: 70px;">
                            {{ $venta->moneda->simbolo }}
                            {{ number_format($venta->exonerado + $venta->gravado + $venta->igv + $venta->gratuito + $venta->igvgratuito + $venta->descuento, 2, '.', ', ') }}
                        </td>
                    </tr>

                    @if ($venta->igv > 0 || $venta->igvgratuito > 0)
                        <tr>
                            <td class="font-bold p-1 text-end">
                                IGV : </td>
                            <td class="font-bold p-1 text-end" style="width: 90px;">
                                {{ $venta->moneda->simbolo }}
                                {{ number_format($venta->igv + $venta->igvgratuito, 2, '.', ', ') }}
                            </td>
                        </tr>
                    @endif

                    @if ($venta->gratuito > 0)
                        <tr>
                            <td class="font-bold p-1 text-end">
                                GRATUITO : </td>
                            <td class="font-bold p-1 text-end" style="width: 90px;">
                                {{ $venta->moneda->simbolo }}
                                {{ number_format($venta->gratuito, 2, '.', ', ') }}</td>
                        </tr>
                    @endif


                    <tr>
                        <td class="font-bold p-1 text-end">
                            TOTAL : </td>
                        <td class="font-bold p-1 text-end" style="width: 90px;">
                            {{ $venta->moneda->simbolo }}
                            {{ number_format($venta->total, 2, '.', ', ') }}</td>
                    </tr>
                </thead>
            </table>
        @endif
    </div>

    <p class="font-normal leading-4 mt-3" style="text-align: justify">
        Estimado cliente, no hay devolución de dinero. Todo cambio de mercadería solo podrá
        realizarse dentro de las 48 horas, previa presentacion del comprobante.
    </p>

    <p class="text-center font-normal">***GRACIAS POR SU COMPRA***</p>

    @if (!empty($venta->sucursal->empresa->web))
        <p class="text-center font-normal">{{ $venta->sucursal->empresa->web }}</p>
    @endif
</body>

</html>
