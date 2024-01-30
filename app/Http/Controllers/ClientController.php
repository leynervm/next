<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function index()
    {
        return view('admin.clients.index');
    }

    public function show(Client $client)
    {
        return view('admin.clients.show', compact('client'));
    }

    public function history(Client $client)
    {
        $ventas = $client->ventas()->with(['moneda', 'sucursal' => function ($query) {
            $query->withTrashed();
        }])->get();

        $sumatorias = $client->ventas()->with(['moneda', 'sucursal' => function ($query) {
            $query->withTrashed();
        }])->selectRaw('moneda_id, SUM(total) as total')->groupBy('moneda_id')
            ->orderBy('total', 'desc')->get();

        return view('admin.clients.history', compact('client', 'ventas', 'sumatorias'));
    }
}
