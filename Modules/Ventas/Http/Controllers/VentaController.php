<?php

namespace Modules\Ventas\Http\Controllers;

use App\Models\Concept;
use App\Models\Moneda;
use App\Models\Monthbox;
use App\Models\Openbox;
use App\Models\Pricetype;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Modules\Ventas\Entities\Venta;
use Nwidart\Modules\Facades\Module;

class VentaController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.ventas')->only('index');
        $this->middleware('can:admin.ventas.create')->only('create');
        $this->middleware('can:admin.ventas.edit')->only('show');
        $this->middleware('can:admin.ventas.delete')->only('delete');
        $this->middleware('can:admin.ventas.cobranzas')->only('cobranzas');
    }

    public function index()
    {
        $openbox = Openbox::mybox(auth()->user()->sucursal_id)->first();
        $monthbox = Monthbox::usando(auth()->user()->sucursal_id)->first();

        return view('ventas::index', compact('openbox', 'monthbox'));
    }

    public function create()
    {

        $moneda = Moneda::default()->first();
        $concept = Concept::ventas()->first();

        $seriecomprobante = auth()->user()->sucursal->seriecomprobantes()
            ->withWhereHas('typecomprobante', function ($query) {
                if (!Module::isEnabled('Facturacion')) {
                    $query->default();
                }
                $query->whereNotIn('code', ['07', '09', '13'])->orderBy('code', 'asc');
            })->orderBy('default', 'desc')->first();

        $pricetypes = Pricetype::default();
        if (count($pricetypes->get()) > 0) {
            $pricetype = $pricetypes->first();
        } else {
            $pricetype = Pricetype::orderBy('id', 'asc')->first();
        }

        return view('ventas::ventas.create', compact('seriecomprobante', 'moneda', 'concept', 'pricetype'));
    }

    public function show(Venta $venta)
    {
        $concept = Concept::paycuota()->first();
        return view('ventas::ventas.show', compact('venta', 'concept'));
    }


    public function cobranzas()
    {
        return view('ventas::ventas.cobranzas');
    }

    public function imprimirA4(Venta $venta)
    {

        $pdf =  PDF::loadView('ventas::pdf.a4', compact('venta'));
        if (Module::isEnabled('Facturacion')) {
            if ($venta->comprobante) {
                $comprobante = $venta->comprobante;
                $pdf = PDF::loadView('facturacion::pdf.a4', compact('comprobante'));
            }
        }
        return $pdf->stream();
    }
}
