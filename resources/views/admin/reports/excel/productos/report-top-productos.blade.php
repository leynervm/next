@if (count($productos) > 0)
    @foreach ($productos as $item)
        <table>
            <tbody>
                <tr>
                    <td style="background: #0fb9b9;color:#ffffff;font-weight: 700;" align="center" valign="center"
                        width="80px">
                        RANK
                        <br>
                        {{ $loop->index + 1 }}
                    </td>
                    <td colspan="6" style="border: 1px solid #0fb9b9;text-align: justify" valign="top">
                        {{ $item->name }}
                    </td>
                    <td style="background: #0fb9b9;color:#ffffff;font-weight: 700;" align="center" valign="center"
                        width="80px">
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

                    <tr>
                        <th style="border: 1px solid #0fb9b9;" align="center" valign="center">CANT.</th>
                        <th style="border: 1px solid #0fb9b9;" align="center" valign="center">ALMACÉN</th>
                        <th style="border: 1px solid #0fb9b9;" align="center" valign="center">COMPRA</th>
                        <th style="border: 1px solid #0fb9b9;" align="center" valign="center">VENTA</th>
                        <th style="border: 1px solid #0fb9b9;" align="center" valign="center">GANANCIA C/U</th>
                        <th style="border: 1px solid #0fb9b9;" align="center" valign="center">TOTAL ITEM</th>
                        <th style="border: 1px solid #0fb9b9;" align="center" valign="center">SALIDA</th>
                        <th style="border: 1px solid #0fb9b9;" align="center" valign="center"></th>
                    </tr>
                    @foreach ($item->tvitems as $tvitem)
                        @php
                            $ganancia = $tvitem->price - $tvitem->pricebuy;
                            $total_item = $total_item + $tvitem->total;
                            $total_item_ganancia = $total_item_ganancia + $ganancia * $tvitem->cantidad;
                        @endphp
                        <tr>
                            <td style="border: 1px solid #0fb9b9;" width="100px" align="center" valign="center">
                                {{ decimalOrInteger($tvitem->cantidad) }} {{ $item->unit->name }}</td>
                            <td style="border: 1px solid #0fb9b9;" align="center" valign="center" width="100px">
                                @if ($tvitem->almacen)
                                    {{ $tvitem->almacen->name }}
                                @endif
                            </td>
                            <td style="border: 1px solid #0fb9b9;" width="100px" align="center" valign="center">
                                {{ number_format($tvitem->pricebuy, 2, '.', ', ') }}</td>
                            <td style="border: 1px solid #0fb9b9;" width="100px" align="center" valign="center">
                                {{ number_format($tvitem->price, 2, '.', ', ') }}</td>
                            <td style="border: 1px solid #0fb9b9;color {{ $ganancia >= 0 ? 'green' : 'red' }}" width="100px" align="center" valign="center" data-format="#,##0.00">
                                {{ number_format($ganancia, 2, '.', ', ') }}
                            </td>
                            <td style="border: 1px solid #0fb9b9;" width="100px" align="center" valign="center">
                                {{ number_format($tvitem->total, 2, '.', ', ') }}</td>
                            <td style="border: 1px solid #0fb9b9;" width="100px">
                                @if ($tvitem->tvitemable && $tvitem->tvitemable->seriecompleta)
                                    {{ $tvitem->tvitemable->seriecompleta }}
                                @endif
                            </td>
                            <td style="border: 1px solid #0fb9b9;" width="100px">
                                @if ($tvitem->isGratuito())
                                    GRATUITO
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="6" style="border: 1px solid #0fb9b9; text-align: end;" valign="center">
                            TOTAL GENERADO</th>
                        <th colspan="2" style="border: 1px solid #0fb9b9;text-align:end;" data-format="#,##0.00">
                            {{ number_format($total_item, 2, '.', '') }}</th>
                    </tr>
                    <tr>
                        <th colspan="6" style="border: 1px solid #0fb9b9; text-align:end;" valign="center">
                            GANANCIA</th>
                        <th colspan="2"
                            style="border: 1px solid #0fb9b9;text-align:end;{{ $total_item_ganancia >= 0 ? 'green' : 'red' }}"
                            data-format="#,##0.00">
                            {{ number_format($total_item_ganancia, 2, '.', '') }}</th>
                    </tr>
                @endif
            </tbody>
        </table>
    @endforeach
@endif
