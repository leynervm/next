<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Modules\Ventas\Entities\Venta;

class CotizacionController extends Controller
{

    public function index()
    {
        return view('modules.almacen.cotizacions.index');
    }

    public function create()
    {
        return view('modules.almacen.cotizacions.create');
    }

    public function edit(Cotizacion $cotizacion)
    {
        $cotizacion->load([
            'user',
            'client',
            'lugar.ubigeo',
            'sucursal',
            'moneda',
            'typepayment',
            'cotizables' => function ($query) {
                $query->with(['cotizable' => function ($subq) {
                    $subq->when(Venta::class, fn($q) => $q->with(['client', 'seriecomprobante.typecomprobante', 'typepayment']));
                    // ->when(Ticket::class, fn($q) => $q->with(['someTicketRelation']));
                }]);
            },
            'cotizaciongarantias' => function ($query) {
                $query->with(['typegarantia']);
            },
            'tvitems' => function ($query) {
                $query->with(['producto' => function ($subq) {
                    $subq->with(['unit', 'imagen']);
                }]);
            },
            'otheritems' => function ($query) {
                $query->with(['unit', 'user']);
            }
        ]);

        // dd($cotizacion);
        return view('modules.almacen.cotizacions.show', compact('cotizacion'));
    }

    public function print(Cotizacion $cotizacion)
    {
        $cotizacion->load([
            'user',
            'client',
            'lugar.ubigeo',
            'sucursal.ubigeo',
            'moneda',
            'typepayment',
            'cotizaciongarantias' => function ($query) {
                $query->with(['typegarantia']);
            },
            'tvitems' => function ($query) {
                $query->with(['producto' => function ($subq) {
                    $subq->with(['unit', 'marca', 'imagen', 'especificacions.caracteristica']);
                }]);
            },
            'otheritems' => function ($query) {
                $query->with(['unit', 'user']);
            }
        ]);

        $empresa = view()->shared('empresa');
        $sucursal = $cotizacion->sucursal;
        $titulo = "COTIZACIÓN : " . $cotizacion->seriecompleta;
        $pdf = SnappyPdf::loadView('print.cotizaciones.a4', compact('cotizacion', 'empresa', 'sucursal', 'titulo'))
            ->setOptions([
                'header-html' => view('admin.reports.snappyPDF.header', compact('titulo')),
                'margin-top' => '29.5mm',
                'margin-bottom' => '10mm',
                'margin-left' => '0mm',
                'margin-right' => '0mm',
                'header-spacing' => 5,
                'footer-html' => view('admin.reports.snappyPDF.footer'),
                'encoding' => 'UTF-8',
            ]);

        return $pdf->inline("$titulo.pdf");
    }
}
