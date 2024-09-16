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
        font-family: 'Ubuntu';
    }

    @page {
        margin: 4cm 1cm 4.5cm 1cm;
    }

    #header {
        position: fixed;
        top: -3cm;
        left: 0;
        width: 100%;
        height: 27.5cm;
        /* background: #DDD; */
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    #header .table {
        padding: 10px;
    }

    #header .table thead,
    #header .table thead td,
    #header .table thead th {
        vertical-align: baseline;
        width: 100%;
    }

    .image {
        width: auto;
        max-width: 100%;
        height: auto;
        max-height: 2cm;
    }

    #footer {
        position: fixed;
        width: 100%;
        /* VALOR BOTTOM DINAMICO EN DIV#FOOTER */
        /* bottom: -2.75cm; */
        left: 0;
    }

    p {
        margin: 0;
        padding: 0 10px;
    }

    .leading-2 {
        line-height: 0.5rem;
    }

    .leading-3 {
        line-height: 0.6rem;
    }

    .leading-4 {
        line-height: 0.7rem;
    }

    .leading-5 {
        line-height: 0.8rem;
    }

    .leading-6 {
        line-height: 1rem;
    }

    .align-middle-center {
        vertical-align: center !important;
    }

    .align-baseline {
        vertical-align: baseline !important;
    }

    .align-middle {
        vertical-align: middle !important;
    }

    .text-10 {
        font-size: 10px;
    }

    .text-11 {
        font-size: 11px;
    }

    .text-12 {
        font-size: 12px;
    }

    .text-13 {
        font-size: 13px;
    }

    .text-14 {
        font-size: 14px;
    }

    .body {
        padding: 10px;
        font-weight: 200;
    }

    .border-table {
        border: 0.5px solid black !important;
    }

    .border-l-table {
        border-left: 0.5px solid black;
    }

    .border-r-table {
        border-right: 0.5px solid black;
    }

    .border {
        border: 1px solid black !important;
    }

    .border-2 {
        border: 2px solid black !important;
    }

    .p-1 {
        padding: 2px;
    }

    .p-2 {
        padding: 4px;
    }

    .p-3 {
        padding: 6px;
    }

    .rounded {
        border-radius: 2rem;
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

    .text-start {
        text-align: left !important;
    }

    .text-center {
        text-align: center !important;
    }

    .text-end {
        text-align: right !important;
    }
</style>

<body>
    <div id="header" class="border">
        <table class="table">
            <thead>
                <tr class="align-baseline">
                    <th style="text-align: left;">
                        @if ($comprobante->sucursal->empresa->image)
                            <div class="">
                                <img src="{{ $comprobante->sucursal->empresa->image->getLogoEmpresa() }}"
                                    alt="" class="image" />
                            </div>
                        @endif
                    </th>
                    <th class="align-baseline" style="padding: 0 2px;">
                        <p class="font-bold text-14 leading-4" style="margin:0;">
                            {{ $comprobante->sucursal->empresa->name }}</p>

                        <p class="font-normal text-10 leading-3">
                            {{ $comprobante->sucursal->direccion }}
                        </p>

                        @if ($comprobante->sucursal->ubigeo)
                            <p class="font-normal text-10 leading-3">
                                {{ $comprobante->sucursal->ubigeo->region }}
                                - {{ $comprobante->sucursal->ubigeo->provincia }}
                                - {{ $comprobante->sucursal->ubigeo->distrito }}
                            </p>
                        @endif

                        @if (count($comprobante->sucursal->empresa->telephones) > 0)
                            <p class="font-normal" style="font-size: 10px; line-height: .7rem;">
                                TELÉFONO:
                                <span>{{ formatTelefono($comprobante->sucursal->empresa->telephones->first()->phone) }}</span>
                            </p>
                        @endif

                    </th>
                    <th class="serie border-2" style="vertical-align:middle; ">
                        <p class="font-bold text-14 leading-7">
                            {{ $comprobante->sucursal->empresa->document }}</p>
                        <p class="font-bold text-14 leading-7">
                            {{ $comprobante->seriecomprobante->typecomprobante->descripcion }}
                        </p>
                        <p class="font-bold text-14 leading-7">{{ $comprobante->seriecompleta }}</p>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
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
        $bottom = !empty($comprobante->sucursal->empresa->web) ? '-3.4cm' : '-3cm';
    @endphp
    <div id="footer" class="" style="bottom: {{ $bottom }};">
        <table class="table" style="padding: 0 10px; border-collapse: separate;">
            <thead>
                <tr class="align-middle">
                    <td class="border-2">
                        <img class="" style="padding:1mm;" src="data:image/svg;base64, {!! base64_encode(QrCode::format('svg')->size(100)->generate($hashcomprobante)) !!} ">
                    </td>
                    <td class="title border" style="">
                        <p class="font-normal leading-3 text-12" style="text-align: justify">
                            Estimado cliente, no hay devolución de dinero. Todo cambio de mercadería solo podrá
                            realizarse dentro de las 48 horas, previa presentacion del comprobante.
                        </p>
                        <p class="font-normal leading-3 text-12 mt-2">
                            Representación impresa del comprobante de venta electrónico, este puede ser consultado en
                            www.sunat.gob.pe.
                        </p>
                        <p class="font-normal text-12 leading-3 mt-2">
                            Autorizado mediante resolución N° 155-2017/Sunat.</p>

                        <p class="text-center font-bold text-10 mt-3 leading-2">
                            BIENES TRANSFERIDOS EN LA AMAZONIA PARA SER CONSUMIDOS EN LA MISMA Y/O SERVICIOS PRESTADOS
                            EN LA AMAZONIA.
                        </p>
                    </td>
                </tr>
                @if (!empty($comprobante->sucursal->empresa->web))
                    <tr>
                        <td colspan="2" class="text-center text-12 leading-3 font-normal">
                            {{ $comprobante->sucursal->empresa->web }}</td>
                    </tr>
                @endif
            </thead>
        </table>
    </div>

    <div class="body">
        <table class="table mt-3 text-10">
            <tbody>
                <tr>
                    <td class="p-1 font-medium" style="width: 100px">
                        FECHA EMISION </td>
                    <td class="p-1 font-bold">
                        : {{ formatDate($comprobante->date, 'DD/MM/Y HH:mm A') }}</td>
                </tr>
                <tr>
                    <td class="p-1 font-medium align-baseline" style="width:100px">
                        CLIENTE </td>
                    <td class="p-1 font-bold">
                        : {{ $comprobante->client->name }}</td>
                </tr>
                <tr>
                    <td class="p-1 font-medium align-baseline" style="width:100px">
                        DIRECCIÓN </td>
                    <td class="p-1 font-bold">
                        : {{ $comprobante->direccion }}</td>
                </tr>
                <tr>
                    <td class="p-1 font-medium" style="width: 100px">
                        TIPO PAGO </td>
                    <td class="p-1 font-bold">
                        : {{ $comprobante->typepayment->name }}</td>
                </tr>

                {{-- @if ($comprobante->typepayment->isCredito())
                    <tr>
                        <td colspan="2" class="p-1 font-medium" style="width: 50px">
                            MONTO PENDIENTE PAGO :
                            <span
                                class="font-bold">{{ number_format($comprobante->total - $comprobante->paymentactual, 2, '.', ', ') }}</span>
                        </td>
                    </tr>
                @endif --}}

                <tr>
                    <td class="p-1 font-medium" style="width: 100px;">
                        MONEDA </td>
                    <td class="p-1 font-bold">
                        : {{ $comprobante->moneda->currency }}</td>
                </tr>
            </tbody>
        </table>

        @if (count($comprobante->facturableitems) > 0)
            <table class="table mt-3 text-10">
                <thead style="background: #CCC">
                    <tr class="border-table" <th class="font-bold p-2 text-center" style="">ITEM</th>
                        <th class="font-bold p-2 text-center" style=";">DESCRIPCIÓN</th>
                        <th class="font-bold p-2 text-center" style=";">CANTIDAD</th>
                        <th class="font-bold p-2 text-center" style=";">P. UNIT.</th>
                        <th class="font-bold p-2 text-center" style=";">IMPORTE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comprobante->facturableitems as $item)
                        <tr class="border-table font-normal">
                            <td class="p-2 text-center align-middle" style="width: 30px;">
                                {{ $item->item }}
                            </td>
                            <td class="p-2 align-middle text-start leading-3">
                                {{ $item->descripcion }}</td>
                            <td class="p-2 text-center" style="width: 70px;">
                                {{ formatDecimalOrInteger($item->cantidad) }} {{ $item->unit }}</td>
                            <td class="p-2 text-center" style="width: 70px;">
                                {{ number_format($item->price + $item->igv, 2, '.', ', ') }}</td>
                            <td class="p-2 text-center align-middle" style="width: 80px;">
                                {{ number_format($item->total, 2, '.', ', ') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="font-bold p-1 text-start" colspan="2" style="">
                            SON : {{ $comprobante->leyenda }}
                        </td>
                        <td class="font-bold p-1 text-end" style="">

                        </td>
                        <td class="font-bold p-1 text-end">
                            SUBTOTAL :
                        </td>
                        <td class="font-bold p-1 text-end" style="width: 70px;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->exonerado + $comprobante->gravado + $comprobante->igv + $comprobante->gratuito + $comprobante->igvgratuito + $comprobante->descuento, 2, '.', ', ') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end" colspan="4">
                            GRAVADO : </td>
                        <td class="font-bold p-1 text-end" style="width: 80px;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->gravado, 2, '.', ', ') }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end" colspan="4">
                            EXONERADO : </td>
                        <td class="font-bold p-1 text-end" style="width: 80px;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->exonerado, 2, '.', ', ') }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end" colspan="4">
                            IGV : </td>
                        <td class="font-bold p-1 text-end" style="width: 80px;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->igv + $comprobante->igvgratuito, 2, '.', ', ') }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end" colspan="4">
                            DESCUENTOS : </td>
                        <td class="font-bold p-1 text-end" style="width: 80px;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->descuento, 2, '.', ', ') }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end" colspan="4">
                            GRATUITO : </td>
                        <td class="font-bold p-1 text-end" style="width: 80px;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->gratuito, 2, '.', ', ') }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end" colspan="4">
                            TOTAL : </td>
                        <td class="font-bold p-1 text-end" style="width: 80px;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->total, 2, '.', ', ') }}</td>
                    </tr>
                </tbody>
            </table>

            @if ($comprobante->typepayment->isCredito())
                @php
                    $cuotas = [];
                    if (count($comprobante->cuotas) > 0) {
                        $cuotas = $comprobante->cuotas;
                    } elseif ($comprobante->facturable) {
                        $cuotas = $comprobante->facturable->cuotas;
                    }
                @endphp
                @if (count($cuotas) > 0)
                    <div class="" style="width: 100%; text-align: justify;">
                        <h1 class="text-10 font-bold">INFORMACIÓN DE CUOTAS</h1>
                        @foreach ($cuotas as $item)
                            <div class="p-1 font-normal border mt-2" style="width:224px; display: inline-flex;">
                                <p class="text-10 leading-4">
                                    N° CUOTA : <span class="font-bold">{{ $item->cuota }}</span>
                                </p>
                                <p class="text-10 leading-4">
                                    MONTO :
                                    <span class="font-bold">{{ number_format($item->amount, 2, '.', ', ') }}</span>
                                </p>
                                <p class="text-10 leading-4">
                                    F. VENCIMIENTO :
                                    <span class="font-bold">{{ formatDate($item->expiredate, 'DD/MM/Y') }}</span>
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        @endif
    </div>
</body>

</html>
