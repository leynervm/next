@extends('admin.reports.snappyPDF.layout')
@section('titulo', $titulo)

<style>
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
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #0fb9b9;
        text-align: center;
        padding: 5px;
        display: table;
        /* float: right; */
    }

    .text-price {
        color: #ffffff;
        display: table-cell;
        position: relative;
        vertical-align: middle;
        font-size: 14px;
        font-weight: 700;
        word-break: wrap;
        white-space: wrap;

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
        font-size: 8rem;
        letter-spacing: 10px;
        margin: 0;
        padding: 0;
        -webkit-text-stroke: 1px #fff;
        color: #0fb9b9;
        text-shadow: 3px 3px 0 #fff, -1px -1px 0 #fff, 1px -1px 0 #fff,
            -1px 1px 0 #fff, 1px 1px 0 #fff;
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
            <td class="align-middle p-5" style="background: #0fb9b9;height: 350px;border-bottom-right-radius: 3rem;"
                width="520px">
                <h1 class="catalogo">CATÁLOGO</h1>
                <h1 class="catalogo text-end">NEXT</h1>
            </td>
            <td class="align-top text-center p-0" align="center">
                <table class="table border-white text-center p-5" style="width:200px;margin: auto;background: #222222;"
                    width="100px" align="center">
                    <tbody>
                        <tr>
                            <td class="align-top text-center p-3" style="">
                                @if (!empty($url_logo))
                                    <img src="{{ imageBase64($url_logo) }}" alt="{{ $empresa->name }}"
                                        class="img-fluid" />
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 py-5">
                                @php
                                    $year = now('America/Lima')->year;
                                @endphp

                                <span class="year">
                                    {{ substr($year, 0, 2) }}</span>
                                <br>
                                <span class="year">
                                    {{ substr($year, -2) }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
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
    <table class="table border-white table-responsive">
        <tr class="">
            <td class="p-5">
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
