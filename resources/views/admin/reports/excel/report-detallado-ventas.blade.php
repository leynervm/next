<html>
<table style="display: table;border:1px solid #0fb9b9;">
    {{-- <thead>
        <tr>
            <th style="color:#0FB9B9;font-weight: 700;" align="center" valign="middle">
                HISTORIAL DE VENTAS</th>
        </tr>
    </thead> --}}
    <tbody>
        @if (count($ventas) > 0)
            @foreach ($ventas as $item)
                <tr>
                    <th rowspan="{{ 2 + count($item->tvitems) }}"
                        style="font-weight: 700; color:#ffffff;background-color: #0fb9b9; border: 1px solid #0fb9b9;width: 120px;"
                        align="center" valign="middle">
                        {{ $item->seriecompleta }} <br>
                        {{ $item->seriecomprobante->typecomprobante->name }}</th>
                    <th style="font-weight: 700;border: 1px solid #0fb9b9; width: 100px;" align="center" valign="middle">
                        {{ formatDate($item->date, 'DD/MM/Y') }}</th>
                    <th style="font-weight: 700;border: 1px solid #0fb9b9; width: 100px;" align="center" valign="middle">
                        {{ $item->typepayment->name }}</th>
                    <th style="font-weight: 700;border: 1px solid #0fb9b9;width: 700px;word-wrap: break-word; max-width: 700px;"
                        align="left" valign="middle">
                        {{ $item->client->document }} - {{ $item->client->name }} - {{ $item->direccion }}</th>
                    <th style="font-weight: 700;border: 1px solid #0fb9b9; width: 150px;word-wrap: break-word; max-width: 150px;"
                        align="right" valign="middle">{{ $item->sucursal->name }} </th>
                    <th style="font-weight: 700;border: 1px solid #0fb9b9; width: 150px;word-wrap: break-word; max-width: 150px;"
                        align="right" valign="middle">{{ $item->user->name }} </th>
                </tr>

                <tr>
                    <td style="border:1px solid #0fb9b9;" align="center" valign="middle">CANT.</td>
                    <td style="border:1px solid #0fb9b9;" align="center" valign="middle">UN. MEDIDA</td>
                    <td style="border:1px solid #0fb9b9;" align="center" valign="middle">DESCRIPCIÓN</td>
                    <td style="border:1px solid #0fb9b9;" align="center" valign="middle">PRECIO</td>
                    <td style="border:1px solid #0fb9b9;" align="center" valign="middle">IMPORTE</td>
                </tr>

                @foreach ($item->tvitems as $tvitem)
                    <tr>
                        <td style="border:1px solid #0fb9b9;" align="center" valign="middle">
                            {{ $tvitem->cantidad }}</td>
                        <td style="border:1px solid #0fb9b9;" align="center" valign="middle">
                            {{ $tvitem->producto->unit->name }}</td>
                        <td style="border:1px solid #0fb9b9;" align="justify" valign="middle">
                            {{ $tvitem->producto->name }}</td>
                        <td style="border:1px solid #0fb9b9;" align="right" valign="middle" data-format="#,##0.00">
                            {{ $tvitem->price }}</td>
                        <td style="border:1px solid #0fb9b9;" align="right" valign="middle" data-format="#,##0.00">
                            {{ $tvitem->total }}</td>
                    </tr>
                @endforeach

                <tr>
                    {{-- <td></td> --}}
                    <th style="font-weight: 700;" colspan="5" align="right" valign="middle">IGV</th>
                    <th style="font-weight: 700;border:1px solid #0fb9b9;background: #0fb9b9;color:#ffffff;"
                        align="right" valign="middle" data-format="#,##0.00">
                        {{ $item->igv + $item->igvgratuito }}
                    </th>
                </tr>
                <tr>
                    {{-- <td></td> --}}
                    <th style="font-weight: 700;" colspan="5" align="right" valign="middle">TOTAL</th>
                    <th style="font-weight: 700;border:1px solid #0fb9b9;background: #0fb9b9;color:#ffffff;"
                        align="right" valign="middle" data-format="#,##0.00">
                        {{ $item->total }}
                    </th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<table style="display: table;border:1px solid #0fb9b9;">
    <tbody>
        @foreach ($sumatorias as $item)
            <tr>
                <td>{{ $item->moneda->currency }} {{ $item->moneda->simbolo }}</td>
                <td>{{ $item->total }}</td>
                <td>{{ $item->moneda->simbolo }} {{ $item->total }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</html>
