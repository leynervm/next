<?php

namespace App\Http\Controllers;

class CajaController extends Controller
{
    public function index()
    {
        return view('admin.cajas');
    }

    public function administrar()
    {
        return view('admin.cajas.index');
    }

    public function aperturas()
    {
        return view('admin.aperturas.index');
    }

    public function conceptos()
    {
        return view('admin.concepts.index');
    }

    public function movimientos()
    {
        return view('admin.movimientos.index');
    }

    public function resumen()
    {
        return view('admin.cajas.index');
    }

    public function cuentas()
    {
        return view('admin.cuentas.index');
    }

    public function methodpayments()
    {
        return view('admin.methodpayments.index');
    }
    
    
}
