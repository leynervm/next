@extends('admin.reports.snappyPDF.layout')
@section('titulo', $titulo)


@if (count($productos) > 0)
    @foreach ($productos as $item)
        @php
            $image = $item->imagen;
            if (!empty($image)) {
                $image = $item->imagen->urlmobile;
            }
        @endphp

        <table class="table table-bordered border-dark text-10 font-normal">
            <tr class="border border-dark">
                <th colspan="4" style="width: 5cm;">
                    @if (!empty($image))
                        <div class="picture-producto">
                            <img src="{{ imageBase64($image, 'app/public/images/productos/') }}"
                                alt="{{ $item->imagen->urlmobile }}" class="img-fluid">
                        </div>
                    @else
                        <p class="text-10 text-10" style="color:#000;">IMÁGEN NO DISPONIBLE</p>
                    @endif
                </th>
                <th colspan="4" class="text-justify">
                    {{ $item->name }}
                </th>
            </tr>
            <tr class="border border-dark">
                <th>
                    MARCA
                </th>
                <td class="border-end border-dark">
                    {{ $item->marca->name }}
                </td>
                <th>
                    MODELO
                </th>
                <td class="border-end border-dark">
                    {{ $item->modelo }}
                </td>
                <th>
                    CATEGORÍA
                </th>
                <td class="border-end border-dark">
                    {{ $item->category->name }}
                </td>
                <th>
                    REGISTRADO
                </th>
                <td>
                    {{ formatDate($item->created_at) }}
                </td>
            </tr>
        </table>

        @if (count($item->compraitems))
            <table class="table table-bordered border-dark  text-10 font-normal mt-5">
                <tr class="border border-dark" style="background: #f1f1f1;">
                    <th colspan="6">ENTRADAS</th>
                </tr>
                <tr class="border border-dark">
                    <th>FECHA</th>
                    <th>CANTIDAD</th>
                    <th>ALMACÉN</th>
                    <th>PRECIO</th>
                    <th>COMPRA</th>
                    <th class="text-end">PROVEEDOR</th>
                </tr>

                @foreach ($item->compraitems as $compraitem)
                    @if (count($compraitem->kardexes) > 0)
                        @foreach ($compraitem->kardexes as $kardex)
                            <tr class="border border-dark">
                                <td class="text-center" style="width:120px;">
                                    {{ formatDate($compraitem->compra->date, 'DD/MM/Y') }}</td>
                                <td class="text-center" style="width:120px;">
                                    {{ decimalOrInteger($kardex->cantidad) }}
                                    {{ $item->unit->name }}
                                </td>
                                <td class="text-center" style="width:120px;">
                                    {{ $kardex->almacen->name }}</td>
                                <td class="text-center">
                                    {{ $compraitem->price }}</td>
                                <td class="text-center">
                                    {{ $compraitem->compra->referencia }}</td>
                                <td class="text-justify">
                                    {{ $compraitem->compra->proveedor->name }}</td>
                            </tr>
                        @endforeach
                    @endif

                    @if (count($compraitem->series))
                        @php
                            $series = $compraitem->series->chunk(6);
                        @endphp

                        <tr class="border border-dark" style="background: #f1f1f1;">
                            <th colspan="6">SERIES ENTRANTES</th>
                        </tr>

                        @foreach ($series as $chunk)
                            <tr class="border border-dark">
                                @foreach ($chunk as $serie)
                                    <td style="word-break: break-all; width:120px;"
                                        class="border-end border-dark text-center">
                                        {{ $serie->serie }}</td>
                                @endforeach

                                @for ($i = count($chunk); $i < 6; $i++)
                                    <td class=""></td>
                                @endfor
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </table>
        @endif

        @if (count($item->kardexes))
            <table class="table table-bordered border-dark text-10 font-normal mt-5">
                <tr class="border border-dark" style="background: #f1f1f1;">
                    <th colspan="6">RESUMEN DE MOVIMIENTOS</th>
                </tr>
                <tr class="border border-dark">
                    <th>FECHA</th>
                    <th>CANTIDAD</th>
                    <th>ALMACÉN</th>
                    <th>MOVIMIENTO</th>
                    <th>DETALLE</th>
                    <th class="text-end">REFERENCIA</th>
                </tr>
                @foreach ($item->kardexes as $kardex)
                    <tr class="border border-dark">
                        <td style="width: 120px;">
                            {{ formatDate($kardex->date, 'DD/MM/Y') }}</td>
                        <td class="text-center" style="width: 120px;">
                            {{ decimalOrInteger($kardex->cantidad) }}
                            {{ $item->unit->name }}
                        </td>
                        <td class="text-center" style="width: 120px;">
                            {{ $kardex->almacen->name }}</td>
                        <td class="text-center {{ $kardex->isEntrada() ? 'text-success' : 'text-danger' }}"
                            style="width: 120px;">
                            @if ($kardex->isEntrada())
                                INGRESO
                            @else
                                SALIDA
                            @endif
                        </td>
                        <td>{{ $kardex->detalle }}</td>
                        <td class="text-end">{{ $kardex->reference }}</td>
                    </tr>
                @endforeach
            </table>
        @endif


        @if (count($item->series))
            <table class="table table-bordered border-dark text-10 font-normal mt-5">
                <tr class="border border-dark" style="background: #f1f1f1;">
                    <th colspan="5">SERIES</th>
                </tr>
                <tr class="border border-dark">
                    <th>REGISTRADO</th>
                    <th>SERIE</th>
                    <th>ALMACÉN</th>
                    <th>ENTRADA</th>
                    <th>SALIDA</th>
                </tr>
                @foreach ($item->series as $serie)
                    <tr class="border border-dark">
                        <td style="width:120px;" class=" align-middle">
                            {{ formatDate($serie->created_at, 'DD/MM/Y') }}</td>
                        <td class="text-center align-middle" style="width:120px; word-wrap: break-word;">
                            {{ $serie->serie }}</td>
                        <td class="text-center align-middle" style="width:120px;">
                            {{ $serie->almacen->name }}</td>
                        <td class="text-center align-middle" style="width:120px;">
                            @if ($serie->compraitem)
                                @if ($serie->compraitem->compra)
                                    {{ $serie->compraitem->compra->referencia }}
                                @endif
                            @endif
                        </td>
                        <td class="text-center align-middle" style="width:120px;">
                            @if (count($serie->itemseries) > 0)
                                @foreach ($serie->itemseries as $itemserie)
                                    @if ($itemserie->seriable)
                                        @if (isset($itemserie->seriable->date))
                                            {{ formatDate($itemserie->seriable->date, 'DD/MM/Y') }}
                                            <br>
                                            <b>{{ $itemserie->seriable->tvitemable->seriecompleta }}</b>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    @endforeach
@endif
