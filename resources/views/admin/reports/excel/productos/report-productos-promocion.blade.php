@if (count($productos) > 0)
    @foreach ($productos as $item)
        <table>
            <tr>
                <th colspan="4" style="border: 1px solid #0fb9b9;word-wrap: break-word; white-space: normal;">
                    <b>{{ $item->name }}</b>
                </th>
            </tr>

            @if (count($item->promocions) > 0)
                @foreach ($item->promocions as $promocion)
                    @php
                        $combo = getAmountCombo($promocion);
                    @endphp
                    @if ($combo)
                        @foreach ($combo->products as $itemcombo)
                            <tr>
                                @if ($loop->first)
                                    <td style="border: 1px solid #0fb9b9;" rowspan="{{ count($combo->products) }}"
                                        width="150px" align="center" valign="center">COMBO</td>

                                    <td rowspan="{{ count($combo->products) }}"
                                        style="border: 1px solid #0fb9b9;text-transform: uppercase;word-wrap: break-word; white-space: normal;"
                                        width="300px" valign="center">{{ mb_strtoupper($promocion->titulo, 'UTF-8') }}
                                    </td>
                                @endif

                                <td style="border: 1px solid #0fb9b9;word-wrap: break-word; white-space: normal;"
                                    width="300px" valign="center">
                                    {{ $itemcombo->name }}
                                </td>
                                <td style="border: 1px solid #0fb9b9;" width="120px" align="center" valign="center">
                                    {{ $itemcombo->type }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td width="150px" style="border: 1px solid #0fb9b9;" align="center" valign="center">
                                @if ($promocion->isDescuento())
                                    DESCUENTO
                                @else
                                    LIQUIDACIÓN
                                @endif
                            </td>
                            <td width="300px" style="border: 1px solid #0fb9b9;" align="center" valign="center">
                                @if ($promocion->isDescuento())
                                    {{ $item->descuento }} % DSCT
                                @endif
                            </td>
                            <td width="300px" style="border: 1px solid #0fb9b9;"></td>
                            <td width="120px" style="border: 1px solid #0fb9b9;"></td>
                        </tr>
                    @endif
                @endforeach
            @endif
        </table>
    @endforeach
@endif
