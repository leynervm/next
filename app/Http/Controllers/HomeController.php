<?php

namespace App\Http\Controllers;

use App\Helpers\GetClient;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Modules\Almacen\Entities\Producto;

class HomeController extends Controller
{

    public function index()
    {
        $empresa = Empresa::first();
        return view('dashboard', compact('empresa'));
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

    public function tipocambio()
    {
        $http = new GetClient();
        $response = $http->getTipoCambio();
        return response()->json($response); 
    }


}
