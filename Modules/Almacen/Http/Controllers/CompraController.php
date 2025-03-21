<?php

namespace Modules\Almacen\Http\Controllers;

use App\Models\Pricetype;
use App\Models\Producto;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Almacen\Entities\Compra;
use Nwidart\Modules\Facades\Module;
use Nwidart\Modules\Routing\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;


class CompraController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.almacen.compras')->only('index');
        $this->middleware('can:admin.almacen.compras.create')->only(['create']);
        $this->middleware('can:admin.almacen.compras.payments')->only('cuentaspagar');
    }

    public function index()
    {
        return view('almacen::compras.index');
    }

    public function create()
    {
        return view('almacen::compras.create');
    }

    public function show(Compra $compra)
    {

        $this->authorize('sucursal', $compra);
        $compra->load([
            'sucursal.empresa',
            'typepayment',
            'moneda',
            'proveedor',
            'cuotas',
            'cajamovimientos',
            'compraitems' => function ($query) {
                $query->with(['kardexes.almacen', 'series', 'producto' => function ($subq) {
                    $subq->with(['unit', 'imagen', 'promocions' => function ($subquery) {
                        $subquery->with(['itempromos.producto' => function ($q) {
                            $q->with(['unit', 'imagen', 'almacens']);
                        }])->availables()->disponibles();
                    }]);
                }]);
            }
        ]);

        $pricetype = null;
        if ($compra->sucursal->empresa->usarlista()) {
            $pricetypes = Pricetype::default();
            if (count($pricetypes->get()) > 0) {
                $pricetype = $pricetypes->first();
            } else {
                $pricetype = Pricetype::orderBy('id', 'asc')->first();
            }
        }

        return view('almacen::compras.show', compact('compra', 'pricetype'));
    }

    public function printA4(Compra $compra)
    {
        $this->authorize('sucursal', $compra);

        if (Module::isEnabled('Almacen')) {
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

            $compra->load([
                'sucursal.empresa',
                'typepayment',
                'moneda',
                'proveedor',
                'cuotas',
                'cajamovimientos',
                'compraitems' => function ($query) {
                    $query->with(['kardexes.almacen', 'series', 'producto' => function ($subq) {
                        $subq->with(['unit']);
                    }]);
                }
            ]);
            $pdf = PDF::setOption($options)->loadView('almacen::pdf.compras.a4', compact('compra'));
            return $pdf->stream('COMPRA-' . $compra->referencia . '.pdf');
        }
    }

    // public function printA5(Compra $compra)
    // {
    //     $this->authorize('sucursal', $compra);

    //     if (Module::isEnabled('Almacen')) {
    //         $pdf = PDF::loadView('almacen::pdf.compras.a5', compact('compra'));
    //         return $pdf->stream();
    //     }
    // }

    public function cuentaspagar()
    {
        return view('almacen::compras.cuentas-pagar');
    }
}
