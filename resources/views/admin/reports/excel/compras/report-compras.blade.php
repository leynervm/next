<table>
    <thead>
        <tr>
            <th style="background: #0fb9b9;color:#ffffff;font-weight: 700;border:1px solid #0fb9b9;" align="center"
                valign="center">COMPRA</th>
            <th style="background: #0fb9b9;color:#ffffff;font-weight: 700;border:1px solid #0fb9b9;" align="center"
                valign="center">REGISTRADO</th>
            <th style="background: #0fb9b9;color:#ffffff;font-weight: 700;border:1px solid #0fb9b9;" align="center"
                valign="center">FECHA COMPRA</th>
            <th style="background: #0fb9b9;color:#ffffff;font-weight: 700;border:1px solid #0fb9b9;" align="center"
                valign="center">TIPO DE PAGO</th>
            <th style="background: #0fb9b9;color:#ffffff;font-weight: 700;border:1px solid #0fb9b9;" align="center"
                valign="center">SUCURSAL</th>
            <th style="background: #0fb9b9;color:#ffffff;font-weight: 700;border:1px solid #0fb9b9;" align="center"
                valign="center">USUARIO</th>
            <th style="background: #0fb9b9;color:#ffffff;font-weight: 700;border:1px solid #0fb9b9;" align="center"
                valign="center">MONEDA</th>
            <th style="background: #0fb9b9;color:#ffffff;font-weight: 700;border:1px solid #0fb9b9;" align="center"
                valign="center">T.C</th>
            <th style="background: #0fb9b9;color:#ffffff;font-weight: 700;border:1px solid #0fb9b9;" align="center"
                valign="center">IGV</th>
            <th style="background: #0fb9b9;color:#ffffff;font-weight: 700;border:1px solid #0fb9b9;" align="center"
                valign="center">TOTAL</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($compras as $item)
            @php
                $cuotas = [];
                $payments = [];
                if (count($item->cajamovimientos) > 0) {
                    $payments = $item->cajamovimientos->chunk(3);
                }
                if (count($item->cuotas) > 0) {
                    $cuotas = $item->cuotas->chunk(3);
                }
            @endphp

            <tr>
                <td style="border:1px solid #0fb9b9;" width="300px" valign="center">
                    {{ $item->referencia }}<br>
                    {{ $item->proveedor->name }} <br>
                    {{ $item->proveedor->document }}
                </td>
                <td style="border:1px solid #0fb9b9;" width="100px" align="center" valign="center">
                    {{ formatDate($item->created_at, 'DD/MM/Y') }}
                </td>
                <td style="border:1px solid #0fb9b9;" width="100px" align="center" valign="center">
                    {{ formatDate($item->date, 'DD/MM/Y') }}
                </td>
                <td style="border:1px solid #0fb9b9;" width="120px" align="center" valign="center">
                    {{ $item->typepayment->name }}
                </td>
                <td style="border:1px solid #0fb9b9;word-wrap: break-word; white-space: normal;" width="200px"
                    align="center" valign="center">
                    {{ $item->sucursal->name }}
                </td>
                <td style="border:1px solid #0fb9b9;word-wrap: break-word; white-space: normal;" width="200px"
                    align="center" valign="center">
                    {{ $item->user->name }}
                </td>
                <td style="border:1px solid #0fb9b9;" width="100px" align="center" valign="center">
                    {{ $item->moneda->currency }}
                </td>
                <td style="border:1px solid #0fb9b9;" width="100px" align="center" valign="center"
                    data-format="#,##0.00">
                    @if ($item->tipocambio > 0)
                        {{ number_format($item->tipocambio, 2, '.', '') }}
                    @endif
                </td>
                <td style="border:1px solid #0fb9b9;" width="100px" align="center" valign="center"
                    data-format="#,##0.00">
                    {{ number_format($item->igv, 2, '.', '') }}
                </td>
                <td style="border:1px solid #0fb9b9;" width="100px" align="center" valign="center"
                    data-format="#,##0.00">
                    {{ number_format($item->total, 2, '.', '') }}
                </td>
            </tr>

            @if ($detallado && count($cuotas) > 0)
                @foreach ($cuotas as $chunk)
                    <tr>
                        @if ($loop->first)
                            <td style="color:#0fb9b9;border:1px solid #0fb9b9;font-weight: 700;"
                                rowspan="{{ count($cuotas) }}" valign="center">
                                CUOTAS DE PAGO</td>
                        @endif
                        @foreach ($chunk as $cuota)
                            <td style="border:1px solid #0fb9b9;" align="center" valign="center">
                                Cuota {{ substr('0000' . $cuota->cuota, -3) }}
                            </td>
                            <td style="border:1px solid #0fb9b9;" align="center" valign="center" data-format="#,##0.00">
                                {{ number_format($cuota->amount, 2, '.', '') }}
                            </td>
                            <td style="border:1px solid #0fb9b9;color:{{ $cuota->cajamovimientos->sum('amount') == $cuota->amount ? 'green' : 'red' }}"
                                align="center" valign="center">
                                @if ($cuota->cajamovimientos->sum('amount') == $cuota->amount)
                                    PAGADO
                                @else
                                    PENDIENTE
                                @endif
                            </td>
                        @endforeach
                        @for ($i = count($chunk); $i < 3; $i++)
                            <td style="border:1px solid #0fb9b9;" colspan="3"></td>
                        @endfor
                    </tr>
                @endforeach
            @endif

            @if ($detallado && count($payments) > 0)
                @foreach ($payments as $chunk)
                    <tr>
                        @if ($loop->first)
                            <td style="color:#0fb9b9;border:1px solid #0fb9b9;font-weight: 700;"
                                rowspan="{{ count($payments) }}" valign="center">
                                HISTORIAL DE PAGOS</td>
                        @endif
                        @foreach ($chunk as $payment)
                            <td style="border:1px solid #0fb9b9;" align="center" valign="center">
                                {{ formatDate($payment->date, 'DD/MM/Y') }}
                            </td>
                            <td style="border:1px solid #0fb9b9;" align="center" valign="center">
                                {{ $payment->methodpayment->name }}
                            </td>
                            <td style="border:1px solid #0fb9b9;" align="center" valign="center" data-format="#,##0.00">
                                {{ number_format($payment->amount, 2, '.', '') }}
                            </td>
                        @endforeach
                        @for ($i = count($chunk); $i < 3; $i++)
                            <td style="border:1px solid #0fb9b9;" colspan="3"></td>
                        @endfor
                    </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>

<table>
    <thead>
        @foreach ($sumatorias as $item)
            <tr>
                <th style="border:1px solid #0fb9b9;font-weight:700;" align="right" valign="center">
                    TOTAL {{ $item->moneda->simbolo }}</th>
                <th style="border:1px solid #0fb9b9;font-weight:700;" colspan="9" align="right" valign="center"
                    data-format="#,##0.00">
                    {{ number_format($item->total, 2, '.', '') }}
                    {{-- {{ $item->moneda->currency }} --}}
                </th>
            </tr>
        @endforeach
    </thead>
</table>
