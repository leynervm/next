<?php

namespace Modules\Facturacion\Http\Controllers;

use App\Models\Guia;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use Nwidart\Modules\Facades\Module;
use Nwidart\Modules\Routing\Controller;
use ZipArchive;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GuiaController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.facturacion.guias')->only('index');
        $this->middleware('can:admin.facturacion.guias.create')->only(['create', 'show']);
        $this->middleware('can:admin.facturacion.guias.motivos')->only('motivos');
    }

    public function index()
    {
        return view('facturacion::guias.index');
    }

    public function create()
    {
        $sucursal = auth()->user()->sucursal;
        return view('facturacion::guias.create', compact('sucursal'));
    }

    public function show(Guia $guia)
    {
        $this->authorize('sucursal', $guia);

        $guia = Guia::with(['tvitems' => function ($query) {
            $query->withTrashed()->orderBy('date', 'asc');
        }])->find($guia->id);
        return view('facturacion::guias.show', compact('guia'));
    }


    public function motivos()
    {
        return view('facturacion::guias.motivos');
    }

    public function imprimirA4(Guia $guia)
    {
        $this->authorize('sucursal', $guia);

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

            $pdf = PDF::setOption($options)->loadView('facturacion::pdf.guias.a4', compact('guia'));
            return $pdf->stream($guia->seriecompleta . '.pdf');
        }
    }

    public function downloadXML(Guia $guia, $type)
    {

        // $this->authorize('sucursal', $comprobante);
        $code_comprobante = $guia->seriecomprobante->typecomprobante->code;
        $r = $type == "cdr" ? 'R-' : '';
        $rutazip = 'xml/' . $code_comprobante . '/' . $r . $guia->sucursal->empresa->document . '-' . $code_comprobante . '-' . $guia->seriecompleta . '.zip';

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

    public function imprimirA4Public(guia $guia, $format)
    {
        if (Module::isEnabled('Facturacion')) {
            if (!in_array($format, ['a4', 'a5'])) {
                abort(404);
            }

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

            $pdf = PDF::setOption($options)->loadView('facturacion::pdf.guias.' . $format, compact('guia'));
            return $pdf->stream($guia->seriecompleta . '.pdf');
        }
    }
}
