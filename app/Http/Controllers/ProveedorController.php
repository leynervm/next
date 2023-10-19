<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Proveedortype;
use App\Models\Ubigeo;

class ProveedorController extends Controller
{
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
    
    public function show(Proveedor $proveedor)
    {
        return view('admin.proveedores.show', compact('proveedor'));
    }

    public function proveedortypes()
    {
        return view('admin.proveedortypes.index');
    }

    
}
