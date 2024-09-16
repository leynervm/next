<?php

namespace App\Http\Controllers;

use App\Models\Cajamovimiento;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PrintController extends Controller
{

    public function imprimirticket(Cajamovimiento $cajamovimiento)
    {
        $tmp = public_path('fonts/');
        $options = [
            'isHtml5ParserEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/dompdf.log.htm'),
            'fontDir' => $tmp,
            'fontCache' => $tmp,
            'tempDir' => $tmp,
            'chroot' => $tmp,
            'defaultFont' => 'Ubuntu'
        ];

        $pdf = PDF::setOption($options)->setPaper([0, 0, 226.77, 350])->loadView('print.ticket', compact('cajamovimiento'));
        return $pdf->stream('VOUCHER-PAGO-' . $cajamovimiento->id . '.pdf');
    }
}
