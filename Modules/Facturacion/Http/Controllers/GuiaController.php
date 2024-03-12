<?php

namespace Modules\Facturacion\Http\Controllers;

use App\Models\Guia;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GuiaController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.facturacion.guias')->only('index');
        $this->middleware('can:admin.facturacion.guias.create')->only('create');
        $this->middleware('can:admin.facturacion.guias.edit')->only('show');

        $this->middleware('can:admin.facturacion.guias.motivos')->only('motivos');
    }

    public function index()
    {
        return view('facturacion::guias.index');
    }

    public function create()
    {
        $sucursal = auth()->user()->sucursal;
        return view('facturacion::guias.create', compact('sucursal'));
    }

    public function show(Guia $guia)
    {
        $guia = Guia::with(['tvitems' => function ($query) {
            $query->withTrashed()->orderBy('date', 'asc');
        }])->find($guia->id);
        return view('facturacion::guias.show', compact('guia'));
    }

    public function edit(Guia $guia)
    {
        return view('facturacion::guias.edit', compact('guia'));
    }

    public function motivos()
    {
        return view('facturacion::guias.motivos');
    }
}
