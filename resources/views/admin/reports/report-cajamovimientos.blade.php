<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ $titulo }}
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
        margin: 3cm 0cm 1cm 0cm;
    }

    #header {
        position: fixed;
        top: -3cm;
        left: 0;
        width: 100%;
        height: 2.5cm;
        background: #05040f;
        color: #FFF;
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
        display: block;
        width: 100%;
        max-width: 6cm;
        height: auto;
        max-height: 2cm;
        object-fit: scale-down;
        object-position: center;
    }

    #footer {
        position: fixed;
        width: 100%;
        bottom: -2.75cm;
        left: 0;
    }

    .title-report {
        color: #0FB9B9;
        font-size: 1rem;
        text-align: center;
        font-weight: 700;
        line-height: 0;
        margin: 20px 0;
    }

    .body {
        padding: 10px;
        font-weight: 200;
    }

    .body>table tbody tr:nth-child(even).row-striped {
        background-color: #e2e2e2;
    }

    .body>table tbody tr:nth-child(odd).row-striped {
        background-color: #fff;
    }


    .body .border-table {
        border: 0.25px solid #222 !important;
    }

    p {
        margin: 0;
        padding: 0 10px;
    }

    .leading-none {
        line-height: 0.55rem;
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

    .align-bottom {
        vertical-align: bottom !important;
    }

    .align-middle {
        vertical-align: middle !important;
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

    .border-table {
        border: 0.5px solid #222 !important;
    }

    .border-l-table {
        border-left: 0.5px solid #222;
    }

    .border-r-table {
        border-right: 0.5px solid #222;
    }

    .border {
        border: 1px solid #222 !important;
    }

    .border-2 {
        border: 2px solid #222 !important;
    }

    .p-0 {
        padding: 0 !important;
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

    .p-4 {
        padding: 8px;
    }

    .p-5 {
        padding: 10px;
    }

    .rounded {
        border-radius: 2rem;
    }

    .m-0 {
        margin: 0 !important;
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

    .row-striped {
        background: #fafafa;
    }

    .ingreso {
        color: #00720f;
    }

    .egreso {
        color: #F50000;
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
    <div id="header" class="">
        <table class="table" style="padding: 0.25cm 1cm">
            <thead>
                <tr class="text-start">
                    <th class="text-start " style="width: 6cm;" rowspan="2">
                        @if (!empty($url_logo))
                            <img src="{{ imageBase64($url_logo) }}" alt="{{ $empresa->name }}" class="image" />
                        @endif
                    </th>
                    <th class="align-top">
                        <p class="font-bold text-14 leading-4 text-end p-0">
                            {{ $empresa->name }}</p>

                        <p class="font-normal text-10 leading-3 text-end p-0">
                            {{ $empresa->direccion }}
                        </p>

                        @if ($empresa->ubigeo)
                            <p class="font-normal text-10 leading-3 text-end p-0">
                                {{ $empresa->ubigeo->region }}
                                - {{ $empresa->ubigeo->provincia }}
                                - {{ $empresa->ubigeo->distrito }}
                            </p>
                        @endif
                        {{-- <p class="font-bold text-14 leading-4 text-end">
                            {{ $titulo }}</p> --}}
                    </th>
                </tr>
                <tr>
                    <th class="align-bottom">
                        <p class="font-bold text-14 leading-4 text-end p-0">
                            {{-- {{ $titulo }} --}}
                            {!! nl2br($titulo) !!}
                        </p>
                    </th>
                </tr>
            </thead>
        </table>
    </div>

    <div id="footer" class="">
        <table class="table" style="padding: 0 10px; border-collapse: separate;">
            <thead>
                @if (!empty($empresa->web))
                    <tr>
                        <td colspan="2" class="text-center text-12 leading-3 font-normal">
                            {{ $empresa->web }}</td>
                    </tr>
                @endif
            </thead>
        </table>
    </div>

    <div class="body" style="padding: 0 1cm;">
        {{-- <h1 class="title-report">{{ $titulo }}</h1> --}}

        @if ($viewconsolidado)
            @php
                $chunknumber = 4;
            @endphp
            @if (count((array) $consolidado) > 0)
                @foreach ($consolidado as $m => $item)
                    <table class="table border-table text-12" style="margin-top: 30px;">
                        <thead>
                            <tr>
                                <th colspan="4" class="font-medium text-center p-3 leading-3">
                                    {{ $item->moneda->currency }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $chunks = collect($item->methodpayments)->chunk($chunknumber);
                            @endphp
                            @foreach ($chunks as $methodpayments)
                                <tr class="">
                                    @foreach ($methodpayments as $k => $itemmethod)
                                        <td class="font-medium text-start p-0" style="width: 100%">
                                            <table class="table text-12 p-0 m-0">
                                                <tr class="border-table">
                                                    <th colspan="2" class="font-medium p-2 align-middle"
                                                        style="background: #f1f1f1;">
                                                        {{ $itemmethod->methodpayment->name }}
                                                    </th>
                                                </tr>

                                                @foreach ($itemmethod->totales as $key => $total)
                                                    <tr class="border-table">
                                                        <td class="p-2 text-10"
                                                            style="width: 50px;border-right: 0.5px solid #000;">
                                                            {{ $key }}
                                                        </td>
                                                        <td class="p-2 text-end text-14 font-bold">
                                                            <small
                                                                class="font-normal text-10">{{ $item->moneda->simbolo }}</small>
                                                            {{ number_format($total, 2, '.', ', ') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </td>
                                    @endforeach
                                    @for ($i = count($methodpayments); $i < $chunknumber; $i++)
                                        <td class="font-medium text-start p-0" style="width: 100%">
                                            <table class="table text-12 p-0 m-0">
                                                <tr class="border-table">
                                                    <th colspan="2" class="font-medium p-2"
                                                        style="background: #f1f1f1;">
                                                        &nbsp;
                                                    </th>
                                                </tr>
                                                @for ($j = 0; $j < 3; $j++)
                                                    <tr class="">
                                                        <td class="font-medium p-2 text-10">
                                                            &nbsp;
                                                        </td>
                                                        <td class="p-2 text-end text-14 font-bold" style="border-right: 0.5px solid #000;">
                                                            &nbsp;
                                                        </td>
                                                    </tr>
                                                @endfor
                                            </table>
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            @endif
        @else
            <table class="table text-10">
                <thead style="background: #CCC">
                    <tr class="border-table">
                        @if (!in_array('date', $hiddencolums))
                            <th class="font-bold p-2 text-center">FECHA</th>
                        @endif
                        <th class="font-bold p-2 text-center">MONTO</th>
                        <th class="font-bold p-2 text-center leading-none">TIPO DE CAMBIO</th>

                        @if (!in_array('typemovement', $hiddencolums))
                            <th class="font-bold p-2 text-center">TIPO MOVIM.</th>
                        @endif

                        @if (!in_array('methodpayment', $hiddencolums))
                            <th class="font-bold p-2 text-center leading-none">FORMA DE PAGO</th>
                        @endif

                        <th class="font-bold p-2 text-center">CONCEPTO</th>
                        {{-- <th class="font-bold p-2 text-center">REFERENCIA</th> --}}
                        {{-- <th class="font-bold p-2 text-center">DETALLE</th> --}}
                        {{-- <th class="font-bold p-2 text-center">CAJA</th> --}}
                        {{-- <th class="font-bold p-2 text-center">CAJA MENSUAL</th> --}}
                        @if (in_array('sucursal', $hiddencolums))
                            <th class="font-bold p-2 text-center">REFERENCIA</th>
                        @endif
                        @if (!in_array('sucursal', $hiddencolums))
                            <th class="font-bold p-2 text-center">SUCURSAL</th>
                        @endif
                        @if (in_array('sucursal', $hiddencolums))
                            <th class="font-bold p-2 text-center">USUARIO</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if (count($cajamovimientos) > 0)
                        @foreach ($cajamovimientos as $item)
                            <tr class="border-table row-striped font-normal text-10">
                                @if (!in_array('date', $hiddencolums))
                                    <td class="p-2 text-center align-middle leading-none" style="width: 80px;">
                                        {{ formatDate($item->date, 'DD MMMM Y') }}
                                    </td>
                                @endif
                                <td class="p-2 text-center" style="width: 90px;">
                                    {{ $item->moneda->simbolo }}
                                    {{ number_format($item->amount, 2, '.', ', ') }}</td>
                                    
                                <td class="p-2 text-center" style="width: 50px;">
                                    @if ($item->tipocambio > 0)
                                        {{ decimalOrInteger($item->tipocambio) }}
                                    @endif
                                </td>

                                @if (!in_array('typemovement', $hiddencolums))
                                    <td class="p-2 text-center {{ $item->isIngreso() ? 'ingreso' : 'egreso' }}"
                                        style="width: 50px;">
                                        {{ $item->typemovement->value }}</td>
                                @endif

                                @if (!in_array('methodpayment', $hiddencolums))
                                    <td class="p-3 text-center align-middle" style="width: 70px;">
                                        {{ $item->methodpayment->name }}</td>
                                @endif

                                <td class="p-2 text-center align-middle leading-none" style="width: 100px;">
                                    {{ $item->concept->name }}</td>
                                {{-- <td class="p-2 text-center align-middle" style="width: 80px;">
                                {{ $item->referencia }}</td> --}}
                                {{-- <td class="p-2 text-center align-middle leading-none" style="width: 80px;">
                                {!! nl2br($item->detalle) !!}</td> --}}
                                {{-- <td class="p-2 text-center align-middle leading-none" style="width: 80px;">
                                {{ $item->openbox->box->name }}</td> --}}
                                {{-- <td class="p-2 text-center align-middle leading-none" style="width: 80px;">
                                {{ $item->monthbox->name }}</td> --}}

                                @if (in_array('sucursal', $hiddencolums))
                                    <td class="p-2 text-center align-middle leading-none"
                                        style="max-width: 130px;min-width: 80px;">
                                        {{ $item->referencia }}</td>
                                @endif
                                @if (!in_array('sucursal', $hiddencolums))
                                    <td class="p-2 text-center align-middle leading-none">
                                        {{ $item->sucursal->name }}</td>
                                @endif
                                @if (in_array('sucursal', $hiddencolums))
                                    <td class="p-2 text-center align-middle leading-none">
                                        {{ $item->user->name }}</td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

            @if (count($aperturas))
                <h1 class="font-bold text-14 leading-4 text-center p-0 m-0" style="margin-top: 30px !important;">
                    HISTORIAL DE APERTURA DE CAJAS</h1>

                <table class="table text-10" style="margin-top: 5px;">
                    <tbody>
                        @foreach ($aperturas as $item)
                            <tr class="border-table row-striped font-normal text-10">
                                @if (!in_array('date', $hiddencolums))
                                    <td class="p-2 text-center align-middle leading-none" style="width: 80px;">
                                        {{ formatDate($item->date, 'DD MMMM Y') }}
                                    </td>
                                @endif
                                <td class="p-2 text-center" style="width: 80px;">
                                    {{ $item->moneda->simbolo }}
                                    {{ number_format($item->amount, 2, '.', ', ') }}</td>
                                {{-- <td class="p-2 text-center" style="width: 50px;">
                                    {{ decimalOrInteger($item->tipocambio) }}</td> --}}

                                {{-- @if (!in_array('typemovement', $hiddencolums))
                                    <td class="p-2 text-center {{ $item->isIngreso() ? 'ingreso' : 'egreso' }}"
                                        style="width: 50px;">
                                        {{ $item->typemovement->value }}</td>
                                @endif --}}

                                <td class="p-3 text-center align-middle" style="width: 60px;">
                                    {{ $item->methodpayment->name }}</td>
                                {{-- <td class="p-2 text-center align-middle leading-none" style="width: 100px;">
                                    {{ $item->concept->name }}</td> --}}
                                @if (!in_array('sucursal', $hiddencolums))
                                    <td class="p-2 text-center align-middle leading-none">
                                        {{ $item->sucursal->name }}</td>
                                @endif
                                @if (in_array('sucursal', $hiddencolums))
                                    <td class="p-2 text-center align-middle leading-none" style="width: 90px;">
                                        {{ $item->user->name }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif

        @if (count($cajamovimientos) > 0)
            <table class="table text-10" style="margin-top: 30px;">
                <tbody>
                    @foreach ($sumatorias as $k => $item)
                        <tr class="border-table">
                            <td class="font-medium text-start p-3 leading-3">
                                TOTAL <br> APERTURAS :</td>
                            <td class="font-bold text-end text-14 p-3 leading-3 align-middle"
                                style="border-right: 0.5px solid #000;">
                                <small class="font-normal text-10">{{ $item->moneda->simbolo }}</small>
                                {{ number_format($aperturas->where('moneda_id', $item->moneda->id)->sum('amount'), 2, '.', ', ') }}
                                @if (!in_array('moneda', $hiddencolums))
                                    <small class="font-normal text-9">{{ $item->moneda->currency }}</small>
                                @endif
                            </td>

                            @foreach ($item->totales as $key => $total)
                                <td class="font-medium text-start p-3 leading-3 align-middle">
                                    TOTAL <br> {{ $key }} :</td>
                                <td class="font-bold text-end text-14 p-3 leading-3 align-middle"
                                    style="border-right: 0.5px solid #000;">
                                    <small class="font-normal text-10">{{ $item->moneda->simbolo }}</small>
                                    {{ number_format($total, 2, '.', ', ') }}
                                    @if (!in_array('moneda', $hiddencolums))
                                        <small class="font-normal text-9">{{ $item->moneda->currency }}</small>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <script type="text/php">
        if ( isset($pdf) ) {
            $size = 8;
            $color = array(0,0,0);
            if (class_exists('Font_Metrics')) {
                $font = Font_Metrics::get_font("Ubuntu");
                $text_height = Font_Metrics::get_font_height($font, $size);
                $width = Font_Metrics::get_text_width("Página 1 de 2", $font, $size);
            } elseif (class_exists('Dompdf\\FontMetrics')) {
                $font = $fontMetrics->getFont("Ubuntu");
                $text_height = $fontMetrics->getFontHeight($font, $size);
                $width = $fontMetrics->getTextWidth("Página 1 de 2", $font, $size);
            }
                          
            $w = $pdf->get_width();
            $h = $pdf->get_height();
            $y = $h - $text_height - 24;
            
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";  
            $pdf->page_text($w / 2 - $width / 2, $y + 6, $text, $font, $size, $color);
        }
    </script>
</body>

</html>
