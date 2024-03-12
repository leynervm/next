<?php

namespace Modules\Almacen\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Almacen\Entities\Compra;

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
        return view('almacen::compras.show', compact('compra'));
    }

    public function cuentaspagar()
    {
        return view('almacen::compras.cuentas-pagar');
    }
}
