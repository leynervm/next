<?php

namespace Modules\Facturacion\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Facturacion\Entities\Comprobante;

class FacturacionController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.facturacion')->only('index');
        $this->middleware('can:admin.facturacion.edit')->only('show');
    }

    public function index()
    {
        return view('facturacion::index');
    }

    public function show(Comprobante $comprobante)
    {
        return view('facturacion::comprobantes.show', compact('comprobante'));
    }

    public function edit($id)
    {
        return view('facturacion::edit');
    }
}
