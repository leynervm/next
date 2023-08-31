<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Almacen\Entities\Producto;

class HomeController extends Controller
{

    public function index()
    {
        return view('dashboard');
    }

    public function administracion()
    {
        return view('admin.administracion');
    }

    public function areas()
    {
        return view('admin.areas.index');
    }

    public function clientes()
    {
        return view('admin.clients.index');
    }

    public function pricetypes()
    {
        return view('admin.pricetypes.index');
    }

    public function channelsales()
    {
        return view('admin.channelsales.index');
    }

}
