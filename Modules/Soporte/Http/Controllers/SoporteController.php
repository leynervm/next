<?php

namespace Modules\Soporte\Http\Controllers;

use App\Models\Area;
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


    public function administrar()
    {
        return view('soporte::administrar');
    }

    public function equipos()
    {
        return view('soporte::equipos');
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

    public function newOrder()
    {
        $areas = Area::where('delete', 0)->where('visible', 1)->orderBy('name', 'asc')->get();
        return view('soporte::orders', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('soporte::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
