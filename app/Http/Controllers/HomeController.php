<?php

namespace App\Http\Controllers;

use App\Enums\StatusPayWebEnum;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Modules\Marketplace\Entities\Order;

class HomeController extends Controller
{

    public function __construct()
    {

        $this->middleware('verifycarshoop')->only(['welcome',]);
        $this->middleware('verifycompany')->only('administracion');
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

        $pricetype = getPricetypeAuth();
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

        // $empresa = view()->shared('empresa');
        // dd($empresa);

        return view('welcome', compact('sliders', 'pricetype'));
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

        $productos = Producto::query()->select(
            'productos.id',
            'productos.name',
            'productos.slug',
            'unit_id',
            'marca_id',
            'category_id',
            'subcategory_id',
            'visivility',
            'publicado',
            'novedad',
            'sku',
            'minstock',
            'partnumber',
            'marcas.name as name_marca',
            'categories.name as name_category',
            'subcategories.name as name_subcategory',
        )->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->leftJoin('subcategories', 'productos.subcategory_id', '=', 'subcategories.id')
            ->leftJoin('categories', 'productos.category_id', '=', 'categories.id')
            ->with(['unit', 'imagen', 'almacens', 'compraitems.compra.proveedor'])
            ->withCount(['almacens as stock' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)')); // Suma de la cantidad en la tabla pivote
            }])->whereHas('almacens', function ($query) {
                $query->select(DB::raw('SUM(almacen_producto.cantidad) as total_stock'))
                    ->groupBy('almacen_producto.producto_id')
                    ->havingRaw('SUM(almacen_producto.cantidad) <= productos.minstock');
            })->visibles()->orderBy('novedad', 'DESC')->orderBy('subcategories.orden', 'ASC')
            ->orderBy('categories.orden', 'ASC')->paginate();

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
}
