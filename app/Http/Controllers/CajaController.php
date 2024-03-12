<?php

namespace App\Http\Controllers;

use App\Models\Monthbox;
use App\Models\Openbox;

class CajaController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.cajas')->only('index');
        $this->middleware('can:admin.cajas.methodpayments')->only('methodpayments');
        $this->middleware('can:admin.cajas.conceptos')->only('conceptos');
        $this->middleware('can:admin.cajas.aperturas')->only('aperturas');
        $this->middleware('can:admin.cajas.mensuales')->only('mensuales');
    }


    public function index()
    {
        $openbox = Openbox::mybox(auth()->user()->sucursal_id)->first();
        $monthbox = Monthbox::usando(auth()->user()->sucursal_id)->first();

        return view('admin.cajas', compact('openbox', 'monthbox'));
    }

    public function aperturas()
    {
        $openbox = Openbox::mybox(auth()->user()->sucursal_id)->first();
        $monthbox = Monthbox::usando(auth()->user()->sucursal_id)->first();
        return view('admin.aperturas.index', compact('openbox', 'monthbox'));
    }

    public function conceptos()
    {
        return view('admin.concepts.index');
    }

    public function movimientos()
    {
        return view('admin.movimientos.index');
    }

    public function methodpayments()
    {
        return view('admin.methodpayments.index');
    }

    public function mensuales()
    {
        return view('admin.cajas.mensuales');
    }
}
