<?php

namespace Modules\Almacen\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AlmacenController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.almacen')->only('index');
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
