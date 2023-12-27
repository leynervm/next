<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        return view('admin.empresas.index');
    }

    public function create()
    {
        return view('admin.empresas.create');
    }

    public function show()
    {
        $empresa = Empresa::first();
        return view('admin.empresas.show', compact('empresa'));
    }

}
