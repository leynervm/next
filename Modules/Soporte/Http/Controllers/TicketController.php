<?php

namespace Modules\Soporte\Http\Controllers;

use App\Models\Areawork;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('modules.soporte.tickets.index');
    }


    public function selectarea()
    {
        $areas = Areawork::tickets()->orderBy('id', 'asc')->get();
        return view('modules.soporte.tickets.select-area', compact('areas'));
    }


    public function create(Areawork $areawork)
    {
        if (!$areawork->addTickets()) {
            abort(404, 'Areaa  no disponible para agregar Tickets');
        }

        $areawork->load(['entornos', 'atencions']);

        return view('modules.soporte.tickets.create', compact('areawork'));
    }
}
