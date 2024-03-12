<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.roles')->only('index');
        $this->middleware('can:admin.roles.create')->only('create');
        $this->middleware('can:admin.roles.edit')->only('edit');
        $this->middleware('can:admin.roles.permisos')->only('permisos');
    }

    public function index()
    {
        return view('admin.roles.index');
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function permisos()
    {
        return view('admin.permisos.index');
    }
}
