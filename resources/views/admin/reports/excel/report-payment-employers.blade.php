<html>
<table style="display: table;">
    <thead>
        <tr>
            <th style="background-color: #0fb9b9;color:#ffffff; font-weight: 700;">
                NOMBRES DEL PERSONAL</th>
            <th style="background-color: #0fb9b9;color:#ffffff; font-weight: 700;">
                MES PAGO</th>
            <th style="background-color: #0fb9b9;color:#ffffff; font-weight: 700;">
                SUELDO</th>
            <th style="background-color: #0fb9b9;color:#ffffff; font-weight: 700;">
                ADELANTOS</th>
            <th style="background-color: #0fb9b9;color:#ffffff; font-weight: 700;">
                DESCUENTOS</th>
            <th style="background-color: #0fb9b9;color:#ffffff; font-weight: 700;">
                BONOS</th>
            <th style="background-color: #0fb9b9;color:#ffffff; font-weight: 700;">
                TOTAL PAGADO</th>
            <th style="background-color: #0fb9b9;color:#ffffff; font-weight: 700;">
                SALDO</th>
            <th style="background-color: #0fb9b9;color:#ffffff; font-weight: 700;">
                SUCURSAL</th>
        </tr>
    </thead>
    @if (count($payments) > 0)
        <tbody>
            @php
                $saldos = 0;
                $sueldos = 0;
            @endphp
            @foreach ($payments as $item)
                @php
                    $montopagado = $item->employer->sueldo - $item->descuentos;
                    $sueldo_pagar = $item->employer->sueldo + $item->bonus - $item->descuentos;
                    $saldo = $sueldo_pagar - ($item->amount + $item->adelantos);
                    $saldos = $saldos + $saldo;
                    $sueldos = $sueldos + $item->employer->sueldo;
                @endphp
                <tr style="">
                    <td style="border:1px solid #0fb9b9;">
                        {{ $item->employer->name }}</td>
                    <td style="border:1px solid #0fb9b9;" align="center" valign="middle">
                        {{ formatDate($item->month, 'MMMM Y') }}
                    </td>
                    <td style="border:1px solid #0fb9b9;" data-format="#,##0.00">
                        {{-- {{ $item->moneda->simbolo }} --}}
                        {{ number_format($item->employer->sueldo, 2, '.', '') }}
                    </td>
                    <td style="border:1px solid #0fb9b9;" data-format="#,##0.00">
                        {{ number_format($item->adelantos, 2, '.', '') }}
                    </td>
                    <td style="border:1px solid #0fb9b9; {{ $item->descuentos > 0 ? 'egreso' : '' }}"
                        data-format="#,##0.00">
                        {{ number_format($item->descuentos, 2, '.', '') }}
                    </td>
                    <td style="border:1px solid #0fb9b9; {{ $item->bonus > 0 ? 'ingreso' : '' }}"
                        data-format="#,##0.00">
                        {{ number_format($item->bonus, 2, '.', '') }}
                    </td>
                    <td style="border:1px solid #0fb9b9;" data-format="#,##0.00">
                        {{-- {{ $item->moneda->simbolo }} --}}
                        {{ number_format($item->amount + $item->adelantos, 2, '.', '') }}
                    </td>
                    <td style="border:1px solid #0fb9b9; {{ $saldo > 0 ? 'egreso' : '' }}" data-format="#,##0.00">
                        @if ($saldo > 0)
                            {{ number_format($saldo, 2, '.', '') }}
                        @endif
                    </td>
                    <td style="border:1px solid #0fb9b9;" align="center" valign="middle">
                        {{ $item->employer->sucursal->name }}</td>
                </tr>
            @endforeach
        </tbody>
    @endif
</table>

@if (count($payments) > 0)
    <table style="display: table;font-weight: 700;">
        <tbody>
            <tr>
                <td style="font-weight:700;border:1px solid #0fb9b9; border-right: none;" colspan="7" align="right"
                    valign="middle">
                    DESCUENTOS TOTALES :</td>
                <td style="font-weight:700;border: 1px solid #0fb9b9;border-left:none;" colspan="2" align="right"
                    valign="middle" data-format="#,##0.00">
                    {{ number_format($payments->sum('descuentos'), 2, '.', '') }}
                </td>
            </tr>

            <tr>
                <td style="font-weight:700;border: 1px solid #0fb9b9;border-right: none;" align="right" valign="middle"
                    colspan="7">
                    TOTAL ADELANTOS :</td>
                <td style="font-weight:700;border: 1px solid #0fb9b9;border-left:none;" colspan="2"
                    data-format="#,##0.00">
                    {{ number_format($payments->sum('adelantos'), 2, '.', '') }}
                </td>
            </tr>

            <tr>
                <td style="font-weight:700;border: 1px solid #0fb9b9;border-right: none;" colspan="7" align="right"
                    valign="middle">
                    TOTAL BONOS :</td>
                <td style="font-weight:700;border: 1px solid #0fb9b9;border-left:none;" colspan="2"
                    data-format="#,##0.00">
                    {{ number_format($payments->sum('bonos'), 2, '.', '') }}
                </td>
            </tr>

            <tr>
                <td style="font-weight:700;border: 1px solid #0fb9b9;border-right: none;" colspan="7" align="right"
                    valign="middle">
                    TOTAL A PAGAR (SIN APLICAR CARGOS):</td>
                <td style="font-weight:700;border: 1px solid #0fb9b9;border-left:none;" colspan="2"
                    data-format="#,##0.00">
                    {{ number_format($sueldos, 2, '.', '') }}
                </td>
            </tr>

            <tr>
                <td style="font-weight:700;border: 1px solid #0fb9b9;border-right: none;" colspan="7" align="right"
                    valign="middle">
                    TOTAL A PAGAR (APLICANDO CARGOS):</td>
                <td style="font-weight:700;border: 1px solid #0fb9b9; border-left: none;" colspan="2" align="right"
                    valign="middle" data-format="#,##0.00">
                    {{ number_format($sueldos + $payments->sum('bonos') - $payments->sum('descuentos'), 2, '.', '') }}
                </td>
            </tr>

            <tr>
                <td style="font-weight:700;border: 1px solid #0fb9b9;border-right: none;" align="right" valign="middle"
                    colspan="7">
                    PAGO PARCIAL :</td>
                <td style="font-weight:700;border: 1px solid #0fb9b9;border-left:none;" colspan="2"
                    data-format="#,##0.00">
                    {{ number_format($payments->sum('amount') + $payments->sum('adelantos'), 2, '.', '') }}
                </td>
            </tr>

            <tr>
                <td style="font-weight:700;border: 1px solid #0fb9b9;border-right: none;" colspan="7" align="right"
                    valign="middle">
                    SALDOS :</td>
                <td style="font-weight:700;border: 1px solid #0fb9b9;border-left:none;color:{{ $saldos > 0 ? '#f50000' : '#0fb9b9' }}"
                    colspan="2" data-format="#,##0.00">
                    {{ number_format($saldos, 2, '.', '') }}
                </td>
            </tr>

            {{-- <tr>
                <td style="font-weight:700;border: 1px solid #0fb9b9;border-right: none;" align="right" valign="middle"
                    colspan="7">
                    AHORRADO :</td>
                <td style="font-weight:700;border: 1px solid #0fb9b9;border-left:none;" data-format="#,##0.00"
                    colspan="2">
                    {{ number_format($sueldos - ($sueldos + $payments->sum('bonos') - $payments->sum('descuentos')), 2, '.', '') }}
                </td>
            </tr> --}}

        </tbody>
    </table>
@endif

</html>
