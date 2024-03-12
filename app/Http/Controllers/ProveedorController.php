<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Proveedortype;
use App\Models\Ubigeo;

class ProveedorController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.proveedores')->only('index');
        $this->middleware('can:admin.proveedores.create')->only('create');
        $this->middleware('can:admin.proveedores.edit')->only('edit');
        $this->middleware('can:admin.proveedores.historial')->only('history');
        $this->middleware('can:admin.proveedores.pedidos')->only('pedidos');
        $this->middleware('can:admin.proveedores.tipos')->only('proveedortypes');
    }

    public function index()
    {
        return view('admin.proveedores.index');
    }

    public function create()
    {
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        $proveedortypes = Proveedortype::orderBy('name', 'asc')->get();
        return view('admin.proveedores.create', compact('ubigeos', 'proveedortypes'));
    }

    public function edit(Proveedor $proveedor)
    {
        return view('admin.proveedores.show', compact('proveedor'));
    }

    public function proveedortypes()
    {
        return view('admin.proveedortypes.index');
    }

    public function history(Proveedor $proveedor)
    {
        $compras = $proveedor->compras()->with(['moneda', 'cajamovimientos', 'cuotas', 'sucursal' => function ($query) {
            $query->withTrashed();
        }])->get();

        $sumatorias = $proveedor->compras()->with(['moneda', 'sucursal' => function ($query) {
            $query->withTrashed();
        }])->selectRaw('moneda_id, SUM(total) as total')->groupBy('moneda_id')
            ->orderBy('total', 'desc')->get();

        return view('admin.proveedores.history', compact('proveedor', 'compras', 'sumatorias'));
    }

    public function pedidos()
    {
        return view('admin.proveedores.pedidos');
    }
}
