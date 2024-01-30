<?php

namespace Modules\Ventas\Http\Controllers;

use App\Models\Concept;
use App\Models\Empresa;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Opencaja;
use App\Models\Pricetype;
use App\Models\Seriecomprobante;
use App\Models\Typecomprobante;
use App\Models\Typepayment;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Ventas\Entities\Venta;
use Nwidart\Modules\Facades\Module;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // return view('ventas::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

        $sucursal = auth()->user()->sucursal;
        $seriecomprobante = auth()->user()->sucursal->seriecomprobantes()->withWhereHas('typecomprobante', function ($query) {
            if (!Module::isEnabled('Facturacion')) {
                $query->default();
            }
            $query->whereNotIn('code', ['07', '09', '13'])->orderBy('code', 'asc');
        })->orderByPivot('default', 'desc')->first();

        // if ($seriecomprobante->wherePivot('default', 1)->exists()) {
        //     $first = $seriecomprobante->first();
        // }
        // else {
        //     $first = $seriecomprobante->first();
        // }

        $methodpayments = Methodpayment::default();
        if (count($methodpayments->get()) > 0) {
            $methodpayment = $methodpayments->first();
        } else {
            $methodpayment = Methodpayment::first();
        }

        $typepayment = Typepayment::defaultTypepayment()->first();
        $moneda = Moneda::default()->first();
        $concept = Concept::defaultConceptVentas()->first();
        // $opencaja =  Opencaja::CajasAbiertas()->CajasUser()->cajasSucursal()->first();

        $pricetypes = Pricetype::default();
        if (count($pricetypes->get()) > 0) {
            $pricetype = $pricetypes->first();
        } else {
            $pricetype = Pricetype::orderBy('id', 'asc')->first();
        }

        $opencaja = Opencaja::CajasAbiertas()->CajasUser()
            ->WhereHas('caja', function ($query) {
                $query->where('sucursal_id', auth()->user()->sucursal_id);
            })->first();

        return view('ventas::ventas.create', compact('sucursal', 'methodpayment', 'seriecomprobante', 'typepayment', 'moneda', 'concept', 'opencaja', 'pricetype'));
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
        $concept = Concept::DefaultPaycuota()->first();
        $methodpayments = Methodpayment::default();
        if (count($methodpayments->get()) > 0) {
            $methodpayment = $methodpayments->first();
        } else {
            $methodpayment = Methodpayment::first();
        }
        $opencaja = Opencaja::CajasAbiertas()->CajasUser()
            ->WhereHas('caja', function ($query) {
                $query->where('sucursal_id', auth()->user()->sucursal_id);
            })->first();
        $sucursal = auth()->user()->sucursal;

        // $venta = Venta::with([
        //     'comprobante' => function ($query) {
        //         $query->withTrashed();
        //     },
        //     'tvitems' => function ($query) {
        //         $query->withTrashed();
        //     }
        // ])->withTrashed()->find($venta);
        return view('ventas::ventas.show', compact('venta', 'concept', 'methodpayment', 'opencaja', 'sucursal'));
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
