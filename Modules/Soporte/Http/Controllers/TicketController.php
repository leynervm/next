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
        return view('soporte::tickets.index');
    }


    public function selectarea()
    {
        $areas = Areawork::tickets()->orderBy('id', 'asc')->get();
        return view('soporte::tickets.select-area', compact('areas'));
    }


    public function create(Areawork $areawork)
    {
        if (!$areawork->addTickets()) {
            abort(404, 'Areaa  no disponible para agregar Tickets');
        }

        $areawork->load(['entornos', 'atencions']);

        return view('soporte::tickets.create', compact('areawork'));
    }



    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('soporte::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('soporte::edit');
    }
}
