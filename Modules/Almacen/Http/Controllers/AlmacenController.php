<?php

namespace Modules\Almacen\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('almacen::index');
    }

    public function typegarantias()
    {
        return view('almacen::garantias');
    }

    public function almacenareas()
    {
        return view('almacen::almacenareas');
    }

    public function categorias()
    {
        return view('almacen::categorias');
    }

    public function subcategorias()
    {
        return view('almacen::subcategorias');
    }

    public function units()
    {
        return view('almacen::units');
    }

    public function especificaciones()
    {
        return view('almacen::especificaciones');
    }

    public function ofertas()
    {
        return view('almacen::ofertas');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('almacen::create');
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
        return view('almacen::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('almacen::edit');
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
