<?php

namespace Modules\Almacen\Http\Controllers;

use App\Models\Serie;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class KardexController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.almacen.kardex')->only('index');
        $this->middleware('can:admin.almacen.kardex.series')->only('series');
    }

    public function index()
    {
        return view('almacen::kardex.index');
    }

    public function series()
    {
        return view('almacen::kardex.serieskardex');
    }
}
