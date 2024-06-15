<?php

namespace App\Http\Controllers;

use App\Helpers\GetClient;
use App\Models\Cajamovimiento;
use App\Models\Employer;
use App\Models\Empresa;
use App\Models\Ubigeo;
use Exception;
use Illuminate\Http\Request;
use jossmp\sunat\ruc;
use jossmp\sunat\tipo_cambio;

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

    // public function tipocambio()
    // {
    //     $http = new GetClient();
    //     $response = $http->getTipoCambio();
    //     return response()->json($response);
    // }

    public function tipocambio()
    {
        $tc = new tipo_cambio();
        $result = $tc->ultimo_tc();

        if ($result->success) {
            $json = [
                'success' => true,
                'compra' => $result->result->compra,
                'venta' => $result->result->venta,
                'fecha' => $result->result->fecha,
            ];
        } else {
            $json = [
                'success' => false,
                'message' => $result->message
            ];
        }
        return response()->json($json);
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

    public function consultaruc($ruc)
    {

        try {
            $config = [
                'representantes_legales'     => true,
                'cantidad_trabajadores'     => true,
                'establecimientos'             => true,
                'deuda'                     => true,
            ];

            $sunat = new ruc($config);
            $search = $sunat->consulta($ruc);

            if ($search->success == true) {
                $ubigeo_id = null;
                if (!empty($search->result->distrito)) {
                    $ubigeo_id = Ubigeo::where('distrito', 'ilike', trim($search->result->distrito))
                        ->where('provincia', 'ilike', trim($search->result->provincia))
                        // ->where('region', 'ilike', trim($search->result->departamento))
                        ->first()->id ?? null;
                }
                return response()->json([
                    'success' => true,
                    'result' => [
                        'ruc' => $search->result->ruc,
                        'razon_social' => $search->result->razon_social,
                        'nombre_comercial' => $search->result->nombre_comercial,
                        'direccion' => $search->result->direccion,
                        'departamento' => $search->result->departamento,
                        'provincia' => $search->result->provincia,
                        'distrito' => $search->result->distrito,
                        'estado' => $search->result->estado,
                        'condicion' => $search->result->condicion,
                        'establecimientos' => $search->result->establecimientos,
                        'ubigeo_id' => $ubigeo_id,
                    ]
                ])->getData();
            }
            return response()->json($search);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
