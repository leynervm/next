<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;

class SucursalController extends Controller
{
    public function index()
    {
        $empresa = Empresa::DefaultEmpresa()->first();
        return view('admin.sucursales.index', compact('empresa'));
    }

    public function edit(Sucursal $sucursal)
    {
       
        $sucursal = Sucursal::with(['seriecomprobantes' => function ($query) {
            $query->whereHas('typecomprobante', function ($query) {
                // $query->where('code', '<>', '09');
                if (Module::isDisabled('Facturacion')) {
                    $query->default();
                } 
            });
        }])->find($sucursal->id);

        return view('admin.sucursales.show', compact('sucursal'));
    }
}
