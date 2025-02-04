<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        LIBRO DE RECLAMACIÓN - {{ $claimbook->serie }}-{{ $claimbook->correlativo }}
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

    @page {
        margin: 4cm 1cm 4.5cm 1cm;
    }

    * {
        font-family: 'Ubuntu';
    }

    #header {
        position: fixed;
        top: -3cm;
        left: 0;
        width: 100%;
        height: 27.5cm;
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

    .mt-4 {
        margin-top: 8px !important;
    }

    .mt-5 {
        margin-top: 10px !important;
    }

    .mt-6 {
        margin-top: 18px !important;
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

    .block {
        display: block;
    }

    .w-full {
        width: 100%;
    }
</style>

<body>
    @php
        $empresa = view()->shared('empresa');
        $logoimpresion = $empresa->logoimpresion;
        $logofooter = $empresa->logofooter;
        $url_logo = $empresa->logo;
        // if (!empty($logoimpresion)) {
        //     $url_logo = $logoimpresion;
        // } else {
        //     if (!empty($logofooter)) {
        //         $url_logo = $logofooter;
        //     }
        // }
    @endphp

    <div id="header" class="border">
        <table class="table">
            <thead>
                <tr class="align-baseline">
                    <th style="text-align: left;">
                        @if (!empty($url_logo))
                            <div class="">
                                <img src="{{ imageBase64($url_logo) }}" alt="{{ $empresa->name }}" class="image" />
                            </div>
                        @endif
                    </th>
                    <th class="align-baseline" style="padding: 0 2px;">
                        <p class="font-bold text-14 leading-4" style="margin:0;">
                            {{ $empresa->name }}</p>

                        <p class="font-normal text-10 leading-3">
                            {{ $empresa->direccion }}
                        </p>

                        @if ($empresa->ubigeo)
                            <p class="font-normal text-10 leading-3">
                                {{ $empresa->ubigeo->region }}
                                - {{ $empresa->ubigeo->provincia }}
                                - {{ $empresa->ubigeo->distrito }}
                            </p>
                        @endif

                        @if (count($empresa->telephones) > 0)
                            <p class="font-normal" style="font-size: 10px; line-height: .7rem;">
                                TELÉFONO:
                                <span
                                    class="font-normal">{{ formatTelefono($empresa->telephones->first()->phone) }}</span>
                            </p>
                        @endif

                    </th>
                    <th class="serie border-2" style="vertical-align:middle; ">
                        <p class="font-bold text-14 leading-7">
                            {{ $empresa->document }}</p>
                        <p class="font-bold text-14 leading-7">
                            LIBRO DE RECLAMACIÓN
                        </p>
                        <p class="font-bold text-14 leading-7">{{ $claimbook->serie }}-{{ $claimbook->correlativo }}
                        </p>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
    @php
        $bottom = !empty($empresa->web) ? '-3.4cm' : '-3cm';
    @endphp
    <div id="footer" class="" style="bottom: {{ $bottom }};">
        <table class="table" style="padding: 0 10px; border-collapse: separate;">
            <thead>
                <tr class="align-middle">
                    <td class="title border" style="">
                        <p class="font-normal text-12 leading-3 mt-2 text-center">
                            © <span class="font-medium">{{ $empresa->name }}</span> 2012 - Todos los derechos
                            reservados.</p>

                    </td>
                </tr>
                @if (!empty($empresa->web))
                    <tr>
                        <td colspan="2" class="text-center text-12 leading-3 font-normal">
                            {{ $empresa->web }}</td>
                    </tr>
                @endif
            </thead>
        </table>
    </div>

    <div class="body">
        <table class="table mt-3 text-10">
            <tbody>
                <tr>
                    <td class="p-1 font-bold" style="width: 100px">
                        FECHA REGISTRO </td>
                    <td class="p-1 font-normal">
                        : {{ formatDate($claimbook->date, 'DD/MM/Y') }}</td>
                </tr>
                <tr>
                    <td class="p-1 font-bold" style="width: 100px">
                        CANAL DE VENTA </td>
                    <td class="p-1 font-normal">
                        : {{ $claimbook->channelsale }}
                    </td>
                </tr>

                @if ($claimbook->sucursal)
                    <tr>
                        <td class="p-1 font-bold align-baseline" style="width: 100px">
                            PUNTO VENTA </td>
                        <td class="p-1 font-normal">
                            : {{ $claimbook->sucursal->name }}
                            <br>
                            <p class="font-normal text-start" style="padding: 0;">
                                {{ $claimbook->sucursal->direccion }} -
                                {{ $claimbook->sucursal->ubigeo->distrito }} /
                                {{ $claimbook->sucursal->ubigeo->provincia }} /
                                {{ $claimbook->sucursal->ubigeo->region }}
                            </p>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td class="p-1 font-bold" style="width: 100px;">
                        PEDIDO </td>
                    <td class="p-1 font-normal">
                        : {{ $claimbook->pedido }}</td>
                </tr>
            </tbody>
        </table>

        <table class="table mt-3 text-10 border-2">
            <tbody>
                <tr class="">
                    <td colspan="2" class="font-bold text-center p-2">
                        DATOS DE LA PERSONA DEL RECLAMO </td>
                </tr>
                <tr>
                    <td class="p-1 font-bold align-baseline" style="width: 50%">
                        NOMBRE COMPLETO
                        <p class="font-normal" style="padding: 0;">
                            {{ $claimbook->document }} / {{ $claimbook->name }}</p>
                    </td>
                    <td class="p-1 font-bold align-baseline" style="width: 50%">
                        DIRECCIÓN
                        <p class="font-normal" style="padding: 0;">
                            {{ $claimbook->direccion }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="p-1 font-bold align-baseline">
                        TELÉFONO
                        <p style="padding: 0;" class="font-normal">
                            {{ formatTelefono($claimbook->telephono) }}</p>
                    </td>
                    <td class="p-1 font-bold align-baseline">
                        CORREO
                        <p style="padding: 0;" class="font-normal">{{ $claimbook->email }}</p>
                    </td>
                </tr>
            </tbody>
        </table>

        @if ($claimbook->isMenorEdad())
            <table class="table mt-6 text-10 border-2">
                <tbody>
                    <tr class="">
                        <td colspan="2" class="font-bold text-center p-2">
                            DATOS DEL APODERADO </td>
                    </tr>
                    <tr>
                        <td class="p-1 font-bold align-baseline" style="width: 50%">
                            NOMBRE COMPLETO
                            <p class="font-normal" style="padding: 0;">
                                {{ $claimbook->document }} / {{ $claimbook->name_apoderado }}</p>
                        </td>
                        <td class="p-1 font-bold align-baseline" style="width: 50%">
                            DIRECCIÓN
                            <p class="font-normal" style="padding: 0;">
                                {{ $claimbook->direccion_apoderado }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-1 font-bold align-baseline">
                            TELÉFONO
                            <p style="padding: 0;" class="font-normal">
                                {{ formatTelefono($claimbook->telefono_apoderado) }}</p>
                        </td>
                        <td>

                        </td>
                    </tr>
                </tbody>
            </table>
        @endif

        <table class="table mt-6 text-10 border-2">
            <tbody>
                <tr class="">
                    <td colspan="2" class="font-bold text-center p-2" style="width: 100px">
                        DETALLE DEL RECLAMO </td>
                </tr>
                <tr>
                    <td class="p-1 font-bold align-baseline">
                        BIEN CONTRATADO :
                        <p class="font-normal" style="padding: 0;">{{ $claimbook->biencontratado }}</p>
                    </td>
                    <td class="p-1 font-bold">
                        DESCRIPCION PRODUCTO / SERVICIO :
                        <p class="font-normal" style="padding: 0;">{{ $claimbook->descripcion_producto_servicio }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td class="p-1 font-bold align-baseline">
                        TIPO RECLAMO :
                        <p class="font-normal" style="padding: 0;">{{ $claimbook->tipo_reclamo }}</p>
                    </td>
                    <td class="p-1 font-bold">
                        DETALLE :
                        <p class="font-normal" style="padding: 0;">{{ $claimbook->detalle_reclamo }}</p>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- <p class="font-light">LIGHT</p>
        <p class="font-normal">NORMAL</p>
        <p class="font-medium">MEDIUM</p>
        <p class="font-bold">BOLD</p> --}}

        {{-- @for ($i = 1; $i < 100; $i++)
            <div style="background:#7e7e7e; margin:2px; font-size: 11px">
                <p class="font-normal">{{ $i }}</p>
            </div>
        @endfor --}}
    </div>
</body>

</html>
