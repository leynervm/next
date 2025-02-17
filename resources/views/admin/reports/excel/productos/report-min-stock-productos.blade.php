<table>
    <tbody>
        @foreach ($productos as $item)
            @php
                $countalmacens = 0;
                if (count($item->almacens) > 0) {
                    $countalmacens = count($item->almacens->chunk(4));
                }
            @endphp
            <tr>
                <td rowspan="{{ 4 + $countalmacens }}"
                    style="border:1px solid #0fb9b9;word-wrap: break-word; white-space: normal;" width="300px"
                    valign="center">
                    <b>{{ $item->name }}</b>
                </td>

                <td style="border:1px solid #0fb9b9;" width="150px">
                    <b>CATEGORÍA</b>
                </td>
                <td style="border:1px solid #0fb9b9;" width="150px">
                    <b>SUBCATEGORÍA</b>
                </td>
                <td style="border:1px solid #0fb9b9;" width="150px" align="center">
                    <b> COMPRA S/.</b>
                </td>
                <td style="border:1px solid #0fb9b9;" width="150px" align="center" valign="center">
                    <b>STOCK MÍNIMO</b>
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #0fb9b9;" valign="center">
                    {{ $item->name_category }}
                </td>
                <td style="border:1px solid #0fb9b9;" valign="center">
                    {{ $item->name_subcategory }}
                </td>
                <td style="border:1px solid #0fb9b9;" valign="center" data-format="#,##0.00">
                    {{ number_format($item->pricebuy, 2, '.', ', ') }}
                </td>
                <td style="border:1px solid #0fb9b9;" valign="center" data-format="#,##0.00">
                    {{ decimalOrInteger($item->minstock) }}
                </td>
            </tr>
            <tr>
                <th style="border:1px solid #0fb9b9;" width="120px" valign="center">
                    <b>MARCA</b>
                </th>
                <td style="border:1px solid #0fb9b9;" valign="center">
                    {{ $item->name_marca }}
                </td>
                <th style="border:1px solid #0fb9b9;" width="120px" valign="center">
                    <b>MODELO</b>
                </th>
                <td style="border:1px solid #0fb9b9;" valign="center">
                    @if (!empty($item->modelo))
                        {{ $item->modelo }}
                    @endif
                </td>
            </tr>

            @if (count($item->almacens) > 0)
                <tr>
                    <th style="border:1px solid #0fb9b9;" colspan="4" align="center">
                        <b>ALMACÉNES</b>
                    </th>
                </tr>

                @php
                    $almacens = $item->almacens->chunk(4);
                @endphp

                @foreach ($almacens as $chunk)
                    <tr>
                        @foreach ($chunk as $almacen)
                            <th style="border:1px solid #0fb9b9;word-break: break-all;" width="120px" align="center"
                                valign="center">
                                {{ $almacen->name }}
                                <br>
                                {{ decimalOrInteger($almacen->pivot->cantidad) }}
                                {{ $item->unit->name }}
                            </th>
                        @endforeach
                        @for ($i = count($chunk); $i < 4; $i++)
                            <td style="border:1px solid #0fb9b9;"></td>
                        @endfor
                    </tr>
                @endforeach
            @endif
            <tr>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
