<?php

namespace Modules\Facturacion\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Facturacion\Entities\Comprobante;
use Nwidart\Modules\Facades\Module;
use Nwidart\Modules\Routing\Controller;
use Illuminate\Support\Str;
use ZipArchive;
use Symfony\Component\HttpFoundation\StreamedResponse;


class FacturacionController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.facturacion')->only('index');
        // $this->middleware('can:admin.facturacion.edit')->only('show');
    }

    public function index()
    {
        return view('facturacion::index');
    }

    public function imprimirA4(Comprobante $comprobante)
    {
        $this->authorize('sucursal', $comprobante);
        if (Module::isEnabled('Facturacion')) {

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

            $comprobante->load([
                'facturableitems',
                'client',
                'moneda',
                'typepayment',
                'seriecomprobante.typecomprobante',
                'sucursal' => function ($query) {
                    $query->with(['ubigeo', 'empresa.telephones']);
                }
            ]);
            $pdf = PDF::setOption($options)->loadView('facturacion::pdf.comprobantes.a4', compact('comprobante'));
            return $pdf->stream($comprobante->seriecompleta . '.pdf');
        }
    }

    public function imprimirA5(Comprobante $comprobante)
    {
        $this->authorize('sucursal', $comprobante);
        if (Module::isEnabled('Facturacion')) {

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

            $comprobante->load([
                'facturableitems',
                'client',
                'moneda',
                'typepayment',
                'seriecomprobante.typecomprobante',
                'sucursal' => function ($query) {
                    $query->with(['ubigeo', 'empresa.telephones']);
                }
            ]);
            $pdf = PDF::setOption($options)->setPaper('a5', 'portarait')->loadView('facturacion::pdf.comprobantes.a5', compact('comprobante'));
            return $pdf->stream($comprobante->seriecompleta . '.pdf');
        }
    }

    public function imprimirticket(Comprobante $comprobante)
    {
        $this->authorize('sucursal', $comprobante);
        if (Module::isEnabled('Facturacion')) {
            // 1 mm = 2.8346 Points;
            $heightHeader = 250;
            $heightBody = (count($comprobante->facturableitems) * 16 * 12) * 2.8346;
            $heightFooter = 400; #Incl. Totales, QR, Leyenda, Info, Web
            $heightPage = number_format($heightHeader + $heightBody + $heightFooter, 2, '.', '');
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

            $comprobante->load([
                'facturableitems',
                'client',
                'moneda',
                'typepayment',
                'seriecomprobante.typecomprobante',
                'sucursal' => function ($query) {
                    $query->with(['ubigeo', 'empresa.telephones']);
                }
            ]);
            $pdf = PDF::setOption($options)->setPaper([0, 0, 226.77, $heightPage])->loadView('facturacion::pdf.comprobantes.ticket', compact('comprobante'));
            return $pdf->stream($comprobante->seriecompleta . '.pdf');
        }
    }

    public function downloadXML($comprobante, $type)
    {

        if (!in_array($type, ['xml', 'cdr']) || !Str::isUuid($comprobante)) {
            abort(404);
        }

        $comprobante = Comprobante::where('uuid', $comprobante)->first();
        if (empty($comprobante)) {
            abort(404);
        }

        $comprobante->load([
            'facturableitems',
            'client',
            'moneda',
            'typepayment',
            'seriecomprobante.typecomprobante',
            'sucursal' => function ($query) {
                $query->with(['ubigeo', 'empresa.telephones']);
            }
        ]);

        // $this->authorize('sucursal', $comprobante);
        $code_comprobante = $comprobante->seriecomprobante->typecomprobante->code;
        $r = $type == "cdr" ? 'R-' : '';
        $rutazip = 'xml/' . $code_comprobante . '/' . $r . $comprobante->sucursal->empresa->document . '-' . $code_comprobante . '-' . $comprobante->seriecompleta . '.zip';

        // dd(Storage::disk('local')->path($rutazip));
        if (Storage::disk('local')->exists($rutazip)) {

            $zip = new ZipArchive();
            if ($zip->open(Storage::disk('local')->path($rutazip)) === TRUE) {
                $xmlFileName = null;

                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $file = $zip->getNameIndex($i);
                    if (pathinfo($file, PATHINFO_EXTENSION) == 'xml') {
                        $xmlFileName = $file;
                        break;
                    }
                }

                if ($xmlFileName) {
                    $xmlContent = $zip->getFromName($xmlFileName);
                    $zip->close();

                    return new StreamedResponse(function () use ($xmlContent) {
                        echo $xmlContent;
                    }, 200, [
                        'Content-Type' => 'application/xml',
                        'Content-Disposition' => 'attachment; filename="' . basename($xmlFileName) . '"',
                    ]);
                } else {
                    $zip->close();
                    return response()->json(['error' => 'No se encontró archivo XML dentro del ZIP.'], 404);
                }
            } else {
                return response()->json(['error' => 'No se pudo abrir el archivo ZIP.'], 500);
            }
        } else {
            return response()->json(['error' => 'No existe el archivo ZIP.'], 404);
        }
    }

    public function imprimirpublic($comprobante, $format)
    {
        if (Module::isEnabled('Facturacion')) {
            if (!in_array($format, ['a4', 'a5', 'ticket']) || !Str::isUuid($comprobante)) {
                abort(404);
            }

            $comprobante = Comprobante::where('uuid', $comprobante)->first();
            if (empty($comprobante)) {
                abort(404);
            }

            $options = [];
            $tmp = public_path('fonts/');
            $comprobante->load([
                'facturableitems',
                'client',
                'moneda',
                'typepayment',
                'seriecomprobante.typecomprobante',
                'sucursal' => function ($query) {
                    $query->with(['ubigeo', 'empresa.telephones']);
                }
            ]);

            switch ($format) {
                case 'ticket':
                    $heightHeader = 250;
                    $heightBody = (count($comprobante->facturableitems) * 16 * 12) * 2.8346;
                    $heightFooter = 400; #Incl. Totales, QR, Leyenda, Info, Web
                    $heightPage = number_format($heightHeader + $heightBody + $heightFooter, 2, '.', '');
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
                    $pdf = PDF::setOption($options)->setPaper([0, 0, 226.77, $heightPage]);
                    break;
                case 'a5':
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
                    $pdf = PDF::setOption($options)->setPaper('a5', 'portarait');
                    break;
                default:
                    $pdf = PDF::setOption($options);
                    break;
            }

            return $pdf->loadView('facturacion::pdf.comprobantes.' . $format, compact('comprobante'))
                ->stream($comprobante->seriecompleta . '.pdf');
        } else {
            abort(404);
        }
    }


    // public function downloadCDR(Comprobante $comprobante)
    // {

    //     // dd($comprobante);
    //     $code_comprobante = $comprobante->seriecomprobante->typecomprobante->code;
    //     $filename =  'R-' . $comprobante->sucursal->empresa->document . '-' . $code_comprobante . '-' . $comprobante->seriecompleta;
    //     $rutazip = 'xml/' . $code_comprobante . '/' .  $filename . '.zip';

    //     if (Storage::disk('local')->exists($rutazip)) {

    //         $zip = new ZipArchive();
    //         if ($zip->open(Storage::disk('local')->path($rutazip)) === TRUE) {
    //             $zip->extractTo(storage_path('app/temp'), $filename . '.xml');
    //             $zip->close();

    //             $tempXmlPath = storage_path('app/temp/' . $filename . '.xml');
    //             return response()->download($tempXmlPath, $filename . '.xml')->deleteFileAfterSend(true);
    //         } else {
    //             return response()->json(['error' => 'No se pudo abrir el archivo ZIP.'], 500);
    //         }
    //     } else {
    //         return response()->json(['error' => 'No existe el archivo ZIP.'], 404);
    //     }
    // }
}
