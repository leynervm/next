@extends('admin.reports.snappyPDF.layout')
@section('titulo', $titulo)

@php
    $tvitems = $cotizacion->tvitems->where('gratuito', \App\Models\Tvitem::NO_GRATUITO);
    $ofertas = $cotizacion->tvitems->where('gratuito', \App\Models\Tvitem::GRATUITO);
    $garantias = $cotizacion->cotizaciongarantias->chunk(5);
@endphp

<table class="table border-white table-responsive text-12 font-normal">
    <thead>
        @if (!empty($cotizacion->detalle))
            <tr>
                <th></th>
                <th class="p-1 text-end text-14 pb-5" colspan="2" align="right">
                    {!! nl2br($cotizacion->detalle) !!}
                    <div style="height: 3px; max-width:200px;background: #0fb9b9; margin-left: auto;"></div>
                </th>
            </tr>
        @endif
        <tr>
            <th class="p-1 text-start">
                FECHA</th>
            <td class="p-1 text-start">
                : {{ FormatDate($cotizacion->date, 'DD/MM/Y') }}</td>
        </tr>
        <tr>
            <th class="p-1 align-top text-start">
                CLIENTE</th>
            <td class="p-1 text-start align-top">
                : {{ $cotizacion->client->name }} -
                <b>[{{ $cotizacion->client->document }}]</b>

                @if (!empty($cotizacion->direccion))
                    <br>
                    {{ $cotizacion->direccion }}
                @endif
            </td>
        </tr>
        @if ($cotizacion->lugar)
            <tr>
                <th class="p-1 align-top text-start">
                    LUGAR DE ENTREGA</th>
                <td class="p-1 text-start align-top">
                    : {{ $cotizacion->lugar->name }} <br>
                    {{ $cotizacion->lugar->ubigeo->region }}
                    - {{ $cotizacion->lugar->ubigeo->provincia }}
                    - {{ $cotizacion->lugar->ubigeo->distrito }}
                </td>
            </tr>
        @endif

        <tr>
            <th class="p-1 align-top text-start" style="width: 170px">
                TIEMPO DE ENTREGA</th>
            <td class="p-1 text-start align-top">
                : {{ $cotizacion->entrega }} {{ getNameTime($cotizacion->datecode) }}
            </td>
        </tr>
    </thead>
</table>

