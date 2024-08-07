<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlmacenController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.administracion.units')->only('units');
        $this->middleware('can:admin.almacen.categorias')->only('categorias');
        $this->middleware('can:admin.almacen.subcategorias')->only('subcategorias');
    }


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
}
