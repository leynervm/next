<!DOCTYPE html>
<html lang="es">

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
        width: auto;
        max-width: 100%;
        height: 2cm;
    }

    .image img {
        display: block;
        width: auto;
        max-width: 8cm;
        height: 100%;
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

    .page-break {
        page-break-after: always;
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
                <tr class="align-middle text-start">
                    <th class="text-start" rowspan="2">
                        @if (!empty($url_logo))
                            <div class="image">
                                <img src="{{ imageBase64($url_logo) }}" alt="{{ $empresa->name }}" class="" />
                            </div>
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
                            {{ $titulo }}</p>
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
        @if (count($payments) > 0)
            <table class="table text-10">
                <thead style="background: #CCC">
                    <tr class="border-table">
                        <th class="font-bold p-2 text-center leading-none">NOMBRES DEL PERSONAL</th>
                        <th class="font-bold p-2 text-center">MES PAGO</th>
                        <th class="font-bold p-2 text-center">SUELDO</th>
                        <th class="font-bold p-2 text-center leading-none">ADELANTOS</th>
                        <th class="font-bold p-2 text-center">DSCTOS</th>
                        <th class="font-bold p-2 text-center leading-none">BONOS</th>
                        <th class="font-bold p-2 text-center">TOTAL PAGADO</th>
                        <th class="font-bold p-2 text-center">SALDO</th>
                        <th class="font-bold p-2 text-center">SUCURSAL</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $saldos = 0;
                        $sueldos = 0;
                    @endphp
                    @foreach ($payments as $item)
                        @php
                            $montopagado = $item->employer->sueldo - $item->descuentos;
                            $sueldo_pagar = $item->employer->sueldo + $item->bonus - $item->descuentos;
                            $saldo = $sueldo_pagar - ($item->amount + $item->adelantos);
                            $saldos = $saldos + $saldo;
                            $sueldos = $sueldos + $item->employer->sueldo;
                        @endphp
                        <tr class="border-table row-striped font-normal text-10">
                            <td class="p-2 text-justify align-middle leading-none" style="width: 110px;">
                                {{ $item->employer->name }}</td>
                            <td class="p-2 text-center align-middle leading-none" style="width: 80px;">
                                {{ formatDate($item->month, 'MMMM Y') }}
                            </td>
                            <td class="p-2 text-center" style="width: 80px;">
                                {{-- {{ $item->moneda->simbolo }} --}}
                                S/.
                                {{ number_format($item->employer->sueldo, 2, '.', ', ') }}
                            </td>
                            <td class="p-2 text-center" style="width: 50px;">
                                {{ number_format($item->adelantos, 2, '.', ', ') }}
                            </td>
                            <td class="p-2 text-center {{ $item->descuentos > 0 ? 'egreso' : '' }}"
                                style="width: 50px;">
                                {{ number_format($item->descuentos, 2, '.', ', ') }}
                            </td>
                            <td class="p-2 text-center {{ $item->bonus > 0 ? 'ingreso' : '' }}" style="width: 50px;">
                                {{ number_format($item->bonus, 2, '.', ', ') }}
                            </td>
                            <td class="p-2 text-center" style="width: 80px;">
                                {{-- {{ $item->moneda->simbolo }} --}}
                                S/.
                                {{ number_format($item->amount + $item->adelantos, 2, '.', ', ') }}
                            </td>
                            <td class="p-2 text-center {{ $saldo > 0 ? 'egreso' : '' }}" style="width: 50px;">
                                @if ($saldo > 0)
                                    S/. {{ number_format($saldo, 2, '.', ', ') }}
                                @endif
                            </td>
                            <td class="p-2 text-center align-middle leading-none">
                                {{ $item->employer->sucursal->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="table text-10" style="margin-top: 30px;">
                <tbody>
                    <tr class="border-table text-10">
                        <td class="p-5 font-medium leading-none text-end" style="width: 400px;" colspan="7">
                            DESCUENTOS TOTALES :
                        </td>
                        <td class="p-5 font-medium leading-none text-end text-14" colspan="2"
                            style="border-right: 0.5px solid #000;">
                            <small class="font-normal text-10">S/. </small>
                            {{ number_format($payments->sum('descuentos'), 2, '.', ', ') }}
                        </td>
                    </tr>
                    <tr class="border-table text-10">
                        <td class="p-5 font-medium leading-none text-end" style="" colspan="7">
                            TOTAL ADELANTOS :
                        </td>
                        <td class="p-5 font-medium leading-none  text-end text-14" colspan="2"
                            style="border-right: 0.5px solid #000;;">
                            <small class="font-normal text-10">S/. </small>
                            {{ number_format($payments->sum('adelantos'), 2, '.', ', ') }}
                        </td>
                    </tr>
                    <tr class="border-table text-10">
                        <td class="p-5 font-medium leading-none text-end" colspan="7" style="">
                            TOTAL BONOS :</td>
                        <td class="p-5 font-medium leading-none  text-end text-14" colspan="2"
                            style="border-right: 0.5px solid #000;">
                            <small class="font-normal text-10">S/. </small>
                            {{ number_format($payments->sum('bonos'), 2, '.', ', ') }}
                        </td>
                    </tr>

                    <tr class="border-table text-10">
                        <td class="p-5 font-medium leading-none text-end" colspan="7">
                            TOTAL A PAGAR (SIN APLICAR CARGOS):</td>
                        <td class="p-5 font-medium leading-none  text-end text-14" colspan="2"
                            style="border-right: 0.5px solid #000;">
                            <small class="font-normal text-10">S/. </small>
                            {{ number_format($sueldos, 2, '.', ', ') }}
                        </td>
                    </tr>
                    <tr class="border-table text-10">
                        <td class="p-5 font-medium leading-none text-end" colspan="7">
                            TOTAL A PAGAR (APLICANDO CARGOS):</td>
                        <td class="p-5 font-medium leading-none  text-end text-14" colspan="2"
                            style="border-right: 0.5px solid #000;">
                            <small class="font-normal text-10">S/. </small>
                            {{ number_format($sueldos + $payments->sum('bonos') - $payments->sum('descuentos'), 2, '.', ', ') }}
                        </td>
                    </tr>
                    <tr class="border-table text-10">
                        <td class="p-5 font-medium leading-none text-end" colspan="7">
                            PAGO PARCIAL :</td>
                        <td class="p-5 font-medium leading-none  text-end text-14" colspan="2"
                            style="border-right: 0.5px solid #000;">
                            <small class="font-normal text-10">S/. </small>
                            {{ number_format($payments->sum('amount') + $payments->sum('adelantos'), 2, '.', ', ') }}
                        </td>
                    </tr>
                    <tr class="border-table text-10">
                        <td class="p-5 font-medium leading-none text-end" colspan="7">
                            SALDOS :</td>
                        <td class="p-5 font-medium leading-none  text-end text-14 {{ $saldos > 0 ? 'egreso' : '' }}"
                            colspan="2" style="border-right: 0.5px solid #000;">
                            <small class="font-normal text-10">S/. </small>
                            {{ number_format($saldos, 2, '.', ', ') }}
                        </td>
                    </tr>
                    {{-- <tr class="border-table text-10">
                        <td class="p-5 font-medium leading-none text-end" colspan="7">
                            AHORRADO :</td>
                        <td class="p-5 font-medium leading-none  text-end text-14" colspan="2"
                            style="border-right: 0.5px solid #000;">
                            <small class="font-normal text-10">S/. </small>
                            {{ number_format($sueldos - ($sueldos + $payments->sum('bonos') - $payments->sum('descuentos')), 2, '.', ', ') }}
                        </td>
                    </tr> --}}
                </tbody>
            </table>

            {{-- <table class="table text-10" style="margin-top: 30px;">
                <tbody>
                    <tr class="border-table text-10">
                        <td class="p-5 font-medium leading-none text-left" style="width: 120px;">DESCUENTOS TOTALES :
                        </td>
                        <td class="p-5 font-medium leading-none text-end text-14"
                            style="border-right: 0.5px solid #000;width: 100px;">
                            <small class="font-normal text-10">S/. </small>
                            {{ number_format($payments->sum('descuentos'), 2, '.', ', ') }}
                        </td>
                        <td class="p-5 font-medium leading-none text-left" style="width: 110px;">TOTAL ADELANTOS :
                        </td>
                        <td class="p-5 font-medium leading-none  text-end text-14"
                            style="border-right: 0.5px solid #000;width: 100px;">
                            <small class="font-normal text-10">S/. </small>
                            {{ number_format($payments->sum('adelantos'), 2, '.', ', ') }}
                        </td>
                        <td class="p-5 font-medium leading-none text-left" style="width: 81px;">TOTAL BONOS :</td>
                        <td class="p-5 font-medium leading-none  text-end text-14"
                            style="border-right: 0.5px solid #000;width: 90px;">
                            <small class="font-normal text-10">S/. </small>
                            {{ number_format($payments->sum('bonos'), 2, '.', ', ') }}
                        </td>
                    </tr>
                    <tr class="border-table text-10">
                        <td class="p-5 font-medium leading-none text-left">TOTAL A PAGAR :</td>
                        <td class="p-5 font-medium leading-none  text-end text-14"
                            style="border-right: 0.5px solid #000;">
                            <small class="font-normal text-10">S/. </small>
                            {{ number_format($payments->sum('amount') + $saldos, 2, '.', ', ') }}
                        </td>
                        <td class="p-5 font-medium leading-none text-left">TOTAL PAGADO :</td>
                        <td class="p-5 font-medium leading-none  text-end text-14"
                            style="border-right: 0.5px solid #000;">
                            <small class="font-normal text-10">S/. </small>
                            {{ number_format($payments->sum('amount'), 2, '.', ', ') }}
                        </td>
                        <td class="p-5 font-medium leading-none text-left">SALDOS :</td>
                        <td class="p-5 font-medium leading-none  text-end text-14 {{ $saldos > 0 ? 'egreso' : '' }}"
                            style="border-right: 0.5px solid #000;">
                            <small class="font-normal text-10">S/. </small>
                            {{ number_format($saldos, 2, '.', ', ') }}
                        </td>
                    </tr>
                </tbody>
            </table> --}}
        @endif

        @if (count($adelantos) > 0)
            @if (count($payments) > 4)
                <div class="page-break"></div>
            @endif

            <table class="table text-10" @if (count($payments) > 0 && count($payments) <= 4) style="margin-top: 80px;" @endif>
                <thead style=""">
                    <tr class="">
                        <th class="font-bold text-14 leading-4 text-center p-2">HISTORIAL DE ADELANTOS</th>
                        {{-- <th class="font-bold p-2 text-center">SUCURSAL</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($adelantos as $item)
                        <tr class=" {{-- row-striped --}} font-normal text-10">
                            <td class="align-middle leading-none">
                                <table class="table border-table text-10 p-0" style="margin-top: 20px;">
                                    <thead>
                                        <tr class="border-table">
                                            <th class="p-2" style="text-align: left;">
                                                {{ $item->name }}</th>
                                            <th class="p-2 text-end font-normal">
                                                {{ $item->sucursal->name }} </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="font-normal text-10 border-table">
                                            <td class="" colspan="2">
                                                <table class="table">
                                                    <tbody>
                                                        <tr class="">
                                                            @foreach ($item->cajamovimientos as $adelanto)
                                                                <td class="align-middle p-2 {{ count($item->cajamovimientos) > 1 ? 'text-center' : '' }}"
                                                                    @if (!$loop->last) style="border-right: 0.5px solid #000;" @endif>
                                                                    {{ formatDate($adelanto->date, 'ddd DD MMMM Y') }}
                                                                    <br>
                                                                    {{ $adelanto->methodpayment->name }} <br>

                                                                    <p class="font-bold text-12"
                                                                        style="padding: 5px 0;">
                                                                        {{ number_format($adelanto->amount, 2, '.', ', ') }}
                                                                    </p>
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr class="">
                                            <td class="p-3 text-start ">TOTAL</td>
                                            <td class="p-3 text-end text-14 font-bold">
                                                {{ number_format($item->cajamovimientos->sum('amount'), 2, '.', ', ') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
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
