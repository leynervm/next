<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.clientes')->only('index');
        $this->middleware('can:admin.clientes.edit')->only('edit');
        $this->middleware('can:admin.clientes.historial')->only('history');
    }

    public function index()
    {
        return view('admin.clients.index');
    }

    public function edit(Client $client)
    {
        return view('admin.clients.show', compact('client'));
    }

    public function history(Client $client)
    {
        $ventas = $client->ventas()->with(['moneda', 'sucursal'])
            ->where('sucursal_id', auth()->user()->sucursal_id)->paginate();

        $sumatorias = $client->ventas()->with(['moneda', 'sucursal'])
            ->where('sucursal_id', auth()->user()->sucursal_id)->selectRaw('moneda_id, SUM(total) as total')->groupBy('moneda_id')
            ->orderBy('total', 'desc')->get();

        return view('admin.clients.history', compact('client', 'ventas', 'sumatorias'));
    }
}
