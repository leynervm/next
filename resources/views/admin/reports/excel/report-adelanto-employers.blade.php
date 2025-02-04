<html>
@php
    $max = $adelantos->max('cajamovimientos_count');
@endphp

<table style="display: table;border:1px solid #0fb9b9;">
    <thead>
        <tr>
            <th style="color:#0FB9B9;font-weight: 700;" colspan="{{ $max }}" align="center" valign="middle">
                HISTORIAL DE ADELANTOS</th>
        </tr>
    </thead>
    <tbody>
        @if (count($adelantos) > 0)
            @foreach ($adelantos as $employer)
                <tr>
                    <th style="font-weight: 700; color:#ffffff;background-color: #0fb9b9; border: 1px solid #0fb9b9;"
                        colspan="{{ $max - 1 }}" align="left" valign="middle">
                        {{ $employer->name }}</th>
                    <th style="font-weight: 700; color:#ffffff;background-color: #0fb9b9;border: 1px solid #0fb9b9;"
                        align="right" valign="middle">
                        {{ $employer->sucursal->name }} </th>
                </tr>

                <tr>
                    @foreach ($employer->cajamovimientos as $key => $value)
                        <td style="border-left:1px solid #0fb9b9; border-right: 1px solid #0fb9b9;" align="center"
                            valign="middle">
                            {{ formatDate($employer->cajamovimientos[$key]->date, 'ddd DD MMMM Y') }}</td>
                    @endforeach
                    @for ($i = 0; $i < $max - count($employer->cajamovimientos); $i++)
                        <td style="border-left:1px solid #0fb9b9; border-right: 1px solid #0fb9b9;"></td>
                    @endfor
                </tr>

                <tr>
                    @foreach ($employer->cajamovimientos as $key => $value)
                        <td style="border-left:1px solid #0fb9b9; border-right: 1px solid #0fb9b9;" align="center"
                            valign="middle">
                            {{ $employer->cajamovimientos[$key]->methodpayment->name }}</td>
                    @endforeach
                    @for ($i = 0; $i < $max - count($employer->cajamovimientos); $i++)
                        <td style="border-left:1px solid #0fb9b9; border-right: 1px solid #0fb9b9;"></td>
                    @endfor
                </tr>

                <tr>
                    @foreach ($employer->cajamovimientos as $key => $value)
                        <td style="border-left:1px solid #0fb9b9; border-right: 1px solid #0fb9b9;" align="center"
                            valign="middle" data-format="#,##0.00">
                            {{ $employer->cajamovimientos[$key]->amount }}</td>
                    @endforeach
                    @for ($i = 0; $i < $max - count($employer->cajamovimientos); $i++)
                        <td style="border-left:1px solid #0fb9b9; border-right: 1px solid #0fb9b9;">
                        </td>
                    @endfor
                </tr>

                <tr>
                    <td colspan="{{ $max - 1 }}"
                        style="border:1px solid #0fb9b9; border-right:none; font-weight: 700;" align="left"
                        valign="middle">
                        TOTAL</td>
                    <td style="border:1px solid #0fb9b9; border-left:none; font-weight: 700;" align="right"
                        valign="middle" data-format="#,##0.00">
                        {{ number_format($employer->cajamovimientos->sum('amount'), 2, '.', ', ') }}
                    </td>
                </tr>
                <tr>
                    <td></td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
</html>
