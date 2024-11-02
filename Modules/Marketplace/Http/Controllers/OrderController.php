<?php

namespace Modules\Marketplace\Http\Controllers;

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
        $order->load(['tvitems.producto.unit', 'transaccion', 'trackings' => function ($query) {
            $query->with('trackingstate')->orderBy('date', 'asc');
        }]);
        return view('marketplace::admin.orders.show', compact('order'));
    }
}
