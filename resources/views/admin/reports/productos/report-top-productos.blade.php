@extends('admin.reports.snappyPDF.layout')
@section('titulo', $titulo)

@if (count($productos) > 0)
    @foreach ($productos as $item)
        <table class="table table-bordered border-dark text-10 font-normal mb-4">
            <tbody>
                <tr class="border border-dark">
                    <td class="text-center align-middle font-medium text-14" style="width: 100px; background: #f1f1f1;">
                        RANK
                        <br>
                        {{ $loop->index + 1 }}
                    </td>
                    <td colspan="6" class="border-dark border-start border-end">
                        {{ $item->name }}
                    </td>
                    <td class="text-center align-middle font-medium text-14" style="width: 100px; background: #f1f1f1;">
                        CANT.
                        <br>
                        {{ decimalOrInteger($item->vendidos) }}
                        {{ $item->unit->name }}
                    </td>
                </tr>

                @if ($detallado)
                    @php
                        $total_item = 0;
                        $total_item_ganancia = 0;
                    @endphp
                    {{-- <tr>
                        <td class="p-0 m-0" colspan="3">
                            <table class="table table-bordered border-dark table-responsive">
                                <thead style="background: #f1f1f1;">
                                    <th>CANT.</th>
                                    <th>COMPRA</th>
                                    <th>VENTA</th>
                                    <th>GANANCIA C/U</th>
                                    <th>TOTAL ITEM</th>
                                    <th>SALIDA</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach ($item->tvitems as $tvitem)
                                        @php
                                            $ganancia = $tvitem->price - $tvitem->pricebuy;
                                            $total_item = $total_item + $tvitem->total;
                                            $total_item_ganancia = $total_item_ganancia + $ganancia;
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                {{ decimalOrInteger($tvitem->cantidad) }} {{ $item->unit->name }}</td>
                                            <td class="text-center">
                                                {{ number_format($tvitem->pricebuy, 2, '.', ', ') }}</td>
                                            <td class="text-center">
                                                {{ number_format($tvitem->price, 2, '.', ', ') }}</td>
                                            <td
                                                class="text-center {{ $ganancia >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($ganancia, 2, '.', ', ') }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($tvitem->total, 2, '.', ', ') }}</td>
                                            <td class="text-center">
                                                @if ($tvitem->tvitemable && $tvitem->tvitemable->seriecompleta)
                                                    {{ $tvitem->tvitemable->seriecompleta }}
                                                @endif
                                            </td>
                                            <td class="text-center" style="width: 100px;">
                                                @if ($tvitem->isGratuito())
                                                    GRATUITO
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <th class="text-center align-middle text-14">
                                            {{ number_format($total_item_ganancia, 2, '.', ', ') }}</th>
                                        <th class="text-center align-middle text-14">
                                            {{ number_format($total_item, 2, '.', ', ') }}</th>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr> --}}

                    <tr class="border border-dark">
                        <th>CANT.</th>
                        <th>ALMACÉN</th>
                        <th>COMPRA</th>
                        <th>VENTA</th>
                        <th>GANANCIA C/U</th>
                        <th>TOTAL ITEM</th>
                        <th>SALIDA</th>
                        <th></th>
                    </tr>
                    @foreach ($item->tvitems as $tvitem)
                        @php
                            $ganancia = $tvitem->price - $tvitem->pricebuy;
                            $total_item = $total_item + $tvitem->total;
                            $total_item_ganancia = $total_item_ganancia + $ganancia * $tvitem->cantidad;
                        @endphp
                        <tr class="border border-dark">
                            <td class="text-center">
                                {{ decimalOrInteger($tvitem->cantidad) }} {{ $item->unit->name }}</td>
                            <td class="text-center">
                                @if ($tvitem->almacen)
                                    {{ $tvitem->almacen->name }}
                                @endif
                            </td>
                            <td class="text-center">
                                {{ number_format($tvitem->pricebuy, 2, '.', ', ') }}</td>
                            <td class="text-center">
                                {{ number_format($tvitem->price, 2, '.', ', ') }}</td>
                            <td class="text-center {{ $ganancia >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($ganancia, 2, '.', ', ') }}
                            </td>
                            <td class="text-center">
                                {{ number_format($tvitem->total, 2, '.', ', ') }}</td>
                            <td class="text-center">
                                @if ($tvitem->tvitemable && $tvitem->tvitemable->seriecompleta)
                                    {{ $tvitem->tvitemable->seriecompleta }}
                                @endif
                            </td>
                            <td class="text-center" style="width: 100px;">
                                @if ($tvitem->isGratuito())
                                    GRATUITO
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr class="border border-dark">
                        <th colspan="6" class="text-end align-middle">TOTAL GENERADO</th>
                        <th colspan="2" class="text-end align-middle text-14">
                            {{ number_format($total_item, 2, '.', ', ') }}</th>
                    </tr>
                    <tr class="border border-dark">
                        <th colspan="6" class="text-end align-middle">GANANCIA</th>
                        <th colspan="2"
                            class="text-end align-middle text-14 {{ $total_item_ganancia >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($total_item_ganancia, 2, '.', ', ') }}</th>
                    </tr>
                @endif
            </tbody>
        </table>
    @endforeach
@endif
