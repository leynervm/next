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
}
