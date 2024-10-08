<?php

namespace App\Http\Controllers;

use App\Enums\StatusPayWebEnum;
use App\Helpers\GetClient;
use App\Models\Empresa;
use App\Models\Moneda;
use App\Models\Producto;
use App\Models\Slider;
use App\Models\Ubigeo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use jossmp\sunat\ruc;
use jossmp\sunat\tipo_cambio;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Modules\Marketplace\Entities\Order;

class HomeController extends Controller
{

    public function __construct()
    {
        // $this->middleware('can:admin.administracion.employers')->only('employers');
        // $this->middleware('can:admin.administracion.employers.payments')->only('payments');
        $this->middleware('can:admin.administracion.typecomprobantes')->only('typecomprobantes');
        $this->middleware('can:admin.almacen.marcas')->only('marcas');
        $this->middleware('can:admin.promociones')->only('promociones');
        $this->middleware('can:admin.administracion.areaswork')->only('areaswork');
        $this->middleware('can:admin.administracion.pricetypes,admin.administracion.rangos')->only('pricetypes');
        $this->middleware(['permission:admin.administracion.empresa.create|admin.administracion.empresa.edit|admin.administracion.sucursales|admin.administracion.employers|admin.users|admin.administracion.pricetypes|admin.administracion.rangos|admin.administracion.typecomprobantes|admin.administracion.units|admin.administracion.areas'])->only('administracion');
        $this->middleware('can:admin.administracion.typepayments')->only('typepayments');
    }

    public function welcome()
    {

        $empresa = mi_empresa();
        $moneda = Moneda::default()->first();
        $pricetype = getPricetypeAuth($empresa);
        $sliders = Slider::activos()->disponibles()->orderBy('orden', 'asc')->get();

        $status_pendiente = StatusPayWebEnum::PENDIENTE->value;

        if (auth()->user()) {
            $user_id = auth()->user()->id;
            $query = Order::toBase()->selectRaw("COUNT(*) FILTER (WHERE status = '$status_pendiente' AND user_id = $user_id) as pendientes")->first();
            if ($query->pendientes) {
                $mensaje = "Usted tiene $query->pendientes ordenes pendientes. <a class='font-semibold' href='" . route('orders') . "?estado-pago=$status_pendiente' >Ir a pagar</a>";
                session()->flash('flash.banner', $mensaje);
            }
        }

        return view('welcome', compact('sliders', 'empresa', 'moneda', 'pricetype'));
    }

    public function dashboard()
    {
        $empresa = Empresa::first();
        $chart_options = [
            'chart_title' => 'Users by months',
            'report_type' => 'group_by_string',
            'model' => 'App\Models\User',
            'group_by_field' => 'sucursal_id',
            'group_by_period' => 'day',
            'chart_type' => 'bar',
        ];
        $chart = new LaravelChart($chart_options);

        // $chart_options = [
        //     'chart_title' => 'Transactions by local',
        //     'chart_type' => 'bar',
        //     'report_type' => 'group_by_relationship',
        //     'model' => 'App\Models\Cajamovimiento',
        //     'relationship_name' => 'sucursal', // represents function user() on Transaction model
        //     'group_by_field' => 'name', // users.name
        //     'aggregate_function' => 'sum',
        //     'aggregate_field' => 'amount',
        //     'filter_field' => 'date',
        // ];
        $chart_options1 = [
            'name' => 'Payments',
            'chart_title' => 'Transactions by local',
            'report_type' => 'group_by_string',
            'model' => 'App\Models\Cajamovimiento',
            'group_by_field' => 'openbox_id',
            // 'group_by_period' => 'month',
            'aggregate_function' => 'sum',
            'aggregate_field' => 'amount',
            'chart_type' => 'bar',
            'where_raw' => "sucursal_id = '1' and deleted_at is null",
            'filter_field' => 'typemovement',
            // 'labels' => [
            //     'INGRESO' => 'INGRESO',
            //     'EGRESO' => 'EGRESO'
            // ]
        ];
        $chart1 = new LaravelChart($chart_options1);
        $productos = Producto::with(['almacens'])
            ->whereHas('almacens', function ($query) {
                $query->select(DB::raw('SUM(almacen_producto.cantidad) as total_stock'))
                    ->groupBy('almacen_producto.producto_id')
                    ->havingRaw('SUM(almacen_producto.cantidad) <= productos.minstock');
            })->paginate();

        return view('dashboard', compact('empresa', 'chart', 'chart1', 'productos'));
    }

    public function administracion()
    {
        return view('admin.administracion');
    }

    public function areaswork()
    {
        return view('admin.areaswork.index');
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

    public function typepayments()
    {
        return view('admin.typepayments.index');
    }

    public function marcas()
    {
        return view('admin.marcas.index');
    }

    public function promociones()
    {
        return view('admin.promociones.index');
    }

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
