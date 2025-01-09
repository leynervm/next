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
        $order->load(['transaccion', 'trackings' => function ($query) {
            $query->with('trackingstate')->orderBy('date', 'asc');
        }, 'tvitems.producto' => function ($query) {
            $query->with(['unit', 'imagen']);
        }]);

        return view('marketplace::admin.orders.show', compact('order'));
    }
}
