<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Moneda;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    public function categorias()
    {
        return view('admin.categories.index');
    }

    public function subcategorias()
    {
        return view('admin.subcategories.index');
    }

    public function units()
    {
        return view('admin.units.index');
    }

    public function especificaciones()
    {
        return view('admin.especificaciones.index');
    }

    public function ofertas()
    {
        $empresa = Empresa::DefaultEmpresa()->first();
        $moneda = Moneda::DefaultMoneda()->first();
        return view('almacen::ofertas', compact('empresa', 'moneda'));
    }

}
