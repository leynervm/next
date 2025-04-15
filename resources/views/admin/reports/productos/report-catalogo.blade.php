@extends('admin.reports.snappyPDF.layout')
@section('titulo', $titulo)

<style>
    * {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .img-product {
        position: relative;
    }

    .img-product:before,
    .img-product:after {
        position: absolute;
        content: '';
        width: 15px;
        height: 15px;
    }

    .img-product:before {
        bottom: 0;
        right: 0;
        border-right: 2px solid #0fb9b9 !important;
        border-bottom: 2px solid #0fb9b9 !important;
    }

    .img-product:after {
        top: 0;
        right: 0;
        border-right: 2px solid #0fb9b9 !important;
        border-top: 2px solid #0fb9b9 !important;
    }


    .content-price {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: #0fb9b9;
        text-align: center;
        padding: 5px;
        display: table;
        /* float: right; */
    }

    .text-price {
        padding-top: 5px;
        color: #ffffff;
        display: table-cell;
        vertical-align: middle;
        font-size: 16px;
        font-weight: 700;
        word-break: wrap;
        white-space: wrap;
        line-height: 2rem;
    }

    .especificacions {
        width: 100%;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 5px;
    }

    .especificacion {
        position: relative;
    }

    .especificacion:before {
        position: absolute;
        content: '';
        width: 30%;
        height: 1px;
    }

    .especificacion:before {
        bottom: 0;
        left: 35%;
        /* border-bottom: 1px solid #0fb9b9 !important; */
        background: #0fb9b9;
    }

    .container-snappy-pdf {
        padding: 0 !important;
    }

    .marca {
        position: relative;
    }

    .marca:before {
        position: absolute;
        content: '';
        bottom: -3px;
        left: 0;
        height: 4px;
        width: 50%;
        display: block;
        background: #0fb9b9;
    }

    .catalogo {
        font-size: 7rem;
        letter-spacing: 10px;
        margin: 0;
        padding: 0;
        /* -webkit-text-stroke: 1px #fff; */
        color: #fff;
        /* text-shadow: 3px 3px 0 #fff, -1px -1px 0 #fff, 1px -1px 0 #fff,
            -1px 1px 0 #fff, 1px 1px 0 #fff; */
    }

    .titulo {
        font-size: 3rem;
        letter-spacing: 5px;
        margin: 0;
        padding: 0;
        -webkit-text-stroke: 1px #0fb9b9;
        color: #fff;
        text-shadow: 3px 3px 0 #0fb9b9, -1px -1px 0 #0fb9b9, 1px -1px 0 #0fb9b9,
            -1px 1px 0 #0fb9b9, 1px 1px 0 #0fb9b9;
        width: auto !important;
        /* max-width: 200px; */
        max-width: 100%;
        background: #fff;
        /* border-radius: .5rem 0 .5rem 0.85rem; */
        /* box-shadow: -3px 3px 0px #0fb9b9; */
        padding: 1rem;
        position: relative;
        display: block;
        border-radius: .025rem;
        border: 1px solid #0fb9b9;
        display: inline-block;
        /* box-shadow: 0px 0px 0px 2px #0fb9b9; */
    }

    .titulo:before {
        position: absolute;
        content: '';
        left: 4px;
        bottom: -6px;
        width: 101%;
        height: 101%;
        /* background: #fff; */
        /* z-index: -1; */
        border-radius: .025rem;
        /* box-shadow: 0px 0px 0px 2px #0fb9b9; */
        border: 1px solid #0fb9b9;
        /* box-shadow: -2px 2px 0px #0fb9b9; */
    }

    .year {
        font-size: 5rem;
        color: #ffffff;
        font-weight: 400;
        display: block;
        line-height: 40px;
    }

    .card-producto {
        border-radius: 1.5rem;
        /* box-shadow: -1px 5px 3px 0px #0fb9b9; */
        box-shadow: 0px 0px 8px #ccc;
        width: 90% !important;
        /* background-color: #fff;
        background-image: url("data:image/svg+xml,%3Csvg width='10' height='10' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='#0fb9b9' fill-opacity='1' fill-rule='evenodd'%3E%3Ccircle cx='3' cy='3' r='3'/%3E%3Ccircle cx='13' cy='13' r='3'/%3E%3C/g%3E%3C/svg%3E");
        background-attachment: fixed;
        font-family: 'Open Sans', sans-serif; */

    }
</style>

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

<table class="table border-white table-responsive">
    <tbody>
        <tr>
            <td width="100"></td>
            <td class="align-middle p-3" style="background: #0fb9b9;height: 350px;border-radius: 3rem;">
                <h1 class="catalogo text-center">CATÁLOGO</h1>
                <h1 class="catalogo text-center">
                    {{ formatDate(now('America/Lima'), 'MMMM') }}
                    {{ now('America/Lima')->year }}
                </h1>
            </td>
            <td width="100"></td>
        </tr>

    </tbody>
</table>

@if (!empty($titulo))
    <table class="table border-white table-responsive mt-5 p-5">
        <tbody>
            <tr>
                <td class="p-0 text-center">
                    <h1 class="catalogo titulo">
                        {{ $titulo }}</h1>
                </td>
            </tr>
        </tbody>
    </table>
@endif


@foreach ($productos as $item)
    @php
        $image = $item->imagen;
        $filename;
        if (!empty($image)) {
            $image = $item->imagen->url;
        }
        $especificacions = $item->especificacions->chunk(3);
        $rowspan = 3 + count($especificacions);
        if (!empty($item->comentario)) {
            $rowspan = $rowspan + 1;
        }
    @endphp
    <table class="table border-white">
        {{-- <tbody>
            <tr>
                <td>
                    <table class="table"
                        style="border-radius: 1.5rem;width: 90%;margin:auto;box-shadow: 0px 0px 5px #ccc;">
                        <tr>
                            <td class="border border-dark" colspan="3"
                                style="text-align-last: center;font-size: 16px;color:#444444;">
                                {{ $item->name }}
                            </td>
                            <td class="border border-primary" width="450px"
                                rowspan="{{ count($especificacions) + 1 }}">
                                @if (!empty($image) && Storage::exists('images/productos/' . $image))
                                    <img src="{{ imageBase64($image, 'app/public/images/productos/') }}" alt=""
                                        class=""
                                        style="display: block;width:auto !important;max-width:100% !important;height: auto;max-height: 450px;object-fit: scale-down !important;border:2px solid rgb(14, 117, 14)">
                                @endif
                            </td>
                        </tr>

                        @if (count($especificacions) > 0)
                            @foreach ($especificacions as $chunk)
                                @php
                                    $clase = $loop->last ? '' : 'especificacion';
                                    $clase = !empty($item->comentario) ? 'especificacion' : $clase;
                                @endphp
                                <tr class="">
                                    @foreach ($chunk as $esp)
                                        <td class="text-center align-top {{ $clase }}" width="70px">
                                            <h1 class="text-13 font-bold" style="color:#494949;">
                                                {{ $esp->caracteristica->name }}</h1>

                                            <p class="text-10 m-0" style="color:#5f5f5f;padding-bottom: 2px;">
                                                {{ $esp->name }}</p>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </td>
            </tr>
        </tbody> --}}
        <tr class="">
            <td class="p-3 px-5">
                <table class="table border-white text-10 font-normal card-producto box-image"
                    style="margin-left:{{ $loop->index % 2 ? 'auto !important;' : '0' }}">
                    <tbody>
                        <tr class="">
                            @if ($loop->index % 2)
                                @include('admin.reports.productos.tr-image-catalogo')
                                @include('admin.reports.productos.tr-especificacions-catalogo')
                            @else
                                @include('admin.reports.productos.tr-especificacions-catalogo')
                                @include('admin.reports.productos.tr-image-catalogo')
                            @endif
                        </tr>
                    </tbody>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
@endforeach
