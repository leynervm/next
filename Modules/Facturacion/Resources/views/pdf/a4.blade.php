<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        {{ $comprobante->seriecomprobante->typecomprobante->descripcion }} - {{ $comprobante->seriecompleta }}
    </title>
</head>
<style>
    @page {
        margin: 4cm 1cm 3.5cm 1cm;
    }

    #header {
        position: fixed;
        top: -3cm;
        left: 0;
        width: 100%;
        height: 27.5cm;
        /* background: #DDD; */
    }

    #header .table {
        padding: 10px;
        width: 100%;
        border-collapse: collapse;
    }

    #header .table thead,
    #header .table thead td,
    #header .table thead th {
        border: 0;
        vertical-align: baseline;
        width: 100%;
    }

    #header .table .image {
        max-width: 100%;
        height: auto;
        max-height: 1.8cm;
    }

    #footer {
        position: fixed;
        width: 100%;
        /* VALOR BOTTOM DINAMICO EN DIV#FOOTER */
        /* bottom: -2.75cm; */
        left: 0;
    }

    #footer p {
        margin: 0;
        padding: 0 10px;
    }


    .serie p,
    .title p {
        line-height: 1rem;
        margin: 0;
        padding: 0;
        text-align: center;
        /* vertical-align: baseline; */
    }

    .serie {
        font-weight: 600;
        vertical-align: center;
        font-size: 14px;
        font-weight: 600;
    }

    .body {
        padding: 10px;
    }

    .body p,
    .body p small {
        line-height: 1rem;
        margin: 0;
        padding: 0;
        font-size: 14px;
    }

    .body p small {
        font-weight: normal !important;
    }

    .body p {
        font-weight: 600;
    }

    .body .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }

    .body .table thead,
    .body .table thead td,
    .body .table thead th,
    .body .table tbody td {
        vertical-align: baseline;
        width: 100%;
    }

    .body .table-border thead,
    .body .table-border thead td,
    .body .table-border thead th,
    .body .table-border tbody td {
        border: 1px solid #686262;
    }

    .border {
        border: 1px solid black;
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

    .font-normal {
        font-weight: normal !important;
    }

    .font-bold {
        font-weight: 600 !important;
    }

    .font-xs {
        font-size: 10px !important;
    }

    .font-sm {
        font-size: 11px !important;
    }

    .text-start {
        text-align: left;
    }

    .text-center {
        text-align: center;
    }

    .text-end {
        text-align: right;
    }
</style>

<body>
    <div id="header" class="border">
        <table class="table">
            <thead>
                <tr>
                    <th>
                        @if ($comprobante->sucursal->empresa->image)
                            <div class="">
                                <img src="{{ Storage::url('images/company/' . $comprobante->sucursal->empresa->image->url) }}"
                                    alt="" class="image" />
                            </div>
                        @endif
                    </th>
                    <th class="title" style="padding: 0 2px;">
                        <p style="font-size: 14px;">
                            {{ $comprobante->sucursal->empresa->name }}</p>

                        <p class="font-normal" style="font-size: 10px; line-height: .7rem">
                            {{ $comprobante->sucursal->direccion }}
                        </p>

                        @if ($comprobante->sucursal->ubigeo)
                            <p class="font-normal" style="font-size: 10px; line-height: .7rem">
                                {{ $comprobante->sucursal->ubigeo->region }}
                                - {{ $comprobante->sucursal->ubigeo->provincia }}
                                - {{ $comprobante->sucursal->ubigeo->distrito }}

                                {{ $comprobante->sucursal->ubigeo->region }}
                                - {{ $comprobante->sucursal->ubigeo->provincia }}
                                - {{ $comprobante->sucursal->ubigeo->distrito }}
                            </p>
                        @endif

                        @if (count($comprobante->sucursal->empresa->telephones) > 0)
                            <p class="font-normal" style="font-size: 10px;">
                                TELÉFONO:
                                <span>{{ formatTelefono($comprobante->sucursal->empresa->telephones->first()->phone) }}</span>
                            </p>
                        @endif

                    </th>
                    <th class="serie border-2" style="vertical-align:middle; ">
                        <p class="" style="line-height: 1rem;">
                            {{ $comprobante->sucursal->empresa->document }}</p>
                        <p class="" style="line-height: 1.2rem;">
                            {{ $comprobante->seriecomprobante->typecomprobante->descripcion }}
                        </p>
                        <p class="" style="line-height: 1rem;">{{ $comprobante->seriecompleta }}</p>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
    @php
        $bottom = !empty($comprobante->sucursal->empresa->web) ? '-2.75cm' : '-2.25cm';
    @endphp
    <div id="footer" class="" style="bottom: {{ $bottom }};">
        <div class="border-2" style="margin: 10px;">
            <p class="font-normal text-left" style="line-height: 0.9rem; font-size:12px">
                Estimado cliente, no hay devolución de dinero. Todo cambio de mercadería solo podrá realizarse dentro de
                las
                48 horas, previa presentacion del comprobante.
            </p>
            <p class="font-normal text-left" style="line-height: 0.9rem; font-size:12px">
                Representación impresa del comprobante de venta electrónico, este puede ser consultado en
                www.sunat.gob.pe.
            </p>
            <p class="font-normal text-left" style="line-height: 0.9rem; font-size:12px">
                Autorizado mediante resolución N° 155-2017/Sunat.</p>

            <p class="text-center font-bold" style="line-height: 0.9rem; font-size: 10px;">
                BIENES TRANSFERIDOS EN LA AMAZONIA PARA SER CONSUMIDOS EN LA MISMA Y/O SERVICIOS PRESTADOS EN LA
                AMAZONIA.
            </p>
        </div>
        @if ($comprobante->sucursal->empresa->web)
            <p class="text-center" style="line-height: 1rem; font-size: 12px;">
                {{ $comprobante->sucursal->empresa->web }}</p>
        @endif
    </div>
    <div class="body">
        {{-- <p>{{ $comprobante }}</p> --}}
        <table class="table rounded mt-3 font-sm">
            <tbody style="">
                <tr>
                    <td class="p-2 font-bold" style="width: 100px;text-align: left; vertical-align:middle">
                        FECHA EMISION </td>
                    <td class="p-2" style="text-align: left; vertical-align:middle;">
                        : {{ formatDate($comprobante->date, 'DD/MM/Y HH:mm A') }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-bold" style="width: 100px;text-align: left; vertical-align:middle">
                        CLIENTE </td>
                    <td class="p-2" style="text-align: left; vertical-align:middle;">
                        : {{ $comprobante->client->name }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-bold" style="width: 100px;text-align: left; vertical-align:middle">
                        DIRECCIÓN </td>
                    <td class="p-2" style="text-align: left; vertical-align:middle;">
                        : {{ $comprobante->direccion }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-bold" style="width: 100px;text-align: left; vertical-align:middle">
                        TIPO PAGO </td>
                    <td class="p-2" style="text-align: left; vertical-align:middle;">
                        : {{ $comprobante->typepayment->name }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-bold" style="width: 100px;text-align: left; vertical-align:middle">
                        MONEDA </td>
                    <td class="p-2" style="text-align: left; vertical-align:middle;">
                        : {{ $comprobante->moneda->currency }}</td>
                </tr>
            </tbody>
        </table>

        @if (count($comprobante->facturableitems) > 0)
            <table class="table table-border mt-3">
                <thead style="background: #CCC">
                    <tr>
                        <th class="p-2" style="text-align: center; vertical-align:middle">ITEM</th>
                        <th class="p-2" style="text-align: center; vertical-align:middle;">DESCRIPCIÓN</th>
                        <th class="p-2" style="text-align: center; vertical-align:middle;">CANTIDAD</th>
                        <th class="p-2" style="text-align: center; vertical-align:middle;">P. UNIT.</th>
                        <th class="p-2" style="text-align: center; vertical-align:middle;">IMPORTE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comprobante->facturableitems as $item)
                        <tr>
                            <td class="p-2" style="width: 30px; text-align: center; vertical-align:middle;">
                                {{ $item->item }}
                            </td>
                            <td class="p-2" style="text-align: start; vertical-align:middle;">
                                {{ $item->descripcion }}</td>
                            <td class="p-2" style="width: 70px; text-align: center; vertical-align:middle;">
                                {{ formatDecimalOrInteger($item->cantidad) }} {{ $item->unit }}</td>
                            <td class="p-2" style="width: 70px; text-align: center; vertical-align:middle;">
                                {{ $comprobante->moneda->simbolo }}
                                {{ number_format($item->price, 2, '.', ', ') }}</td>
                            <td class="p-2" style="width: 80px; text-align: center; vertical-align:middle;">
                                {{ $comprobante->moneda->simbolo }}
                                {{ number_format($item->total, 2, '.', ', ') }}</td>
                        </tr>
                    @endforeach
                    <tr class="font-bold">
                        <td class="p-2 text-start" colspan="2" style="vertical-align:middle; border: 0">
                            {{ $comprobante->leyenda }}
                        </td>
                        <td class="p-2 text-end" style="vertical-align:middle; border: 0;">

                        </td>
                        <td class="p-2 text-end" style="vertical-align:middle; border: 0;">
                            SUBTOTAL :
                        </td>
                        <td class="p-2 text-end" style="width: 70px; vertical-align:middle; border: 0;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->exonerado + $comprobante->gravado + $comprobante->igv + $comprobante->gratuito + $comprobante->igvgratuito + $comprobante->descuento, 2, '.', ', ') }}
                        </td>
                    </tr>


                    <tr class="font-bold">
                        <td class="p-2 text-end" colspan="4" style="vertical-align:middle; border: 0;">
                            GRAVADO : </td>
                        <td class="p-2 text-end" style="width: 80px; vertical-align:middle; border: 0;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->gravado, 2, '.', ', ') }}</td>
                    </tr>
                    <tr class="font-bold">
                        <td class="p-2 text-end" colspan="4" style="vertical-align:middle; border: 0;">
                            EXONERADO : </td>
                        <td class="p-2 text-end" style="width: 80px; vertical-align:middle; border: 0;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->exonerado, 2, '.', ', ') }}</td>
                    </tr>
                    <tr class="font-bold">
                        <td class="p-2 text-end" colspan="4" style="vertical-align:middle; border: 0;">
                            IGV : </td>
                        <td class="p-2 text-end" style="width: 80px; vertical-align:middle; border: 0;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->igv + $comprobante->igvgratuito, 2, '.', ', ') }}</td>
                    </tr>
                    <tr class="font-bold">
                        <td class="p-2 text-end" colspan="4" style="vertical-align:middle; border: 0;">
                            DESCUENTOS : </td>
                        <td class="p-2 text-end" style="width: 80px; vertical-align:middle; border: 0;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->descuento, 2, '.', ', ') }}</td>
                    </tr>
                    <tr class="font-bold">
                        <td class="p-2 text-end" colspan="4" style="vertical-align:middle; border: 0;">
                            GRATUITO : </td>
                        <td class="p-2 text-end" style="width: 80px; vertical-align:middle; border: 0;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->gratuito, 2, '.', ', ') }}</td>
                    </tr>
                    <tr class="font-bold">
                        <td class="p-2 text-end" colspan="4" style="vertical-align:middle; border: 0;">
                            TOTAL : </td>
                        <td class="p-2 text-end" style="width: 80px; vertical-align:middle; border: 0;">
                            {{ $comprobante->moneda->simbolo }}
                            {{ number_format($comprobante->total, 2, '.', ', ') }}</td>
                    </tr>
                </tbody>
            </table>
        @endif


        {{-- @for ($i = 1; $i < 100; $i++)
            <div style="background:#7e7e7e; margin:2px; font-size: 12px">
                <p>{{ $i }}</p>
            </div>
        @endfor --}}
    </div>
</body>

</html>
