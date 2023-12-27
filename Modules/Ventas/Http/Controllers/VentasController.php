<?php

namespace Modules\Ventas\Http\Controllers;

use Illuminate\Routing\Controller;

class VentasController extends Controller
{

    public function index()
    {
        return view('ventas::index');
    }

    public function methodpayments()
    {
        return view('ventas::methodpayments');
    }

    public function cobranzas()
    {
        return view('ventas::ventas.cobranzas');
    }

    
    
}
