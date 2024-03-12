<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.administracion.empresa.create')->only('create');
        $this->middleware('can:admin.administracion.empresa.edit')->only('edit');
    }

    public function index()
    {
        return view('admin.empresas.index');
    }

    public function create()
    {
        return view('admin.empresas.create');
    }

    public function edit()
    {
        $empresa = Empresa::first();
        return view('admin.empresas.show', compact('empresa'));
    }

}
