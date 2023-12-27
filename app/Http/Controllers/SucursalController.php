<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function index()
    {
        $empresa = Empresa::DefaultEmpresa()->first();
        return view('admin.sucursales.index', compact('empresa'));
    }

    public function edit(Sucursal $sucursal)
    {
        return view('admin.sucursales.show', compact('sucursal'));
    }
}
