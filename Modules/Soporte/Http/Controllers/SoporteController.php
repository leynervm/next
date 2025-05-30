<?php

namespace Modules\Soporte\Http\Controllers;

use App\Models\Areawork;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SoporteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('soporte::index');
    }

    public function typeequipos()
    {
        return view('soporte::typeequipos');
    }

    public function marcas()
    {
        return view('soporte::marcas');
    }

    public function status()
    {
        return view('soporte::status');
    }

    public function atenciones()
    {
        return view('soporte::atenciones');
    }

    public function conditions()
    {
        return view('soporte::conditions');
    }

    public function typeatencions()
    {
        return view('soporte::typeatencions');
    }

    public function entornos()
    {
        return view('soporte::entornos');
    }

    public function priorities()
    {
        return view('soporte::priorities');
    }
}
