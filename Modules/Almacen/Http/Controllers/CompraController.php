<?php

namespace Modules\Almacen\Http\Controllers;

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
        return view('almacen::compras.show', compact('compra'));
    }

    public function printA4(Compra $compra)
    {
        $this->authorize('sucursal', $compra);

        if (Module::isEnabled('Almacen')) {
            $pdf = PDF::loadView('almacen::pdf.compras.a4', compact('compra'));
            return $pdf->stream();
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
