<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> --}}
    <title>DOCUMENTO COMPRA - {{ $compra->referencia }}</title>
    <link rel="stylesheet" href="{{ asset('css/ubuntu.css') }}">
</head>
<style>
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

    .px-3 {
        padding-left: 6px;
        padding-right: 6px;
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
        font-family: 'Ubuntu';
        font-weight: 300;
    }

    .font-normal {
        font-weight: 400;
    }

    .font-medium {
        font-family: 'Ubuntu';
        font-weight: 500;
    }

    .font-bold {
        font-family: 'Ubuntu';
        font-weight: 700;
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
                        @if ($compra->sucursal->empresa->image)
                            <div class="">
                                <img src="{{ Storage::url('images/company/' . $compra->sucursal->empresa->image->url) }}"
                                    alt="" class="image" />
                            </div>
                        @endif
                    </th>
                    <th class="align-baseline" style="padding: 0 2px;">
                        <p class="font-bold text-14 leading-4" style="margin:0;">
                            {{ $compra->sucursal->empresa->name }}</p>

                        <p class="font-normal text-10 leading-3">
                            {{ $compra->sucursal->direccion }}
                        </p>

                        @if ($compra->sucursal->ubigeo)
                            <p class="font-normal text-10 leading-3">
                                {{ $compra->sucursal->ubigeo->region }}
                                - {{ $compra->sucursal->ubigeo->provincia }}
                                - {{ $compra->sucursal->ubigeo->distrito }}
                            </p>
                        @endif

                        @if (count($compra->sucursal->empresa->telephones) > 0)
                            <p class="font-normal" style="font-size: 10px; line-height: .7rem;">
                                TELÉFONO:
                                <span>{{ formatTelefono($compra->sucursal->empresa->telephones->first()->phone) }}</span>
                            </p>
                        @endif

                    </th>
                    <th class="serie border-2" style="vertical-align:middle; ">
                        <p class="font-bold text-14 leading-7">
                            {{ $compra->sucursal->empresa->document }}</p>
                        <p class="font-bold text-14 leading-7">
                            DOCUMENTO COMPRA
                        </p>
                        <p class="font-bold text-14 leading-7">{{ $compra->referencia }}</p>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
    @php
        $serie_reeplace = str_replace('-', '|', $compra->seriecompleta);
        $tipo_doc_cliente = strlen(trim($compra->proveedor->document)) == 11 ? 6 : 1;
        $bottom = '-3.35cm';
    @endphp
    <div id="footer" class="" style="bottom: {{ $bottom }};">
        <table class="table" style="padding: 0 10px; border-collapse: separate;">
            <thead>
                @if (!empty($compra->sucursal->empresa->web))
                    <tr>
                        <td colspan="2" class="text-center text-12 leading-3">
                            {{ $compra->sucursal->empresa->web }}</td>
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
                        FECHA COMPRA </td>
                    <td class="p-1 font-bold">
                        : {{ formatDate($compra->date, 'DD MMMM Y') }}</td>
                </tr>
                <tr>
                    <td class="p-1 font-medium align-baseline" style="width:100px">
                        PROVEEDOR </td>
                    <td class="p-1 font-bold">
                        : {{ $compra->proveedor->document }} - {{ $compra->proveedor->name }}</td>
                </tr>
                {{-- <tr>
                    <td class="p-1 font-medium" style="width: 100px">
                        TIPO PAGO </td>
                    <td class="p-1 font-bold">
                        : {{ $compra->typepayment->name }}
                        @if ($compra->typepayment->isCredito())
                            ( {{ count($compra->cuotas) }} CUOTAS)
                        @endif
                    </td>
                </tr> --}}

                {{-- <tr>
                    <td class="p-1 font-medium" style="width: 100px;">
                        MONEDA </td>
                    <td class="p-1 font-bold">
                        : {{ $compra->moneda->currency }}
                        @if ($compra->moneda->isDolar())
                            (TC. {{ number_format($compra->tipocambio, 2, '.', ' ,') }})
                        @endif
                    </td>
                </tr> --}}
            </tbody>
        </table>

        @if (count($compra->compraitems) > 0)
            <table class="table mt-3 text-10">
                <thead style="background: #CCC">
                    <tr class="border-table">
                        <th class="font-bold p-2 text-center" style="">ITEM</th>
                        <th class="font-bold p-2 text-center" style=";">DESCRIPCIÓN</th>
                        <th class="font-bold p-2 text-center" style="">TOTAL</th>
                        {{-- <th class="font-bold p-2 text-center" style=";">DISTRIBUCIÓN</th> --}}
                        {{-- <th class="font-bold p-2 text-center" style=";">P. UNIT.</th>
                        <th class="font-bold p-2 text-center" style=";">IMPORTE</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($compra->compraitems as $item)
                        <tr class="border-table font-normal" style="border-bottom: 0 !important;">
                            <td class="p-2 text-center align-middle" rowspan="3" style="width: 40px;">
                                {{ $loop->index + 1 }}
                            </td>
                            <td class="p-2 align-middle text-start leading-3">
                                {{ $item->producto->name }}
                            </td>
                            <td class="p-2 text-center align-middle" style="width: 60px;" rowspan="2">
                                {{ formatDecimalOrInteger($item->cantidad) }} {{ $item->producto->unit->name }}
                            </td>
                        </tr>
                        <tr class="border-table font-normal"
                            style="border-top: 0 !important;border-bottom: 0 !important;">
                            <td class="p-2 font-bold">
                                DISTRIBUCION
                            </td>
                        </tr>
                        <tr class="border-table" style="border-top: 0 !important;"">
                            <td class="p-2" colspan="2" style="border-top: 0 !important;">
                                @foreach ($item->almacencompras as $almacen)
                                    <p class="font-normal" style="margin: 0; padding: 0">
                                        <span class="font-medium">
                                            [{{ formatDecimalOrInteger($almacen->cantidad) }}
                                            {{ $item->producto->unit->name }}]</span>
                                        - {{ $almacen->almacen->name }}
                                    </p>

                                    @if (count($almacen->series) > 0)
                                        <p style="padding: 0px; margin-bottom: 5px;">
                                            @foreach ($almacen->series as $serie)
                                                <span class="px-3 font-medium">
                                                    - S/N: {{ $serie->serie }}</span>
                                            @endforeach
                                        </p>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    {{-- <tr>
                        <td class="font-bold p-1 text-end" style="" colspan="3">

                        </td>
                        <td class="font-bold p-1 text-end">
                            SUBTOTAL :
                        </td>
                        <td class="font-bold p-1 text-end" style="width: 70px;">
                            {{ $compra->moneda->simbolo }}
                            {{ number_format($compra->total + $compra->descuento, 2, '.', ', ') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end" colspan="4">
                            GRAVADO : </td>
                        <td class="font-bold p-1 text-end" style="width: 80px;">
                            {{ $compra->moneda->simbolo }}
                            {{ number_format($compra->gravado, 2, '.', ', ') }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end" colspan="4">
                            EXONERADO : </td>
                        <td class="font-bold p-1 text-end" style="width: 80px;">
                            {{ $compra->moneda->simbolo }}
                            {{ number_format($compra->exonerado, 2, '.', ', ') }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end" colspan="4">
                            IGV : </td>
                        <td class="font-bold p-1 text-end" style="width: 80px;">
                            {{ $compra->moneda->simbolo }}
                            {{ number_format($compra->igv + $compra->igvgratuito, 2, '.', ', ') }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end" colspan="4">
                            DESCUENTOS : </td>
                        <td class="font-bold p-1 text-end" style="width: 80px;">
                            {{ $compra->moneda->simbolo }}
                            {{ number_format($compra->descuento, 2, '.', ', ') }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold p-1 text-end" colspan="4">
                            TOTAL : </td>
                        <td class="font-bold p-1 text-end" style="width: 80px;">
                            {{ $compra->moneda->simbolo }}
                            {{ number_format($compra->total, 2, '.', ', ') }}</td>
                    </tr> --}}
                </tbody>
            </table>

            {{-- @if ($comprobante->typepayment->isCredito())
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
            @endif --}}
        @endif



        {{-- <p class="font-light">The quick brown fox jumps over the lazy dog</p>
        <p class="font-normal">The quick brown fox jumps over the lazy dog</p>
        <p class="font-medium">The quick brown fox jumps over the lazy dog</p>
        <p class="font-bold">The quick brown fox jumps over the lazy dog</p> --}}

        {{-- @for ($i = 1; $i < 100; $i++)
            <div style="background:#7e7e7e; margin:2px; font-size: 11px">
                <p class="font-normal">{{ $i }}</p>
            </div>
        @endfor --}}
    </div>
</body>

</html>
