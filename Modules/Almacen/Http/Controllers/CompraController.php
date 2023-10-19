<?php

namespace Modules\Almacen\Http\Controllers;

use App\Models\Concept;
use App\Models\Empresa;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Opencaja;
use App\Models\Typepayment;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Almacen\Entities\Compra;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('almacen::compras.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $moneda = Moneda::defaultMoneda()->first();
        $typepayment = Typepayment::defaultTypepayment()->first();
        $concept = Concept::DefaultConceptCompra()->first();
        $methodpayment = Methodpayment::with('cuentas')->DefaultMethodpayment()->first();
        $opencaja =  Opencaja::CajasAbiertas()->CajasUser()->first();
        return view('almacen::compras.create', compact('typepayment', 'methodpayment', 'moneda', 'concept', 'opencaja'));
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
    public function show(Compra $compra)
    {
        $empresa = Empresa::DefaultEmpresa()->first();
        $opencaja =  Opencaja::CajasAbiertas()->CajasUser()->first();
        return view('almacen::compras.show', compact('compra', 'empresa', 'opencaja'));
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
