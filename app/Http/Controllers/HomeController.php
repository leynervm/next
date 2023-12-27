<?php

namespace App\Http\Controllers;

use App\Helpers\GetClient;
use App\Models\Empresa;
use Exception;
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

    public function typecomprobantes()
    {
        return view('admin.typecomprobantes.index');
    }





    public function tipocambio()
    {
        $http = new GetClient();
        $response = $http->getTipoCambio();
        return response()->json($response);
    }

    public function consultasunat($document = null)
    {

        try {
            $http = new GetClient();
            $response = $http->getClient($document);
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
