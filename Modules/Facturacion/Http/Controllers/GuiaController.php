<?php

namespace Modules\Facturacion\Http\Controllers;

use App\Models\Guia;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Nwidart\Modules\Facades\Module;
use Nwidart\Modules\Routing\Controller;

class GuiaController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.facturacion.guias')->only('index');
        $this->middleware('can:admin.facturacion.guias.create')->only(['create', 'show']);
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
        $this->authorize('sucursal', $guia);

        $guia = Guia::with(['tvitems' => function ($query) {
            $query->withTrashed()->orderBy('date', 'asc');
        }])->find($guia->id);
        return view('facturacion::guias.show', compact('guia'));
    }


    public function motivos()
    {
        return view('facturacion::guias.motivos');
    }

    public function imprimirA4(Guia $guia)
    {
        $this->authorize('sucursal', $guia);

        if (Module::isEnabled('Facturacion')) {
            $pdf = PDF::loadView('facturacion::pdf.guia', compact('guia'));
            return $pdf->stream();
        }
    }
}
