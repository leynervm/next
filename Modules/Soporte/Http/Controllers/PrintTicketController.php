<?php

namespace Modules\Soporte\Http\Controllers;

use Barryvdh\Snappy\Facades\SnappyPdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Soporte\Entities\Ticket;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Browsershot\Browsershot;

class PrintTicketController extends Controller
{
    public function registro($seriecompleta)
    {
        if (empty($seriecompleta)) {
            return redirect('404');
        }

        $ticket = Ticket::with([
            'client',
            'telephones',
            'contact',
            'estate',
            'atencion',
            'condition',
            'priority',
            'entorno',
            'areawork',
            'sucursal',
            'user',
            'userasigned',
            'direccion' => function ($q) {
                $q->with('ubigeo');
            },
            'equipo' => function ($q) {
                $q->with(['typeequipo', 'marca']);
            }
        ])->where('seriecompleta', $seriecompleta)->first();
        if (empty($ticket)) {
            return redirect('404');
        }

        // return $ticket;

        $empresa = view()->shared('empresa');
        $sucursal = $ticket->sucursal;
        $titulo = $ticket->entorno->name . "<br/>" . $ticket->seriecompleta;
        $textoQR = asset('tickets/' . $ticket->seriecompleta . '/tracking');
        // $svg = QrCode::format('svg')->size(100)->generate($textoQR);
        $qr = $textoQR;

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

        $pdf = PDF::setOption($options)->setPaper('a5', 'landscape')->loadView('soporte::pdf.ingreso', compact('ticket', 'empresa', 'sucursal', 'titulo', 'qr'));
        return $pdf->stream($ticket->seriecompleta . '.pdf');


        // necesario si estás en servidor
        //->setOption('args', ['--no-sandbox']) 
        // $convertPNG = Browsershot::html($svg)->setOption('args', ['--no-sandbox'])
        //     ->windowSize(120, 120)->screenshot();
        // $qr = 'data:image/png;base64,' . base64_encode($convertPNG);

        // $pdf = SnappyPdf::loadView('soporte::pdf.registro', compact('ticket', 'empresa', 'sucursal', 'titulo', 'qr'))
        //     ->setOptions([
        //         'header-html' => view('admin.reports.snappyPDF.header', compact('titulo')),
        //         'margin-top' => '29.5mm',
        //         'margin-bottom' => '10mm',
        //         'margin-left' => '0mm',
        //         'margin-right' => '0mm',
        //         'header-spacing' => 5,
        //         'page-size' => 'A5',
        //         'orientation' => 'Landscape',
        //         'footer-html' => view('admin.reports.snappyPDF.footer'),
        //         'encoding' => 'UTF-8',
        //     ]);

        // return $pdf->inline("$titulo.pdf");
    }
}
