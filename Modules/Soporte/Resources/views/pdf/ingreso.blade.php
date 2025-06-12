<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <link rel="stylesheet" href="{{ asset('assets/snappyPDF/snappy.css') }}"> --}}
    <title>
        {{ $ticket->seriecompleta }}
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
        margin: 0cm;
    }

    body {
        padding: 0.5cm;
        padding-top: 2.5cm;
    }

    .header {
        position: fixed;
        top: 0cm;
        left: 0;
        width: 100%;
        /* height: 2.25cm; */
        background: #05040f;
        color: #fff;
        padding: 0.25cm;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .image {
        display: block;
        width: auto;
        max-width: 10cm;
        min-width: 3cm;
        height: 1.5cm;
    }

    .image img {
        display: block;
        width: auto;
        max-width: 100%;
        height: 100%;
        object-fit: scale-down;
        object-position: center;
    }

    .footer {
        position: fixed;
        width: 100%;
        bottom: 0;
        left: 0;
        /* background: #ccc; */
        /* background: #05040f; */
        color: #fff;
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

    .text-8 {
        font-size: 0.575rem;
    }

    .text-9 {
        font-size: 0.625rem;
    }

    .text-10 {
        font-size: 0.675rem;
    }

    .text-11 {
        font-size: 0.7rem;
    }

    .text-12 {
        font-size: 0.785rem;
    }

    .text-13 {
        font-size: 1rem;
    }

    .text-14 {
        font-size: 1.185rem;
    }

    .text-15 {
        font-size: 1.2rem;
    }

    .text-16 {
        font-size: 1.275rem;
    }

    .text-18 {
        font-size: 1.475rem;
    }

    .text-20 {
        font-size: 1.625rem;
    }

    .text-22 {
        font-size: 2.25rem;
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

    .mt-4 {
        margin-top: 8px !important;
    }

    .mt-5 {
        margin-top: 10px !important;
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

    .text-justify {
        text-align: justify !important;
    }

    .text-end {
        text-align: right !important;
    }
</style>

<body>
    @php
        $logoimpresion = $empresa->logoimpresion;
        $logofooter = $empresa->logofooter;
        $url_logo = $empresa->logo;
        if (!empty($logoimpresion)) {
            $url_logo = $logoimpresion;
        } else {
            if (!empty($logofooter)) {
                $url_logo = $logofooter;
            }
        }
    @endphp

    <table class="header">
        <thead>
            <tr class="align-middle">
                <th class="text-start p-0" rowspan="2">
                    @if (!empty($url_logo))
                        <div class="image">
                            <img src="{{ imageBase64($url_logo) }}" alt="{{ $empresa->name }}" />
                        </div>
                    @endif
                </th>
                <th class="align-top text-end p-0">
                    <p class="font-bold text-12 leading-4 text-end p-0">
                        {{ $empresa->name }}</p>

                    <p class="font-normal text-8 leading-3 text-end p-0">
                        @if ($ticket->sucursal)
                            {{ $ticket->sucursal->direccion }}
                        @else
                            {{ $empresa->direccion }}
                        @endif
                    </p>

                    <p class="font-normal text-8 leading-3 text-end p-0">
                        @if ($ticket->sucursal)
                            @if ($ticket->sucursal->ubigeo)
                                {{ $ticket->sucursal->ubigeo->region }}
                                - {{ $ticket->sucursal->ubigeo->provincia }}
                                - {{ $ticket->sucursal->ubigeo->distrito }}
                            @endif
                        @else
                            @if ($empresa->ubigeo)
                                {{ $empresa->ubigeo->region }}
                                - {{ $empresa->ubigeo->provincia }}
                                - {{ $empresa->ubigeo->distrito }}
                            @endif
                        @endif
                    </p>
                </th>
            </tr>
            <tr>
                <th class="align-bottom font-bold p-0">
                    <p class="text-13 leading-3 text-end p-0">
                        <span class="text-10">{{ $ticket->entorno->name }}</span>
                        {{ $ticket->seriecompleta }}
                    </p>
                </th>
            </tr>
        </thead>
    </table>

    <div class="footer">
        <table class="table" style="padding:0.25cm 0.75cm;color:#222;">
            <thead>
                <tr>
                    <td></td>
                    <td class="p-0" style="border-bottom: 1.5px solid #222 !important;padding: 3.5rem 0rem;"></td>
                    <td></td>
                    <td class="p-0" style="border-bottom: 1.5px solid #222 !important;padding: 3.5rem 0rem;"></td>
                    <td></td>
                    <td class="p-0" align="right" valign="bottom" style="width: 110px;padding:0 !important;">
                        <img style="padding:1mm;border:1.5px solid #222;background: #fff;"
                            src="data:image/svg;base64, {!! base64_encode(QrCode::format('svg')->size(90)->generate($qr)) !!} ">
                    </td>
                </tr>
                <tr>
                    <td class="" style="padding:0 !important;"></td>
                    <td class="text-center text-10 font-bold" style="width: 200px;padding:0 !important;">
                        FIRMA TÉCNICO RESPONSABLE</td>
                    <td class="" style="padding:0 !important;"></td>
                    <td class="text-center text-10 font-bold" style="width: 200px;padding:0 !important;">
                        FIRMA CLIENTE</td>
                    <td class="" style="padding:0 !important;"></td>
                    {{-- <td></td> --}}
                </tr>
            </thead>
        </table>
        <table class="table" style="background: #05040f;padding: 0 2.5px; border-collapse: separate;">
            <thead>
                <tr class="align-middle">
                    <td class="" style="">
                        <p class="text-center font-light p-0 m-0" style="font-size:8px;">
                            *** IMPORTANTE: LA EMPRESA SE HACE RESPONSABLE POR LA TENENCIA DE SU EQUIPO SOLO POR 45 DIAS
                            A PARTIR DE LA
                            FECHA DE INGRESO ***
                        </p>
                        <p class="text-center font-light p-0 m-0 mt-1" style="font-size:8px;">
                            *** CONDICIONES DEL SERVICIO BRINDADO, VISITA NUESTRA PAGINA WEB ***
                        </p>

                        @if (!empty($empresa->web))
                            <p class="text-center text-9 font-normal">
                                {{ $empresa->web }}</p>
                        @endif
                    </td>
                </tr>
            </thead>
        </table>
    </div>

    <div class="body">
        <table class="table" style="border: 1.5px solid #222 !important;">
            <tr>
                <td class="p-0 m-0">
                    <table class="table border-white table-responsive p-0 m-0 text-10 font-normal">
                        <thead>
                            <tr>
                                <th class="p-0 align-top text-start" style="width: 100px;">
                                    CLIENTE</th>
                                <td class="p-0 text-start align-top text-9">
                                    : {{ $ticket->client->name }} -
                                    <b>{{ substr($ticket->client->document, 0, strlen(trim($ticket->client->document)) - 3) }}***</b>
                                </td>

                                <th class="p-0 align-top text-end" style="width: 70px;">
                                    TELÉFONOS :</th>
                                <td class="p-0 text-end align-top text-9" style="width: 220px;">
                                    {{ implode(
                                        ', ',
                                        array_map(function ($item) {
                                            return formatTelefono($item['phone']);
                                        }, $ticket->telephones->toArray()),
                                    ) }}
                                </td>
                            </tr>

                            @if ($ticket->contact)
                                <tr>
                                    <th class="p-0 align-top text-start">
                                        CONTACTO</th>
                                    <td class="p-0 text-start align-top text-9" colspan="3">
                                        : {{ $ticket->contact->name }}
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <th class="p-0 text-start">
                                    REGISTRO</th>
                                <td class="p-0 text-start text-9">
                                    : {{ FormatDate($ticket->date) }}</td>

                                <th class="p-0 align-top text-end">
                                    CONDICIÓN :</th>
                                <td class="p-0 text-end align-top text-9">
                                    : {{ $ticket->condition->name }}
                                </td>
                            </tr>
                        </thead>
                    </table>
                </td>
            </tr>
        </table>

        <table class="table mt-5" style="border: 1.5px solid #222 !important;">
            <tr>
                <td class="p-0 m-0">
                    <table class="table border-white table-responsive text-10 p-0 m-0 font-normal">
                        <tr>
                            <th class="p-0 align-top text-start" style="width: 100px;line-height: 10px;">
                                DETALLES DE ATENCIÓN</th>
                            <td class="p-0 text-justify text-9" valign="top">
                                : {{ $ticket->detalle }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        @if ($ticket->equipo)
            <table class="table p-0 m-0 mt-5 " style="border: 1.5px solid #222 !important;">
                <tr>
                    <td class="p-0 m-0">
                        <table class="table border-white table-responsive p-0 m-0 text-10 font-normal">
                            <thead>
                                <tr>
                                    <th class="p-0 align-top text-start" style="width: 80px;">
                                        EQUIPO</th>
                                    <td class="p-0 text-start align-top text-9">
                                        : {{ $ticket->equipo->typeequipo->name }}</td>

                                    <th class="p-0 align-top text-end" style="width: 50px;">
                                        MARCA :</th>
                                    <td class="p-0 text-start align-top text-9" style="width: 80px;">
                                        {{ $ticket->equipo->marca->name }}</td>

                                    <th class="p-0 align-top text-end" style="width: 60px;">
                                        MODELO :</th>
                                    <td class="p-0 text-start align-top text-9" style="width: 120px;">
                                        {{ $ticket->equipo->modelo }}</td>

                                    @if (!empty($ticket->equipo->serie))
                                        <th class="p-0 align-top text-end" style="width: 40px;">
                                            SERIE :</th>
                                        <td class="p-0 text-end align-top text-9" style="width: 120px;">
                                            {{ $ticket->equipo->serie }}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <th class="p-0 text-start" valign="top" style="width: 100px;line-height: 10px;">
                                        DESCRIPCIÓN DEL EQUIPO</th>
                                    <td class="p-0 text-justify align-top text-9"
                                        colspan="{{ empty($ticket->equipo->serie) ? 5 : 7 }}">
                                        : {{ $ticket->equipo->descripcion }}</td>
                                </tr>
                            </thead>
                        </table>
                    </td>
                </tr>
            </table>
        @endif

        @if ($ticket->direccion)
            <table class="table p-0 m-0 mt-5" style="border: 1.5px solid #222 !important;">
                <tr>
                    <td class="p-0 m-0">
                        <table class="table border-white table-responsive p-0 m-0 text-10 font-normal">
                            <thead>
                                <tr>
                                    <th class="p-0 align-top text-start" style="width: 100px;line-height: 10px;">
                                        LUGAR DE ATENCIÓN</th>
                                    <td class="p-0 text-start align-top text-9" colspan="3">
                                        : {{ $ticket->direccion->name }}
                                        @if ($ticket->direccion->referencia)
                                            <p class="p-0 m-0 text-9" style="padding: 0;">
                                                {{ $ticket->direccion->referencia }}
                                            </p>
                                        @endif
                                        {{ $ticket->direccion->ubigeo->distrito }},
                                        {{ $ticket->direccion->ubigeo->provincia }},
                                        {{ $ticket->direccion->ubigeo->region }}
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </td>
                </tr>
            </table>
        @endif

        {{-- <table class="table mt-5">
            <tr>
                <td></td>
                <td class="p-0" style="border-bottom: 1.5px solid #222 !important;padding: 3.5rem 0rem;"></td>
                <td></td>
                <td class="p-0" style="border-bottom: 1.5px solid #222 !important;padding: 3.5rem 0rem;"></td>
                <td></td>
                <td class="p-0 m-0" align="right" valign="bottom" style="width: 120px;padding:0 !important;">
                    <img style="padding:1mm;border:1.5px solid #222;"
                        src="data:image/svg;base64, {!! base64_encode(QrCode::format('svg')->size(100)->generate($qr)) !!} ">
                </td>
            </tr>
            <tr>
                <td class="" style="padding:0 !important;"></td>
                <td class="text-center text-10 font-bold" style="width: 200px;padding:0 !important;">
                    FIRMA TÉCNICO RESPONSABLE</td>
                <td class="" style="padding:0 !important;"></td>
                <td class="text-center text-10 font-bold" style="width: 200px;padding:0 !important;">
                    FIRMA CLIENTE</td>
                <td class="" style="padding:0 !important;"></td>
                <td></td>
            </tr>
        </table> --}}
    </div>
</body>

</html>
