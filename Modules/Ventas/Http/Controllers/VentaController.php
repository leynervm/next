<?php

namespace Modules\Ventas\Http\Controllers;

use App\Models\Concept;
use App\Models\Moneda;
use App\Models\Monthbox;
use App\Models\Openbox;
use App\Models\Pricetype;
use App\Models\Producto;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Modules\Ventas\Entities\Venta;
use Nwidart\Modules\Facades\Module;
use Nwidart\Modules\Routing\Controller;

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

        $empresa = mi_empresa();
        $moneda = Moneda::default()->first();
        $concept = Concept::ventas()->first();

        $seriecomprobante = auth()->user()->sucursal->seriecomprobantes()
            ->withWhereHas('typecomprobante', function ($query) {
                if (!Module::isEnabled('Facturacion')) {
                    $query->default();
                }
                $query->whereNotIn('code', ['07', '09', '13'])->orderBy('code', 'asc');
            })->orderBy('default', 'desc')->first();


        $pricetype = null;
        if ($empresa->usarlista()) {
            $pricetypes = Pricetype::default();
            if (count($pricetypes->get()) > 0) {
                $pricetype = $pricetypes->first();
            } else {
                $pricetype = Pricetype::orderBy('id', 'asc')->first();
            }
        }

        return view('ventas::ventas.create', compact('seriecomprobante', 'empresa', 'moneda', 'concept', 'pricetype'));
    }

    public function show(Venta $venta)
    {
        $this->authorize('sucursal', $venta);
        $venta->load([
            'client',
            'seriecomprobante.typecomprobante',
            'moneda',
            'sucursal.empresa',
            'typepayment',
            'cuotas',
            'cajamovimientos' => function ($query) {
                $query->with(['openbox.box', 'moneda', 'methodpayment', 'monthbox']);
            },
            'tvitems' => function ($query) {
                $query->with(['itemseries.serie', 'almacen', 'producto' => function ($subQuery) {
                    $subQuery->with('unit')->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderBy('default', 'desc')->limit(1);
                    }]);
                }]);
            }
        ]);
        return view('ventas::ventas.show', compact('venta'));
    }


    public function cobranzas()
    {
        return view('ventas::ventas.cobranzas');
    }

    public function imprimirticket(Venta $venta)
    {

        if (Module::isEnabled('Facturacion')) {
            if ($venta->comprobante) {
                return redirect()->route('admin.facturacion.print.ticket', $venta->comprobante);
            }
        }
        $heightHeader = 250;
        $heightBody = (count($venta->tvitems) * 3 * 12) * 2.8346;
        $heightFooter = 400; #Incl. Totales, QR, Leyenda, Info, Web
        $heightPage = number_format($heightHeader + $heightBody + $heightFooter, 2, '.', '');
        $pdf =  PDF::setPaper([0, 0, 226.77, $heightPage])->loadView('ventas::pdf.ticket', compact('venta'));
        return $pdf->stream();
    }
}
