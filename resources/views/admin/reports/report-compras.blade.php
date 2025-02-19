@extends('admin.reports.snappyPDF.layout')
@section('titulo', $titulo)

<table class="table table-bordered border-dark table-responsive text-10 font-normal">
    <thead>
        <tr class="border-start border-end border-dark">
            <th class="p-2 text-start">COMPRA</th>
            <th class="p-2">FECHA COMPRA</th>
            <th class="p-2">IGV</th>
            <th class="p-2">TOTAL</th>
            <th class="p-2">T.C</th>
            <th class="p-2">TIPO DE PAGO</th>
            <th class="p-2">SUCURSAL</th>
            <th class="p-2">USUARIO</th>
            <th class="p-2">REGISTRADO</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($compras as $item)
            @php
                $cuotas = [];
                $payments = [];
                if (count($item->cajamovimientos) > 0) {
                    $payments = $item->cajamovimientos->chunk(2);
                }
                if (count($item->cuotas) > 0) {
                    $cuotas = $item->cuotas->chunk(2);
                }
            @endphp

            <tr class="border-start border-end border-dark">
                <td class="p-2 align-middle leading-none text-10" width="150px">
                    <p class="font-semibold text-justify m-0 text-12">
                        {{ $item->referencia }}</p>
                    <p class="font-normal text-justify">
                        {{ $item->proveedor->name }} <br>
                        {{ $item->proveedor->document }}
                    </p>
                </td>
                <td class="p-2 text-center align-middle">
                    {{ formatDate($item->date, 'DD/MM/Y') }}
                </td>
                <td class="p-2 text-center align-middle">
                    {{ $item->moneda->simbolo }}
                    {{ number_format($item->igv, 2, '.', ', ') }}
                </td>
                <td class="p-2 text-center align-middle">
                    {{ $item->moneda->simbolo }}
                    {{ number_format($item->total, 2, '.', ', ') }}
                </td>
                <td class="p-2 text-center align-middle">
                    @if ($item->tipocambio > 0)
                        {{ $item->moneda->simbolo }}
                        {{ number_format($item->tipocambio, 2, '.', ', ') }}
                    @endif
                </td>
                <td class="p-2 text-center align-middle">
                    {{ $item->typepayment->name }}
                </td>
                <td class="p-2 text-center align-middle">
                    {{ $item->sucursal->name }}
                </td>
                <td class="p-2 text-center align-middle">
                    {{ $item->user->name }}
                </td>
                <td class="p-2 text-center align-middle">
                    {{ formatDate($item->created_at, 'DD/MM/Y') }}
                </td>
            </tr>

            @if ($detallado && count($cuotas) > 0)
                <tr>
                    <td colspan="3" rowspan="{{ count($cuotas) + 1 }}" valign="center"
                        class="border-start font-semibold text-center border-end border-dark align-middle">
                        CUOTAS DE PAGO</td>
                </tr>
                @foreach ($cuotas as $chunk)
                    <tr>
                        @foreach ($chunk as $cuota)
                            <td class="p-2 border-start border-dark">
                                Cuota {{ substr('0000' . $cuota->cuota, -3) }}
                            </td>
                            <td class="p-2">
                                {{ $item->moneda->simbolo }}
                                {{ number_format($cuota->amount, 2, '.', ', ') }}
                                {{ $item->moneda->currency }}
                            </td>
                            <td class="p-2 border-end border-dark font-semibold">
                                @if ($cuota->cajamovimientos->sum('amount') == $cuota->amount)
                                    PAGADO
                                @else
                                    PENDIENTE
                                @endif
                            </td>
                        @endforeach
                        @for ($i = count($chunk); $i < 2; $i++)
                            <td colspan="3" class="border-end border-dark font-semibold"></td>
                        @endfor
                    </tr>
                @endforeach
            @endif

            @if ($detallado && count($payments) > 0)
                <tr>
                    <td colspan="3" rowspan="{{ count($payments) + 1 }}" valign="center"
                        class="border-start font-semibold text-center border-end border-dark align-middle">
                        HISTORIAL DE PAGOS</td>
                </tr>
                @foreach ($payments as $chunk)
                    <tr>
                        @foreach ($chunk as $payment)
                            <td class="p-2 border-start border-dark">
                                {{ formatDate($payment->date, 'DD/MM/Y') }}
                            </td>
                            <td class="p-2">
                                {{ $payment->methodpayment->name }}
                            </td>
                            <td class="p-2 border-end border-dark">
                                {{ $payment->moneda->simbolo }}
                                {{ number_format($payment->amount, 2, '.', ', ') }}
                                {{ $payment->moneda->currency }}
                            </td>
                        @endforeach
                        @for ($i = count($chunk); $i < 2; $i++)
                            <td colspan="3" class="border-end border-dark font-semibold"></td>
                        @endfor
                    </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>

<table class="table table-bordered border-dark table-responsive text-10 font-normal mt-5">
    <thead>
        @foreach ($sumatorias as $item)
            <tr class="border-start border-end border-dark">
                <th class="p-2 text-end">TOTAL</th>
                <th class="p-2 text-end border-start border-dark" width="200px">
                    {{ $item->moneda->simbolo }}
                    {{ number_format($item->total, 2, '.', ', ') }}
                    {{ $item->moneda->currency }}
                </th>
            </tr>
        @endforeach
    </thead>
</table>
