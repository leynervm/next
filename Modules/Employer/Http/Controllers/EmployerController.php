<?php

namespace Modules\Employer\Http\Controllers;

use App\Models\Employer;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Nwidart\Modules\Routing\Controller;

class EmployerController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.administracion.employers')->only('index');
        $this->middleware('can:admin.administracion.employers.payments')->only('payments');
        $this->middleware('can:admin.administracion.turnos')->only('turnos');
    }

    public function index()
    {
        return view('employer::index');
    }

    public function payments(Employer $employer)
    {
        $this->authorize('sucursal', $employer);
        return view('employer::payments', compact('employer'));
        // return view('admin.employers.payments', compact('employer'));
    }

    public function turnos()
    {
        return view('employer::turnos');
    }
}
