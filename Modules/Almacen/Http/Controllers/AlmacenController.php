<?php

namespace Modules\Almacen\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AlmacenController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:admin.almacen|admin.almacen.compras|admin.almacen.productos|admin.almacen.marcas|admin.almacen.categorias|admin.almacen.caracteristicas|admin.almacen.productos.import|admin.almacen.productos.create|admin.almacen.kardex|admin.almacen.typegarantias|admin.almacen.almacenareas|admin.almacen.estantes|admin.administracion.units|admin.almacen.caracteristicas')->only('index');
        $this->middleware('can:admin.almacen.typegarantias')->only('typegarantias');
        // $this->middleware('can:admin.almacen.almacenareas')->only('almacenareas');
    }

    public function index()
    {
        return view('almacen::index');
    }

    public function typegarantias()
    {
        return view('almacen::garantias');
    }

    public function almacenareas()
    {
        return view('almacen::almacenareas');
    }
}