@foreach ($tvitems as $item)
    <table class="table table-responsive text-10 font-normal p-0 m-0 mt-5">
        <tbody>
            @php
                $rowspan = 2;
                $rowspan = $rowspan + count($item->producto->especificacions);
                if ($item->producto->modelo) {
                    $rowspan = $rowspan + 1;
                }
                $image = $item->producto->imagen;
                if (!empty($image)) {
                    $image = $item->producto->imagen->url;
                }
            @endphp
            <tr class="">
                <td colspan="2" class="p-2 border text-center align-middle leading-none text-12"
                    style="border-color: #0fb9b9 !important;">
                    @if (!empty($image))
                        <img src="{{ imageBase64($image, 'app/public/images/productos/') }}"
                            alt="{{ $item->producto->imagen->url }}" class="img-fluid img-sm">
                        {{-- @else
                        <p class="text-10 text-10" style="color:#000;">IMÁGEN NO DISPONIBLE</p> --}}
                        <br>
                    @endif
                    <p class="font-normal text-justify p-0 m-0">
                        {{ $item->producto->name }}</p>
                    <br>
                </td>
                <td colspan="4" class="p-0 m-0 border" valign="top"
                    style="width: 560px;border-color: #0fb9b9 !important;">
                    <table class="table p-0 m-0 border-0 font-normal text-10">
                        <tbody>
                            <tr style="border-color: #0fb9b9 !important;">
                                <th colspan="2" class="p-2 leading-none text-center border-end border-bottom"
                                    style="background: #0fb9b9;color:#ffffff;border-color: #0fb9b9 !important;">
                                    ESPECIFICACIONES
                                </th>
                            </tr>
                            <tr style="border: 0px solid #fff;">
                                <th style="width:120px;border-color: #0fb9b9 !important;"
                                    class="p-2 py-3 leading-none text-start border-end border-bottom">
                                    MARCA
                                </th>
                                <td class="p-2 py-3 border-bottom leading-none"
                                    style="border-color: #0fb9b9 !important;">
                                    {{ $item->producto->marca->name }}
                                </td>
                            </tr>

                            @if (!empty($item->producto->modelo))
                                @php
                                    $rowspan = $rowspan + 1;
                                @endphp
                                <tr style="border: 0px solid #fff;">
                                    <th style="width:120px;border-color: #0fb9b9 !important;"
                                        class="p-2 py-3 leading-none text-start border-end border-bottom ">
                                        MODELO
                                    </th>
                                    <td class="p-2 py-3 border-bottom leading-none"
                                        style="border-color: #0fb9b9 !important;">
                                        {{ $item->producto->modelo }}
                                    </td>
                                </tr>
                            @endif

                            @if (count($item->producto->especificacions) > 0)
                                @php
                                    $rowspan = $rowspan + count($item->producto->especificacions);
                                @endphp
                                @foreach ($item->producto->especificacions as $esp)
                                    <tr style="border: 0px solid #fff;">
                                        <th style="width:120px;border-color: #0fb9b9 !important;"
                                            class="p-2 py-3 leading-none text-start border-bottom border-end">
                                            {{ $esp->caracteristica->name }}
                                        </th>
                                        <td class="p-2 py-3 leading-none border-bottom align-middle"
                                            style="border-color: #0fb9b9 !important;">
                                            {{ $esp->name }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            @if (!empty($item->producto->comentario))
                                <tr style="border: 0px solid #fff;">
                                    <th style="width:120px;border-color: #0fb9b9 !important;"
                                        class="p-2 py-3 leading-none align-middle text-start border-bottom border-end">
                                        OTROS
                                    </th>
                                    <td class="p-2 py-3 leading-none border-bottom"
                                        style="border-color: #0fb9b9 !important;">
                                        {!! nl2br($item->producto->comentario) !!}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr>
                <th style="border-color: #0fb9b9 !important;background: #0fb9b9;color:#ffffff;font-size: 14px;"
                    class="p-2 border text-start align-middle" width="200px">
                    PRECIO UNITARIO
                </th>
                <th class="p-2 border text-center align-middle" width="150px"
                    style="border-color: #0fb9b9 !important;font-size: 14px;">
                    {{ $cotizacion->moneda->simbolo }}
                    {{ number_format($item->price, 2, '.', ', ') }}
                </th>

                <th style="border-color: #0fb9b9 !important;background: #0fb9b9;color:#ffffff;font-size: 14px;"
                    class="p-2 border text-start align-middle" width="120px">
                    CANTIDAD
                </th>
                <th class="p-2 border text-center align-middle" width="150px"
                    style="border-color: #0fb9b9 !important;font-size: 14px;">
                    {{ decimalOrInteger($item->cantidad) }} {{ $item->producto->unit->name }}
                </th>

                <th style="border-color: #0fb9b9 !important;background: #0fb9b9;color:#ffffff;font-size: 14px;"
                    class="p-2 border text-start align-middle" width="120px">
                    TOTAL
                </th>
                <th class="p-2 border text-center align-middle"
                    style="border-color: #0fb9b9 !important;font-size: 14px;">
                    {{ $cotizacion->moneda->simbolo }}
                    {{ number_format($item->total, 2, '.', ', ') }}
                </th>
            </tr>
        </tbody>
    </table>
@endforeach


@if (count($cotizacion->otheritems))
    @foreach ($cotizacion->otheritems as $item)
        <table class="table table-responsive text-10 font-normal mt-5">
            <tbody>
                @php
                    $rowspan = 2;
                    $image = $item->imagen;
                    if (!empty($image)) {
                        $image = $item->imagen->url;
                    }
                @endphp
                <tr class="">
                    <td colspan="{{ !empty($item->marca) ? 2 : 6 }}"
                        class="p-2 border text-center align-middle leading-none text-12"
                        style="border-color: #0fb9b9 !important;">
                        @if (!empty($image))
                            <img src="{{ imageBase64($image, 'app/public/images/productos/') }}"
                                alt="{{ $item->imagen->url }}" class="img-fluid img-sm">
                            <br>
                        @endif
                        <p class="font-normal text-justify m-0 p-0">
                            {{ $item->name }}</p>
                        <br>
                    </td>

                    @if (!empty($item->marca))
                        <td class="p-0 border" colspan="4" style="border-color: #0fb9b9 !important;">
                            <table class="table p-0 m-0 border-0 font-normal text-10">
                                <tbody>
                                    <tr class="border-start-0" style="border-color: #0fb9b9 !important;">
                                        <th colspan="2" class="p-2 leading-none text-center border-end border-bottom"
                                            style="background: #0fb9b9;color:#ffffff;border-color: #0fb9b9 !important;">
                                            ESPECIFICACIONES
                                        </th>
                                    </tr>
                                    <tr class="border-start-0">
                                        <th style="width:120px;border-color: #0fb9b9 !important;"
                                            class="p-2 py-3 leading-none text-start border-end border-bottom">
                                            MARCA
                                        </th>
                                        <td class="p-2 py-3 border-bottom leading-none"
                                            style="border-color: #0fb9b9 !important;">
                                            {{ $item->marca }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    @endif
                </tr>

                <tr>
                    <th style="border-color: #0fb9b9 !important;background: #0fb9b9;color:#ffffff;font-size: 14px;"
                        class="p-2 border text-start align-middle" width="200px">
                        PRECIO UNITARIO
                    </th>
                    <th class="p-2 border text-center align-middle" width="150px"
                        style="border-color: #0fb9b9 !important;font-size: 14px;">
                        {{ $cotizacion->moneda->simbolo }}
                        {{ number_format($item->price, 2, '.', ', ') }}
                    </th>
                    <th style="border-color: #0fb9b9 !important;background: #0fb9b9;color:#ffffff;font-size: 14px;"
                        class="p-2 border text-start align-middle" width="120px">
                        CANTIDAD
                    </th>
                    <th class="p-2 border text-center align-middle" width="150px"
                        style="border-color: #0fb9b9 !important;font-size: 14px;">
                        {{ decimalOrInteger($item->cantidad) }} {{ $item->unit->name }}
                    </th>
                    <th style="border-color: #0fb9b9 !important;background: #0fb9b9;color:#ffffff;font-size: 14px;"
                        class="p-2 border text-start align-middle" width="120px">
                        TOTAL
                    </th>
                    <th class="p-2 border text-center align-middle"
                        style="border-color: #0fb9b9 !important;font-size: 14px;">
                        {{ $cotizacion->moneda->simbolo }}
                        {{ number_format($item->total, 2, '.', ', ') }}
                    </th>
                </tr>
            </tbody>
        </table>
    @endforeach
@endif



<table class="table table-responsive text-16 font-normal mt-5">
    <thead>
        <tr>
            <th class="text-14 border p-2 text-start" style="border-color: #0fb9b9 !important;" width="350px">
                TOTAL DE LA COTIZACIÓN
            </th>
            <th class="text-14 border p-2 text-end" style="border-color: #0fb9b9 !important;">
                {{ $cotizacion->moneda->simbolo }}
                {{ number_format($cotizacion->total, 2, '.', ', ') }}
            </th>
        </tr>
    </thead>
</table>

@if (count($ofertas))
    <table class="table table-responsive text-10 font-normal mt-5">
        <thead>
            <tr>
                <th class="text-14 border p-2 text-center" colspan="2" style="border-color: #0fb9b9 !important;">
                    OFERTAS
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ofertas as $item)
                <tr class="">
                    <td class="p-2 border font-normal text-justify align-middle leading-none text-10"
                        style="border-color: #0fb9b9 !important;" width="350px">
                        {{ $item->producto->name }} | {{ $item->producto->marca->name }}
                        @if (!empty($item->producto->modelo))
                            |
                            {{ $item->producto->modelo }}
                        @endif
                    </td>
                    <td class="p-2 border" style="border-color: #0fb9b9 !important;">
                        <span class="text-9 font-normal p-2 px-3"
                            style="background: #0fb9b9; white-space: nowrap; color:white;display: inline-block; border-radius: 0.35rem;">
                            GRATIS</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@if (count($garantias))
    <table class="table table-responsive text-10 font-normal mt-5">
        <thead>
            <tr>
                <th class="text-14 border p-2 text-center" colspan="5" style="border-color: #0fb9b9 !important;">
                    GARANTÍAS
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($garantias as $chunk)
                <tr class="">
                    @foreach ($chunk as $item)
                        <th class="text-13 p-2 border text-center align-middle"
                            style="border-color: #0fb9b9 !important;" width="120px;">
                            {{ $item->typegarantia->name }}
                            <br>
                            {{ $item->time }}
                            {{ getNameTime($item->datecode) }}
                        </th>
                    @endforeach
                    @for ($i = count($chunk); $i < 5; $i++)
                        <td class="p-2 border text-center align-middle" style="border-color: #0fb9b9 !important;"
                            width="120px;"></td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<table class="table table-responsive text-16 font-normal mt-5" style="border-color:#0fb9b9 !important;">
    <thead>
        <tr>
            <td class="text-10 p-2 text-center" style="">
                COTIZACIÓN VÁLIDA HASTA EL
                {{ formatDate($cotizacion->validez, "dddd DD \d\e MMMM \d\e Y") }}
            </td>
        </tr>
        <tr>
            <td class="text-12 p-2 text-center" style="">
                MODALIDAD DE PAGO
                {{ $cotizacion->typepayment->name }}
            </td>
        </tr>
    </thead>
</table>
