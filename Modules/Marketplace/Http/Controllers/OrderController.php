<?php

namespace Modules\Marketplace\Http\Controllers;

use App\Models\Producto;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Marketplace\Entities\Order;

use Nwidart\Modules\Routing\Controller;

class OrderController extends Controller
{

    public function index()
    {
        return view('marketplace::admin.orders.index');
    }

    public function show(Order $order)
    {
        $order->load([
            'shipmenttype',
            'user',
            'entrega.sucursal.ubigeo',
            'client',
            'moneda',
            'direccion.ubigeo',
            'transaccion',
            'tvitems' => function ($query) {
                $query->with(['kardexes.almacen', 'itemseries' => function ($subq) {
                    $subq->with(['serie.almacen']);
                }, 'producto' => function ($subq) {
                    $subq->withTrashed()->with(['unit', 'imagen', 'almacens', 'seriesdisponibles']);
                }, 'carshoopitems' => function ($subq) {
                    $subq->with(['kardexes.almacen', 'itempromo', 'producto' => function ($q) {
                        $q->with(['unit', 'imagen', 'almacens', 'seriesdisponibles']);
                    }, 'itemseries' => function ($subq) {
                        $subq->with(['serie.almacen']);
                    }]);
                }]);
            },
            'trackings' => function ($query) {
                $query->with('trackingstate')->orderBy('date', 'asc');
            },
            'comprobante' => function ($query) {
                $query->with(['client', 'facturableitems', 'seriecomprobante.typecomprobante']);
            }
        ]);

        return view('marketplace::admin.orders.show', compact('order'));
    }
}
