<?php

namespace App\Http\Controllers;

use App\Helpers\GetClient;
use App\Models\Cajamovimiento;
use App\Models\Employer;
use App\Models\Empresa;
use Exception;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct()
    {
        // $this->middleware('can:admin.administracion.employers')->only('employers');
        // $this->middleware('can:admin.administracion.employers.payments')->only('payments');
        $this->middleware('can:admin.administracion.typecomprobantes')->only('typecomprobantes');
        $this->middleware('can:admin.almacen.marcas')->only('marcas');
        $this->middleware('can:admin.promociones')->only('promociones');
    }

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

    public function typecomprobantes()
    {
        return view('admin.typecomprobantes.index');
    }

    public function marcas()
    {
        return view('admin.marcas.index');
    }

    // public function employers()
    // {
    //     return view('admin.employers.index');
    // }

    // public function payments(Employer $employer)
    // {
    //     $this->authorize('sucursal', $employer);
    //     return view('admin.employers.payments', compact('employer'));
    // }

    public function promociones()
    {
        return view('admin.promociones.index');
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
