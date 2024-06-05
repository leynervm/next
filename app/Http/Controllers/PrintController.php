<?php

namespace App\Http\Controllers;

use App\Models\Cajamovimiento;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PrintController extends Controller
{

    public function imprimirticket(Cajamovimiento $cajamovimiento)
    {
        $pdf =  PDF::setPaper([0, 0, 226.77, 350])->loadView('print.ticket', compact('cajamovimiento'));
        return $pdf->stream();
    }
}
