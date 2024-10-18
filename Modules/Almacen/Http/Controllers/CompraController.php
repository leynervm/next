<?php

namespace Modules\Almacen\Http\Controllers;

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
        $compra->load(['compraitems' => function ($query) {
            $query->with(['producto' => function ($subQuery) {
                $subQuery->with(['unit', 'promocions' => function ($queryPrm) {
                    $queryPrm->with(['itempromos.producto' => function ($queryItemprm) {
                        $queryItemprm->with('unit')->addSelect(['image' => function ($q) {
                            $q->select('url')->from('images')
                                ->whereColumn('images.imageable_id', 'productos.id')
                                ->where('images.imageable_type', Producto::class)
                                ->orderBy('default', 'desc')->limit(1);
                        }]);
                    }])->availables()->disponibles();
                }])->addSelect(['image' => function ($q) {
                    $q->select('url')->from('images')
                        ->whereColumn('images.imageable_id', 'productos.id')
                        ->where('images.imageable_type', Producto::class)
                        ->orderBy('default', 'desc')->limit(1);
                }]);
            }]);
        }]);
        return view('almacen::compras.show', compact('compra'));
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
