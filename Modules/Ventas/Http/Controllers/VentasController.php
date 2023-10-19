<?php

namespace Modules\Ventas\Http\Controllers;

use App\Models\Concept;
use App\Models\Empresa;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Opencaja;
use App\Models\Pricetype;
use App\Models\Typepayment;
use App\Models\Typecomprobante;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Ventas\Entities\Venta;

class VentasController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('ventas::index');
    }

    public function methodpayments()
    {
        return view('ventas::methodpayments');
    }

    public function cobranzas()
    {
        return view('ventas::ventas.cobranzas');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $empresa = Empresa::DefaultEmpresa()->first();
        $methodpayment = Methodpayment::defaultMethodPayment()->first();
        $typecomprobante = Typecomprobante::defaultTypecomprobante()->first();
        $typepayment = Typepayment::defaultTypepayment()->first();
        $moneda = Moneda::defaultMoneda()->first();
        $concept = Concept::defaultConceptVentas()->first();
        $opencaja =  Opencaja::CajasAbiertas()->CajasUser()->first();
        $pricetype = Pricetype::defaultPricetype()->first();
        return view('ventas::ventas.create', compact('empresa', 'methodpayment', 'typecomprobante', 'typepayment', 'moneda', 'concept', 'opencaja', 'pricetype'));
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
    public function show(Venta $venta)
    {

        $concept = Concept::DefaultConceptPaycuota()->first();
        $methodpayment = Methodpayment::defaultMethodpayment()->first();
        $opencaja =  Opencaja::CajasAbiertas()->CajasUser()->first();
        return view('ventas::ventas.show', compact('venta', 'concept', 'methodpayment', 'opencaja'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('ventas::edit');
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
