<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ $comprobante->seriecomprobante->typecomprobante->descripcion }} - {{ $comprobante->seriecompleta }}
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

    .image {
        /* border: 1px solid #000; */
        /* width: 80%;
        object-fit: cover;
        height: auto;
        max-height: 2cm;
        margin: auto;
        text-align: center; */

        max-width: 80%;
        height: auto;
        max-height: 2cm;
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

    .font-normal {
        font-weight: 300 !important;
    }

    .font-regular {
        font-weight: 400 !important;
    }

    .font-medium {
        font-weight: 500 !important;
    }

    .font-bold {
        font-weight: 700 !important;
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

    .align-baseline {
        vertical-align: baseline !important;
    }

    .align-middle {
        vertical-align: middle !important;
    }
</style>

<body class="">
    @if ($comprobante->sucursal->empresa->image)
        <div class="text-center">
            <img src="{{ $comprobante->sucursal->empresa->image->getLogoEmpresa() }}" alt="" class="image" />
        </div>
    @endif

    <p class="font-bold text-center" style="font-size: 11px;">
        {{ $comprobante->sucursal->empresa->name }}</p>

    <p class="text-center font-medium" style="line-height: 1rem;">
        {{ $comprobante->sucursal->empresa->document }}</p>

    <p class="font-regular text-center leading-3 mt-2" style="font-size: 10px;">
        {{ $comprobante->sucursal->direccion }}
    </p>

    @if ($comprobante->sucursal->ubigeo)
        <p class="font-regular text-center leading-3 mt-2" style="font-size: 10px;">
            {{ $comprobante->sucursal->ubigeo->region }}
            - {{ $comprobante->sucursal->ubigeo->provincia }}
            - {{ $comprobante->sucursal->ubigeo->distrito }}
        </p>
    @endif

    @if (count($comprobante->sucursal->empresa->telephones) > 0)
        <p class="font-regular text-center leading-4 mt-2" style="font-size: 10px;">
            TELÉFONO:
            <span>{{ formatTelefono($comprobante->sucursal->empresa->telephones->first()->phone) }}</span>
        </p>
    @endif

    <p class="text-center font-bold mt-3" style="font-size: 11px;">
        {{ $comprobante->seriecomprobante->typecomprobante->descripcion }}
    </p>
    <p class="text-center font-bold" style="font-size: 11px; line-height: 1rem;">{{ $comprobante->seriecompleta }}</p>

    @php
        $serie_reeplace = str_replace('-', '|', $comprobante->seriecompleta);
        $tipo_doc_cliente = strlen(trim($comprobante->client->document)) == 11 ? 6 : 1;
        $hashcomprobante =
            $comprobante->sucursal->empresa->document .
            '|' .
            $comprobante->seriecomprobante->typecomprobante->code .
            '|' .
            $serie_reeplace .
            '|' .
            number_format($comprobante->igv + $comprobante->igvgratuito, 2, '.', '') .
            '|' .
            number_format($comprobante->total, 2, '.', '') .
            '|' .
            formatDate($comprobante->date, 'DD/MM/Y') .
            '|' .
            $tipo_doc_cliente .
            '|' .
            $comprobante->client->document;

        if (!empty($comprobante->hash)) {
            $hashcomprobante .= '|' . $comprobante->hash;
        }
    @endphp


    <div class="body">
        <table class="table rounded mt-3">
            <tbody style="">
                <tr>
                    <td class="font-medium" style="width: 50px;text-align: left;">
                        FECHA </td>
                    <td class="font-bold" style="text-align: left;">
                        : {{ formatDate($comprobante->date, 'DD/MM/Y HH:mm A') }}</td>
                </tr>
                <tr class="">
                    <td class="font-medium align-baseline" style="width: 50px;text-align: left;">
                        CLIENTE </td>
                    <td class="font-bold leading-5" style="text-align: left;">
                        : {{ $comprobante->client->name }}</td>
                </tr>
                <tr>
                    <td class="font-medium align-baseline" style="width: 50px;text-align: left;">
                        DIRECCIÓN </td>
                    <td class="font-bold" style="text-align: left;">
                        : {{ $comprobante->direccion }}</td>
                </tr>
                <tr>
                    <td class="font-medium" style="width: 50px;text-align: left;">
                        TIPO PAGO </td>
                    <td class="font-bold" style="text-align: left;">
                        : {{ $comprobante->typepayment->name }}</td>
                </tr>
                @if ($comprobante->typepayment->isCredito())
                    {{-- <tr>
                        <td colspan="2" class="font-medium" style="width: 50px;text-align: left;">
                            MONTO PENDIENTE PAGO :
                            <span
                                class="font-bold">{{ number_format($comprobante->total - $comprobante->paymentactual, 2, '.', ', ') }}</span>
                        </td>
                    </tr> --}}
                    @if (count($comprobante->cuotas) > 0)
                        <tr>
                            <td colspan="2" class="font-medium"
                                style="width: 50px;text-align: left; vertical-align:middle">
                                N° CUOTAS :
                                <span class="font-bold">{{ count($comprobante->cuotas) }}</span>
                            </td>
                        </tr>
                    @elseif($comprobante->facturable)
                        <tr>
                            <td colspan="2" class="font-medium"
                                style="width: 50px;text-align: left; vertical-align:middle">
                                N° CUOTAS :
                                <span class="font-bold">{{ count($comprobante->facturable->cuotas) }}</span>
                            </td>
                        </tr>
                    @endif
                @endif
                <tr>
                    <td class="font-medium" style="width: 50px;text-align: left; vertical-align:middle">
                        MONEDA </td>
                    <td class="font-bold" style="text-align: left; vertical-align:middle;">
                        : {{ $comprobante->moneda->currency }}</td>
                </tr>
            </tbody>
        </table>

        @if (count($comprobante->facturableitems) > 0)
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
                    @foreach ($comprobante->facturableitems as $item)
                        <tr class="font-regular">
                            <td class="p-1 text-9 leading-5 text-start" style="width:100%">
                                <p>
                                    <small class="font-medium">[
                                        {{ formatDecimalOrInteger($item->cantidad) }} {{ $item->unit }}
                                        ]</small>
                                    {{ $item->descripcion }}
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
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->exonerado + $comprobante->gravado + $comprobante->igv + $comprobante->gratuito + $comprobante->igvgratuito + $comprobante->descuento, 2, '.', ', ') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end">
                            GRAVADO : </td>
                        <td class="font-bold p-1 text-end" style="width: 90px;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->gravado, 2, '.', ', ') }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end">
                            EXONERADO : </td>
                        <td class="font-bold p-1 text-end" style="width: 90px;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->exonerado, 2, '.', ', ') }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end">
                            IGV : </td>
                        <td class="font-bold p-1 text-end" style="width: 90px;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->igv + $comprobante->igvgratuito, 2, '.', ', ') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end">
                            DESCUENTOS : </td>
                        <td class="font-bold p-1 text-end" style="width: 90px;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->descuento, 2, '.', ', ') }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end">
                            GRATUITO : </td>
                        <td class="font-bold p-1 text-end" style="width: 90px;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->gratuito, 2, '.', ', ') }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end">
                            TOTAL : </td>
                        <td class="font-bold p-1 text-end" style="width: 90px;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->total, 2, '.', ', ') }}</td>
                    </tr>
                </thead>
            </table>
        @endif
    </div>

    <p class="font-bold p-1 text-center leading-6 mt-3">
        SON : {{ $comprobante->leyenda }}
    </p>

    <div class="text-center mt-3">
        <img class="border-2" style="padding:1mm;" src="data:image/svg;base64, {!! base64_encode(QrCode::format('svg')->size(80)->generate($hashcomprobante)) !!} ">
    </div>

    <p class="font-normal leading-5 mt-3" style="text-align: justify">
        Estimado cliente, no hay devolución de dinero. Todo cambio de mercadería solo podrá
        realizarse dentro de las 48 horas, previa presentacion del comprobante.
    </p>
    <p class="font-normal leading-5 mt-1" style="text-align: justify;">
        Representación impresa del comprobante de venta electrónico, este puede ser consultado en
        www.sunat.gob.pe.
    </p>
    <p class="font-normal leading-5 mt-1" style="text-align: justify">
        Autorizado mediante resolución N° 155-2017/Sunat.</p>

    <p class="text-center font-bold leading-5" style="font-size: 10px; margin-top: 10px;">
        BIENES TRANSFERIDOS EN LA AMAZONIA PARA SER CONSUMIDOS EN LA MISMA Y/O SERVICIOS PRESTADOS
        EN LA AMAZONIA.
    </p>

    <p class="text-center font-regular">***GRACIAS POR SU COMPRA***</p>

    @if (!empty($comprobante->sucursal->empresa->web))
        <p class="text-center">{{ $comprobante->sucursal->empresa->web }}</p>
    @endif
</body>

</html>
