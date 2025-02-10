<table style="border: 1px solid #0fb9b9;">
    <tbody>
        @foreach ($productos as $item)
            @php
                $rowspan = 3; //categor, subcategory, marca
                $almacens = $item->almacens->chunk(5) ?? [];
                $rowspan = $rowspan + count($almacens);

                if (count($almacens) > 0) {
                    $rowspan++;
                }
                if (!empty($item->modelo)) {
                    $rowspan++;
                }

                if ($empresa->usarLista()) {
                    $rowspan = $rowspan + 2;
                }

                if ($detallado) {
                    $series = $item->series->chunk(5) ?? [];
                    $especificacions = $item->especificacions ?? [];
                    $rowspan = $rowspan + count($series) + count($especificacions);

                    if (!empty($item->comentario)) {
                        $rowspan++;
                    }

                    if (count($series) > 0) {
                        $rowspan++;
                    }
                }
            @endphp

            <tr>
                <td rowspan="{{ $rowspan }}" valign="center" width="300px"
                    style="border: 1px solid #0fb9b9;background: #0fb9b9;color:#ffffff;font-weight: 700;text-align: justify">
                    {{ $item->name }}</td>
                <td colspan="{{ $empresa->usarLista() ? 4 : 3 }}" style="border: 1px solid #0fb9b9;" valign="top"
                    width="100px">
                    {{ $item->name_category }}</td>
                <td style="border: 1px solid #0fb9b9;" width="150px" valign="top">COMPRA</td>
                @if (!$empresa->usarLista())
                    <td style="border: 1px solid #0fb9b9;" valign="top" width="150px">VENTA</td>
                @endif
            </tr>
            <tr>
                <td colspan="{{ $empresa->usarLista() ? 4 : 3 }}" style="border: 1px solid #0fb9b9;" valign="top"
                    width="150px">
                    {{ $item->name_subcategory }}
                </td>
                <th style="border: 1px solid #0fb9b9;" valign="top" width="150px" data-format="#,##0.00">
                    {{ number_format($item->pricebuy, 2, '.', '') }}
                </th>
                @if (!$empresa->usarLista())
                    <th style="border: 1px solid #0fb9b9;" valign="top" width="150px" data-format="#,##0.00">
                        {{ number_format($item->pricesale, 2, '.', '') }}
                    </th>
                @endif
            </tr>

            @if ($empresa->usarLista())
                <tr>
                    @foreach ($pricetypes as $pricetype)
                        <td style="border: 1px solid #0fb9b9;font-weight: 700;" valign="center" align="center"
                            width="150px">
                            {{ $pricetype->name }}
                        </td>
                    @endforeach
                    @for ($i = count($pricetypes); $i < 5; $i++)
                        <td style="border: 1px solid #0fb9b9;"></td>
                    @endfor
                </tr>
                <tr>
                    @foreach ($pricetypes as $pricetype)
                        <td style="border: 1px solid #0fb9b9;" valign="center" align="center" width="150px"
                            data-format="#,##0.00">
                            {{ number_format($item->getPrecioVenta($pricetype), $pricetype->decimals, '.', '') }}
                        </td>
                    @endforeach
                    @for ($i = count($pricetypes); $i < 5; $i++)
                        <td style="border: 1px solid #0fb9b9;"></td>
                    @endfor
                </tr>
            @endif
            <tr>
                <th style=" font-weight: 700;  border:1px solid #0fb9b9;">
                    MARCA</th>
                <td colspan="4" style="border: 1px solid #0fb9b9;">
                    {{ $item->name_marca }}</td>
            </tr>

            @if (!empty($item->modelo))
                <tr>
                    <th style="font-weight: 700; border: 1px solid #0fb9b9;">
                        MODELO</th>
                    <td colspan="4" style="border: 1px solid #0fb9b9;">
                        {{ $item->modelo }}</td>
                </tr>
            @endif

            @if ($detallado && count($item->especificacions) > 0)
                @foreach ($item->especificacions as $esp)
                    <tr>
                        <th style=" font-weight: 700;  border: 1px solid #0fb9b9;" width="150px">
                            {{ $esp->caracteristica->name }}</th>
                        <td colspan="4" style=" border: 1px solid #0fb9b9;" width="200px">
                            {{ $esp->name }}</td>
                    </tr>
                @endforeach
                @if (!empty($item->comentario))
                    <tr>
                        <th style="border: 1px solid #0fb9b9;">
                            OTROS</th>
                        <td colspan="4" style="border: 1px solid #0fb9b9;">
                            {{ nl2br($item->comentario) }}</td>
                    </tr>
                @endif
            @endif

            @if (count($item->almacens) > 0)
                <tr>
                    <th colspan="5" style="border: 1px solid #0fb9b9;">
                        ALMACÉNES</th>
                </tr>

                @foreach ($almacens as $chunk)
                    <tr>
                        @foreach ($chunk as $almacen)
                            <th style="word-break: break-all;border: 1px solid #0fb9b9;font-weight: 700;"
                                valign="center" align="center" width="150px">
                                {{ $almacen->name }}
                                <br>
                                {{ decimalOrInteger($almacen->pivot->cantidad) }}
                                {{ $item->unit->name }}
                            </th>
                        @endforeach

                        @for ($i = count($chunk); $i < 5; $i++)
                            <td style="border: 1px solid #0fb9b9;"></td>
                        @endfor
                    </tr>
                @endforeach
            @endif

            @if ($detallado && count($item->series) > 0)
                <tr>
                    <th colspan="5" style="border: 1px solid #0fb9b9;">
                        SERIES</th>
                </tr>

                @foreach ($series as $chunk)
                    <tr>
                        @foreach ($chunk as $serie)
                            <td style=" word-break: break-all;border: 1px solid #0fb9b9;" valign="center" align="center"
                                width="150px">
                                {{ $serie->serie }}</td>
                        @endforeach

                        @for ($i = count($chunk); $i < 5; $i++)
                            <td style="border: 1px solid #0fb9b9;"></td>
                        @endfor
                    </tr>
                @endforeach
            @endif
            <tr>
                <td></td>
            </tr>

            {{-- </tbody>
                    </table>
                </td>
            </tr> --}}
        @endforeach
    </tbody>
</table>
