<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ $guia->seriecomprobante->typecomprobante->descripcion }} - {{ $guia->seriecompleta }}
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
        margin: 4cm 1cm 2cm 1cm;
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
        max-width: 100%;
        height: auto;
        max-height: 1.8cm;
    }

    #footer {
        position: fixed;
        width: 100%;
        bottom: -0.85cm;
        left: 0;
    }

    p {
        margin: 0;
        padding: 0 10px;
    }

    h1 {
        margin: 0;
        padding: 0;
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

    .text-8 {
        font-size: 8px;
    }

    .text-9 {
        font-size: 9px;
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

    .table-border td,
    .table-border th {
        border: 0.5px solid black;
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
                        @if ($guia->sucursal->empresa->image || $guia->sucursal->empresa->logoimpresion)
                            <div class="">
                                @if ($guia->sucursal->empresa->logoimpresion)
                                    <img src="{{ getLogoEmpresa($guia->sucursal->empresa->logoimpresion, false) }}"
                                        alt="" class="image" />
                                @else
                                    <img src="{{ getLogoEmpresa($guia->sucursal->empresa->image->url, false) }}"
                                        alt="" class="image" />
                                @endif
                            </div>
                        @endif
                    </th>
                    <th class="align-baseline" style="padding: 0 2px;">
                        <p class="font-bold text-14 leading-4" style="margin:0;">
                            {{ $guia->sucursal->empresa->name }}</p>

                        <p class="font-normal text-10 leading-3">
                            {{ $guia->sucursal->direccion }}
                        </p>

                        @if ($guia->sucursal->ubigeo)
                            <p class="font-normal text-10 leading-3">
                                {{ $guia->sucursal->ubigeo->region }}
                                - {{ $guia->sucursal->ubigeo->provincia }}
                                - {{ $guia->sucursal->ubigeo->distrito }}
                            </p>
                        @endif

                        @if (count($guia->sucursal->empresa->telephones) > 0)
                            <p class="font-normal" style="font-size: 10px; line-height: .7rem;">
                                TELÉFONO:
                                <span>{{ formatTelefono($guia->sucursal->empresa->telephones->first()->phone) }}</span>
                            </p>
                        @endif

                    </th>
                    <th class="serie border-2" style="vertical-align:middle; ">
                        <p class="font-bold text-14 leading-7">
                            {{ $guia->sucursal->empresa->document }}</p>
                        <h1 class="font-bold text-14 leading-7" style="margin:0;">
                            {{ $guia->seriecomprobante->typecomprobante->descripcion }}
                        </h1>
                        <p class="font-bold text-14 leading-7">
                            {{ $guia->seriecompleta }}</p>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
    @php
        $serie_reeplace = str_replace('-', '|', $guia->seriecompleta);
        $tipo_doc_cliente = strlen(trim($guia->client->document)) == 11 ? 6 : 1;
        $hashcomprobante =
            $guia->sucursal->empresa->document .
            '|' .
            $guia->seriecomprobante->typecomprobante->code .
            '|' .
            $serie_reeplace .
            '|' .
            number_format($guia->igv + $guia->igvgratuito, 2, '.', '') .
            '|' .
            number_format($guia->total, 2, '.', '') .
            '|' .
            formatDate($guia->date, 'DD/MM/Y') .
            '|' .
            $tipo_doc_cliente .
            '|' .
            $guia->client->document;

        if (!empty($guia->hash)) {
            $hashcomprobante .= '|' . $guia->hash;
        }
    @endphp
    <div id="footer" class="">
        <table class="table" style="padding: 0 10px; border-collapse: separate;">
            <thead>
                {{-- <tr class="align-middle">
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
                </tr> --}}
                @if (!empty($guia->sucursal->empresa->web))
                    <tr>
                        <td colspan="2" class="text-center text-12 leading-3 font-normal">
                            {{ $guia->sucursal->empresa->web }}</td>
                    </tr>
                @endif
            </thead>
        </table>
    </div>
    <div class="body">
        <h1 class="font-bold text-10 align-middle text-left">DATOS DE INICIO DEL TRASLADO</h1>
        <table class="table text-8">
            <tbody>
                <tr>
                    <td class="font-medium" style="">
                        FECHA EMISION </td>
                    <td class="font-bold">
                        : {{ formatDate($guia->date, 'DD/MM/Y') }}</td>

                    <td class="font-medium" style="width: 130px">
                        MOTIVO TRASLADO </td>
                    <td class="font-bold" style="">
                        : {{ $guia->motivotraslado->name }}</td>

                    <td class="font-medium" style="width: 70px">
                        PESO BRUTO </td>
                    <td class="font-bold text-end" style="width: 50px">
                        : {{ $guia->peso }} {{ $guia->unit }} </td>
                </tr>

                <tr>
                    <td class="font-medium" style="width: 110px">
                        FECHA INICIO TRASLADO </td>
                    <td class="font-bold" style="width: 100px">
                        : {{ formatDate($guia->datetraslado, 'DD/MM/Y') }}</td>

                    <td class="font-medium" style="">
                        MODALIDAD TRANSPORTE </td>
                    <td class="font-bold" style="">
                        : {{ $guia->modalidadtransporte->name }}</td>
                </tr>

                <tr>
                    {{-- <td class="font-medium" style="width: 110px">
                        FECHA ENTREGA </td>
                    <td class="font-bold" style="width: 100px">
                        : </td> --}}

                    <td class="font-medium" style="width: 135px">
                        DOCUMENTO RELACIONADO </td>
                    <td class="font-bold" style="">
                        : {{ $guia->referencia }}
                    </td>
                </tr>
            </tbody>
        </table>

        <h1 class="font-bold text-10 align-middle text-left mt-2">DATOS DEL DESTINATARIO</h1>
        <table class="table text-8">
            <tbody>
                <tr class="align-middle">
                    <td class="font-medium" style="width: 135px">
                        DOCUMENTO
                    </td>
                    <td class="font-bold" style="width: 100px">
                        : {{ $guia->documentdestinatario }}</td>

                    <td class="font-medium" style="width: 130px">
                        RAZÓN SOCIAL </td>
                    <td class="font-bold">
                        : {{ $guia->namedestinatario }}
                    </td>
                </tr>
            </tbody>
        </table>

        <h1 class="font-bold text-10 mt-2 align-middle text-left">DATOS DEL PUNTO DE PARTIDA Y PUNTO DE LLEGADA</h1>
        <table class="table text-8">
            <tbody>
                <tr>
                    <td class="font-medium" style="width: 135px">
                        DIRECCIÓN DE PUNTO DE PARTIDA </td>
                    <td class="font-bold">
                        : {{ $guia->direccionorigen }} - {{ $guia->ubigeoorigen->distrito }}
                        - {{ $guia->ubigeoorigen->provincia }} - {{ $guia->ubigeoorigen->region }}</td>
                </tr>
                <tr>
                    <td class="font-medium" style="width: 135px">
                        DIRECCIÓN DE PUNTO DE LLEGADA </td>
                    <td class="font-bold" style="">
                        : {{ $guia->direcciondestino }} - {{ $guia->ubigeodestino->distrito }}
                        - {{ $guia->ubigeodestino->provincia }} - {{ $guia->ubigeodestino->region }}</td>
                </tr>
            </tbody>
        </table>

        {{-- 01: PUBLICO, 02:PRIVADO --}}
        @if ($guia->isVehiculosml())
            <h1 class="font-bold text-10 align-middle text-left mt-2">DATOS DEL VEHÍCULO</h1>
            <table class="table text-8">
                <tbody>
                    <tr class="align-baseline">
                        <td class="p-1 font-medium" style="width: 135px">
                            N° PLACA
                        </td>
                        <td class="p-1 font-bold">
                            : {{ $guia->placavehiculo }}
                        </td>
                    </tr>
                </tbody>
            </table>
        @else
            @if ($guia->modalidadtransporte->code == '01')
                <h1 class="font-bold text-10 align-middle text-left">DATOS DEL TRANSPORTISTA</h1>
                <table class="table text-8">
                    <tbody>
                        <tr class="align-middle">
                            <td class="font-medium border" style="width: 110px">
                                RUC
                            </td>
                            <td class="font-bold border" style="width: 100px">
                                : {{ $guia->ructransport }}</td>

                            <td class="font-medium border" style="width: 70px">
                                RAZÓN SOCIAL </td>
                            <td class="font-bold border">
                                : {{ $guia->nametransport }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            @else
                <h1 class="font-bold text-10 align-middle text-left">DATOS DE LOS VEHÍCULOS</h1>
                @if (count($guia->transportvehiculos) > 0)
                    <table class="table text-8">
                        <tbody>
                            @foreach ($guia->transportvehiculos as $item)
                                <tr class="align-middle">
                                    <td class="font-medium" style="width: 135px">
                                        @if ($item->isPrincipal())
                                            PRINCIPAL
                                        @else
                                            SECUNDARIO
                                        @endif
                                    </td>
                                    <td class="font-medium">
                                        <p class="" style="margin:0; padding:0;">: {{ $item->name }}
                                            N° PLACA {{ $item->placa }}</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <h1 class="font-bold text-10 align-middle text-left">DATOS DE LOS CONDUCTORES</h1>
                @if (count($guia->transportdrivers) > 0)
                    <table class="table text-8">
                        <tbody>
                            @foreach ($guia->transportdrivers as $item)
                                <tr class="align-baseline">
                                    <td class="font-medium" style="width: 135px">
                                        @if ($item->isPrincipal())
                                            PRINCIPAL
                                        @else
                                            SECUNDARIO
                                        @endif
                                    </td>
                                    <td class="font-medium">
                                        <p class="" style="margin:0; padding:0;">: {{ $item->name }}
                                            {{ $item->lastname }} - DNI N° {{ $item->document }}</p>
                                        <p class="text-8 leading-2" style="margin:0; padding:0;">: N° LICENCIA
                                            CONDUCIR :
                                            <span>{{ $item->licencia }}</span>
                                        </p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endif
        @endif


        @if ($guia->motivotraslado->code == '03' || $guia->motivotraslado->code == '13')
            <h1 class="font-bold text-10 align-middle text-left mt-2">DATOS DEL COMPRADOR</h1>
            <table class="table text-8">
                <tbody>
                    <tr class="align-middle">
                        <td class="font-medium" style="width: 135px">
                            DOCUMENTO
                        </td>
                        <td class="font-bold" style="width: 100px">
                            : {{ $guia->client->document }}</td>

                        <td class="font-medium" style="width: 130px">
                            RAZÓN SOCIAL </td>
                        <td class="font-bold">
                            : {{ $guia->client->name }}
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif

        @if ($guia->motivotraslado->code == '02' || $guia->motivotraslado->code == '07' || $guia->motivotraslado->code == '13')
            <h1 class="font-bold text-10 align-middle text-left mt-2">DATOS DEL PROVEEDOR</h1>
            <table class="table text-8">
                <tbody>
                    <tr class="align-middle">
                        <td class="font-medium" style="width: 135px">
                            RUC
                        </td>
                        <td class="font-bold" style="width: 100px">
                            : {{ $guia->rucproveedor }}</td>

                        <td class="font-medium" style="width: 130px">
                            RAZÓN SOCIAL </td>
                        <td class="font-bold">
                            : {{ $guia->nameproveedor }}
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif

        <h1 class="font-bold text-10 align-middle text-left mt-3">INFORMACIÓN DE BIENES TRASLADADOS</h1>
        @if (count($guia->tvitems) > 0)
            <table class="table mt-3 text-10 font-normal">
                <thead style="background: #CCC">
                    <tr class="border-table">
                        <th class="font-bold p-2 text-center align-middle">ITEM</th>
                        <th class="font-bold p-2 text-center align-middle">DESCRIPCIÓN</th>
                        <th class="font-bold p-2 text-center align-middle">CANT.</th>
                        <th class="font-bold p-2 text-center align-middle">UNIDAD</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($guia->tvitems as $item)
                        <tr class="align-middle border-table">
                            <td class="p-2 font-normal text-center align-middle" style="width: 30px;">
                                {{ $loop->iteration }}
                            </td>
                            <td class="p-2 align-middle text-start leading-3" style="">
                                {{ $item->producto->name }}</td>
                            <td class="p-2 text-center align-middle" style="width: 30px;">
                                {{ decimalOrInteger($item->cantidad) }}</td>
                            <td class="p-2 font-normal text-center align-middle" style="width: 30px;">
                                {{ $item->producto->unit->code }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <p class="text-9 mt-3 font-medium" style="margin:0; padding:0;">
            INDICADOR TRASLADO EN VEHÍCULOS DE CATEGORÍA M1 o L:
            <span class="font-bold">
                @if ($guia->isVehiculosml())
                    SI
                @else
                    NO
                @endif
            </span>
        </p>

        <table class="table text-8 mt-3">
            <tbody>
                <tr class="align-baseline">
                    <td class="p-1 font-medium" style="width: 110px">
                        OBSERVACIONES
                    </td>
                    <td class="p-1 font-normal">
                        : {{ $guia->note }} - {{ $guia->descripcion }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
