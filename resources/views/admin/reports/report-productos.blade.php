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

    .picture-producto {
        width: auto;
        max-width: 5cm;
        height: auto;
        max-height: 5cm;
        margin: auto;
        text-align: center;
    }

    .picture-producto img {
        text-align: center;
        display: block;
        width: auto;
        max-width: 5cm;
        height: auto;
        max-height: 100%;
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
        @if (count($productos) > 0)
            <table class="table text-10 font-normal">
                {{-- @if (!$detallado) --}}
                {{-- <thead style="background: #CCC">
                    <tr class="border-table">
                        @if ($detallado)
                            <th class="font-bold p-2 text-center">IMÁGEN</th>
                        @endif
                        <th class="font-bold p-2 text-center">DESCRIPCIÓN DEL PRODUCTO</th>
                        <th class="font-bold p-2 text-center">CATEGORÍA</th>
                        <th class="font-bold p-2 text-center leading-none">PRECIO DE COMPRA</th>

                        @if ($empresa->usarLista())
                            @foreach ($pricetypes as $pricetype)
                                <th class="font-bold p-2 text-center leading-none">{{ $pricetype->name }}</th>
                            @endforeach
                        @else
                            <th class="font-bold p-2 text-center leading-none">PRECIO DE VENTA</th>
                        @endif

                        <th class="font-bold p-2 text-center">STOCK</th>
                    </tr>
                </thead> --}}
                <tbody>
                    @foreach ($productos as $item)
                        @php
                            $image = $item->imagen;
                            if (!empty($image)) {
                                $image = $item->imagen->url;
                            }
                        @endphp
                        <tr class="">
                            <td class="border-table p-2 text-center align-middle leading-none text-10 font-semibold">
                                @if ($detallado)
                                    @if (!empty($image))
                                        <div class="picture-producto">
                                            <img src="{{ imageBase64($image, 'app/public/images/productos/') }}"
                                                alt="{{ $item->imagen->url }}">
                                        </div>
                                    @else
                                        <p class="text-10 text-10" style="color:#000;">IMÁGEN NO DISPONIBLE</p>
                                    @endif
                                @endif
                                <br>
                                <p class="font-medium text-justify">{{ count($productos) }}- {{ $item->name }}</p>
                                {{-- @if ($detallado && $item->isPublicado())
                                    <span class="text-9 font-medium"
                                        style="background: #0fb9b9; color:white;display: inline-block; border-radius: 0.35rem;padding: 4px 8px;">
                                        TIENDA WEB</span>
                                @endif --}}
                            </td>
                            <td class="border-table" valign="top" style="width: 450px;">
                                <table class="table font-normal text-10">
                                    <tbody>
                                        <tr class="" style="border-bottom: 1px solid #222;">
                                            <th class=" p-2 leading-none text-start">
                                                <span class="font-normal">MARCA</span> <br>
                                                {{ $item->name_marca }}
                                            </th>
                                            <td class=" text-center p-1 leading-none" style="width: 90px;">
                                                MODELO <br>
                                                <b>
                                                    {{-- {{ decimalOrInteger($item->stock) }}
                                                    {{ $item->unit->name }} --}}
                                                    {{ $item->modelo }}
                                                </b>
                                            </td>
                                            <td colspan="{{ $empresa->usarLista() ? 2 : 0 }}"
                                                class=" p-2 leading-none text-center">
                                                {{ $item->name_category }} <br>
                                                {{ $item->name_subcategory }}
                                            </td>
                                            <td class=" p-2 leading-none text-center" style="width: 90px;">
                                                COMPRA <br>
                                                <b>
                                                    S/. {{ number_format($item->pricebuy, 2, '.', ', ') }}
                                                </b>
                                            </td>
                                            @if (!$empresa->usarLista())
                                                <td class=" p-2 leading-none text-center" style="width: 100px;">
                                                    VENTA<br>
                                                    <b>
                                                        S/. {{ number_format($item->pricesale, 2, '.', ', ') }}
                                                    </b>
                                                </td>
                                            @endif
                                        </tr>

                                        @if ($empresa->usarLista())
                                            <tr
                                                @if ($detallado) style="border-bottom: 1px solid #222;" @endif>
                                                @foreach ($pricetypes as $pricetype)
                                                    <td class="border-b p-2 text-start align-middle leading-none"
                                                        style="width: 90px;">
                                                        <b>{{ $pricetype->name }}</b>
                                                        <br>
                                                        S/.{{ number_format($item->getPrecioVenta($pricetype), $pricetype->decimals, '.', ', ') }}
                                                    </td>
                                                @endforeach

                                                @php
                                                    $rowempty = 0;
                                                @endphp
                                                @while ($rowempty < 5 - count($pricetypes))
                                                    <td></td>
                                                    @php
                                                        $rowempty++;
                                                    @endphp
                                                @endwhile
                                            </tr>
                                        @endif

                                        @if ($detallado && count($item->especificacions) > 0)
                                            @foreach ($item->especificacions as $esp)
                                                <tr class="row-striped ">
                                                    <th class="p-2 leading-none text-start"
                                                        style="border-right: 1px solid #222;">
                                                        {{ $esp->caracteristica->name }}
                                                    </th>
                                                    <td colspan="4" class="p-2 leading-none">
                                                        {{ $esp->name }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if (!empty($item->comentario))
                                                <tr class="row-striped ">
                                                    <th class="p-2 leading-none text-start">
                                                        OTROS
                                                    </th>
                                                    <td colspan="4" class="p-1 leading-none">
                                                        {{ nl2br($item->comentario) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif

                                        <tr style="border-top: 1px solid #222;">
                                            <th class="text-center p-2" colspan="5">
                                                ALMACENES</th>
                                        </tr>

                                        @if (count($item->almacens) > 5)
                                            @foreach ($item->almacens as $almacen)
                                                <tr>
                                                    <th class="p-2 text-start">
                                                        {{ $almacen->name }}</th>
                                                    <th class="p-2 text-start">
                                                        {{ $almacen->pivot->cantidad }}
                                                        {{ $item->unit->name }}
                                                    </th>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="" style="border-top: 1px solid #222;">
                                                @foreach ($item->almacens as $almacen)
                                                    <th class="p-2 text-center" style="border-right: 1px solid #222;">
                                                        {{ $almacen->name }}
                                                        <br>
                                                        {{ decimalOrInteger($almacen->pivot->cantidad) }}
                                                        {{ $item->unit->name }}
                                                    </th>
                                                @endforeach
                                                @php
                                                    $rowautoempty = 0;
                                                    // $rowautoempty = 5 - count($item->almacens);
                                                @endphp
                                                @while ($rowautoempty < 5 - count($item->almacens))
                                                    <td></td>
                                                    @php
                                                        $rowautoempty++;
                                                    @endphp
                                                @endwhile
                                            </tr>
                                        @endif

                                        @if (count($item->series) > 0)
                                            <tr style="border-top: 1px solid #222;">
                                                <th class="text-center p-2" colspan="5">
                                                    SERIES</th>
                                            </tr>
                                            @php
                                                $i = 0;
                                                $counter = 0;
                                                $cadena = '';
                                                $opentr = '<tr>';
                                                $closetr = '</tr>';

                                                foreach ($item->series as $serie) {
                                                    if ($i == 0) {
                                                        $cadena .= $opentr;
                                                    }
                                                    $cadena .=
                                                        '<td class="p-2 text-center border-table">' .
                                                        $serie->serie .
                                                        '-' .
                                                        $counter .
                                                        '</td>';
                                                    $i++;
                                                    $counter++;
                                                    if ($counter == count($item->series)) {
                                                        // while ($i < 5) {
                                                        //     $cadena .= '<td class="p-2 text-center border-table"></td>';
                                                        //     $i++;
                                                        // }
                                                    }

                                                    if ($i == 5) {
                                                        $cadena .= $closetr;
                                                        $i = 0;
                                                    }
                                                }

                                                echo $cadena;
                                            @endphp
                                        @endif

                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        {{-- 
                        <tr class=" row-striped font-normal text-10">

                            <td class="p-2 text-justify align-middle leading-none">

                                @if ($detallado && count($item->especificacions) > 0)
                                    <br>
                                    <b>ESPECIFICACIONES</b>

                                    @foreach ($item->especificacions as $esp)
                                        <br>
                                        <b>{{ $esp->caracteristica->name }} :</b>
                                        {{ $item->name }}
                                    @endforeach
                                    @if (!empty($item->comentario))
                                        <b>OTROS : </b>{{ nl2br($item->comentario) }}
                                    @endif
                                @endif
                            </td>

                            <td class="p-3 text-center align-middle" style="width: 60px;">
                                S/. {{ number_format($item->pricebuy, 2, '.', ', ') }}
                            </td>
                            @if ($empresa->usarLista())
                                @foreach ($pricetypes as $pricetype)
                                    <td class="p-2 text-center align-middle leading-none" style="width: 60px;">
                                        S/.
                                        {{ number_format($item->getPrecioVenta($pricetype), $pricetype->decimals, '.', ', ') }}
                                    </td>
                                @endforeach
                            @else
                                <td class="p-2 text-center align-middle leading-none" style="width: 60px;">
                                    S/.

                                    {{ number_format($item->pricesale, 2, '.', ', ') }}
                                </td>
                            @endif

                            <td class="p-2 text-center align-middle leading-none" style="width: 50px;">
                                {{ decimalOrInteger($item->stock) }}
                                {{ $item->unit->name }}
                            </td>
                        </tr> --}}
                    @endforeach
                </tbody>
                {{-- @else
                    <tbody>
                        @foreach ($ventas as $item)
                            <tr class="border-table">
                                <td>
                                    <table class="table text-10 font-normal">
                                        <thead class="" style="background: #ccc;">
                                            <th class="p-2 leading-none text-start" style="width: 100px;">
                                                {{ $item->seriecompleta }}
                                                <br>
                                                {{ $item->seriecomprobante->typecomprobante->name }}
                                            </th>
                                            <th class="p-2 font-normal leading-none text-start">
                                                {{ $item->client->document }} -
                                                {{ $item->client->name }}
                                                <br>
                                                {{ $item->direccion }}
                                            </th>
                                            <th class="p-2 font-normal leading-none" style="width: 90px;">
                                                {{ formatDate($item->date, 'DD MMMM Y') }}</th>
                                            <th class="p-2 font-normal" style="width: 50px;">
                                                {{ $item->typepayment->name }}</th>
                                            <th class="p-2 leading-none text-end font-medium" style="width: 150px;">
                                                {{ $item->sucursal->name }}
                                                <br>
                                                {{ $item->user->name }}
                                            </th>
                                        </thead>
                                        <tbody>
                                            <tr class="">
                                                <td colspan="5">
                                                    <table class="table">
                                                        <thead class="font-medium">
                                                            <th class="text-start font-medium">CANT.</th>
                                                            <th class="font-medium">DESCRIPCION</th>
                                                            <th class="font-medium">ALMACÉN</th>
                                                            <th class="font-medium text-end">PRECIO</th>
                                                            <th class="font-medium text-end">IMPORTE</th>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($item->tvitems as $tvitem)
                                                                <tr class="text-10 row-striped">
                                                                    <td class="text-10 p-1" style="width: 50px;">
                                                                        {{ decimalOrInteger($tvitem->cantidad) }}
                                                                        {{ $tvitem->producto->unit->name }}</td>
                                                                    <td class="text-10 leading-none p-1">
                                                                        {{ $tvitem->producto->name }}</td>
                                                                    <td class="text-10 text-center p-1"
                                                                        style="width: 60px">
                                                                        {{ $tvitem->almacen->name }}</td>
                                                                    <td class="text-10 text-end p-1"
                                                                        style="width: 60px">
                                                                        {{ number_format($tvitem->price, 2, '.', ', ') }}
                                                                    </td>
                                                                    <td class="text-10 text-end p-1"
                                                                        style="width: 60px">
                                                                        {{ number_format($tvitem->total, 2, '.', ', ') }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td colspan="5" class="p-2 leading-none text-12 font-bold text-end"
                                                    style="">
                                                    <small class="text-10">
                                                        IGV : {{ $item->moneda->simbolo }}</small>
                                                    {{ number_format($item->igv + $item->igvgratuito, 2, '.', ', ') }}
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td colspan="5" class="p-2 leading-none text-12 font-bold text-end"
                                                    style="">
                                                    <small class="text-10">
                                                        TOTAL : {{ $item->moneda->simbolo }}</small>
                                                    {{ number_format($item->total, 2, '.', ', ') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif --}}
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
