@if (count($productos) > 0)
    @foreach ($productos as $item)
        <table style="border: 1px solid #0fb9b9;">
            <tr>
                <th width="800px"
                    style="border:1px solid #0fb9b9;text-align: justify;word-wrap: break-word; white-space: normal;"
                    colspan="6">
                    {{ $item->name }}</th>
            </tr>
            <tr>
                <th style="border: 1px solid #0fb9b9; color:white; background:#0fb9b9;font-weight: 700;">
                    MARCA</th>
                <th style="border: 1px solid #0fb9b9; color:white; background:#0fb9b9;font-weight: 700;" colspan="2">
                    MODELO</th>
                <th style="border: 1px solid #0fb9b9; color:white; background:#0fb9b9;font-weight: 700;">
                    CATEGORÍA</th>
                <th style="border: 1px solid #0fb9b9; color:white; background:#0fb9b9;font-weight: 700;" colspan="2">
                    REGISTRADO</th>
            </tr>
            <tr>
                <td style="border: 1px solid #0fb9b9;">
                    {{ $item->marca->name }}</td>
                <td style="border: 1px solid #0fb9b9;" colspan="2">
                    {{ $item->modelo }}</td>
                <td style="border: 1px solid #0fb9b9;">
                    {{ $item->category->name }}</td>
                <td style="border: 1px solid #0fb9b9;" colspan="2">
                    {{ formatDate($item->created_at, 'DD/MM/Y HH:mm:ss') }}
                </td>
            </tr>
        </table>

        @if (count($item->compraitems))
            <table>
                <tr>
                    <th colspan="6"
                        style="border: 1px solid #0fb9b9;background: #0fb9b9;color:#ffffff;font-weight: 700;"
                        align="center">ENTRADAS</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #0fb9b9;color:#0fb9b9; font-weight:700;" align="center">
                        FECHA</th>
                    <th style="border: 1px solid #0fb9b9;color:#0fb9b9; font-weight:700;" align="center">
                        CANTIDAD</th>
                    <th style="border: 1px solid #0fb9b9;color:#0fb9b9; font-weight:700;" align="center">
                        ALMACÉN</th>
                    <th style="border: 1px solid #0fb9b9;color:#0fb9b9; font-weight:700;" align="center">
                        PRECIO</th>
                    <th style="border: 1px solid #0fb9b9;color:#0fb9b9; font-weight:700;" align="center">
                        COMPRA</th>
                    <th style="border: 1px solid #0fb9b9;color:#0fb9b9; font-weight:700; word-wrap: break-word; white-space: normal;"
                        align="center">PROVEEDOR</th>
                </tr>

                @foreach ($item->compraitems as $compraitem)
                    @if (count($compraitem->almacencompras) > 0)
                        @foreach ($compraitem->almacencompras as $almacencompra)
                            <tr>
                                <td style="border:1px solid #0fb9b9;" width="120px">
                                    {{ formatDate($compraitem->compra->date, 'DD/MM/Y') }}</td>
                                <td style="border:1px solid #0fb9b9;" width="120px">
                                    {{ $almacencompra->cantidad }}
                                    {{ $item->unit->name }}
                                </td>
                                <td style="border:1px solid #0fb9b9;" width="120px">
                                    {{ $almacencompra->almacen->name }}</td>
                                <td style="border:1px solid #0fb9b9;" width="120px">
                                    {{ $compraitem->price }}</td>
                                <td style="border:1px solid #0fb9b9;" width="150px">
                                    {{ $compraitem->compra->referencia }}</td>
                                <td style="border:1px solid #0fb9b9;word-wrap: break-word; white-space: normal;"
                                    width="200px">
                                    {{ $compraitem->compra->proveedor->name }}</td>
                            </tr>
                            @if (count($almacencompra->series))
                                @php
                                    $series = $almacencompra->series->chunk(6);
                                @endphp

                                @foreach ($series as $chunk)
                                    <tr>
                                        @foreach ($chunk as $serie)
                                            <td
                                                style="border:1px solid #0fb9b9;word-wrap: break-word; white-space: normal;">
                                                {{ $serie->serie }}</td>
                                        @endforeach

                                        @for ($i = count($chunk); $i < 6; $i++)
                                            <td style="border:1px solid #0fb9b9;"></td>
                                        @endfor
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </table>
        @endif

        @if (count($item->kardexes))
            <table>
                <tr>
                    <th colspan="6"
                        style="border: 1px solid #0fb9b9;background:#0fb9b9;color:#ffffff; font-weight:700;"
                        align="center">RESUMEN DE MOVIMIENTOS</th>
                </tr>
                <tr>
                    <th style="border:1px solid #0fb9b9;color:#0fb9b9;font-weight:700;" align="center">
                        FECHA</th>
                    <th style="border:1px solid #0fb9b9;color:#0fb9b9;font-weight:700;" align="center">
                        CANTIDAD</th>
                    <th style="border:1px solid #0fb9b9;color:#0fb9b9;font-weight:700;" align="center">
                        ALMACÉN</th>
                    <th style="border:1px solid #0fb9b9;color:#0fb9b9;font-weight:700;" align="center">
                        MOVIMIENTO</th>
                    <th style="border:1px solid #0fb9b9;color:#0fb9b9;font-weight:700;" align="center">
                        DETALLE</th>
                    <th style="border:1px solid #0fb9b9;color:#0fb9b9;font-weight:700;" align="center">
                        REFERENCIA</th>
                </tr>
                @foreach ($item->kardexes as $kardex)
                    <tr>
                        <td style="border:1px solid #0fb9b9;" align="center" valign="center">
                            {{ formatDate($kardex->date, 'DD/MM/Y') }}</td>
                        <td style="border:1px solid #0fb9b9;" align="center" valign="center">
                            {{ decimalOrInteger($kardex->cantidad) }}
                            {{ $item->unit->name }}
                        </td>
                        <td style="border:1px solid #0fb9b9;" align="center" valign="center">
                            {{ $kardex->almacen->name }}</td>
                        <td style="border:1px solid #0fb9b9;color: {{ $kardex->isEntrada() ? 'green' : 'red' }}"
                            align="center" valign="center">
                            @if ($kardex->isEntrada())
                                INGRESO
                            @else
                                SALIDA
                            @endif
                        </td>
                        <td style="border:1px solid #0fb9b9;word-wrap: break-word; white-space: normal;" align="center"
                            valign="center" width="200px">
                            {{ $kardex->detalle }}</td>
                        <td style="border:1px solid #0fb9b9;word-wrap: break-word; white-space: normal;" align="center"
                            valign="center" width="200px">
                            {{ $kardex->reference }}</td>
                    </tr>
                @endforeach
            </table>
        @endif


        @if (count($item->series))
            <table>
                <tr>
                    <th colspan="6"
                        style="border: 1px solid #0fb9b9;background:#0fb9b9; color:#ffffff;font-weight:700;"
                        align="center">SERIES</th>
                </tr>
                <tr>
                    <th style="border:1px solid #0fb9b9;color:#0fb9b9; font-weight:700;" align="center">
                        REGISTRADO</th>
                    <th style="border:1px solid #0fb9b9;color:#0fb9b9; font-weight:700;" align="center">
                        SERIE</th>
                    <th style="border:1px solid #0fb9b9;color:#0fb9b9; font-weight:700;" align="center">
                        ALMACÉN</th>
                    <th style="border:1px solid #0fb9b9;color:#0fb9b9; font-weight:700;" align="center">
                        ENTRADA</th>
                    <th style="border:1px solid #0fb9b9;color:#0fb9b9; font-weight:700;" align="center">
                        SALIDA</th>
                    <th style="border:1px solid #0fb9b9;color:#0fb9b9; font-weight:700;" align="center">
                        FECHA SALIDA</th>
                </tr>
                @foreach ($item->series as $serie)
                    <tr>
                        <td style="border:1px solid #0fb9b9;" align="center">
                            {{ formatDate($serie->created_at, 'DD/MM/Y') }}</td>
                        <td style="border:1px solid #0fb9b9; word-wrap: break-word;" align="center">
                            {{ $serie->serie }}</td>
                        <td style="border:1px solid #0fb9b9;" align="center">
                            {{ $serie->almacen->name }}</td>
                        <td style="border:1px solid #0fb9b9;" align="center">
                            @if ($serie->almacencompra)
                                @if ($serie->almacencompra->compraitem)
                                    @if ($serie->almacencompra->compraitem->compra)
                                        {{ $serie->almacencompra->compraitem->compra->referencia }}
                                    @endif
                                @endif
                            @endif
                        </td>
                        @if ($serie->itemserie)
                            <td style="border:1px solid #0fb9b9;" align="center">
                                @if ($serie->itemserie->tvitem)
                                    <b>{{ $serie->itemserie->tvitem->tvitemable->seriecompleta }}</b>
                                @endif
                            </td>
                            <td style="border:1px solid #0fb9b9;" align="center">
                                @if ($serie->itemserie->tvitem)
                                    {{ formatDate($serie->itemserie->tvitem->date, 'DD/MM/Y') }}
                                @endif
                            </td>
                        @else
                            <td style="border:1px solid #0fb9b9;"></td>
                            <td style="border:1px solid #0fb9b9;"></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        @endif
    @endforeach
@endif
