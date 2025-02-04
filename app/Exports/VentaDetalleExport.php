<?php

namespace App\Exports;

use App\Enums\FilterReportsEnum;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Excel;
use Modules\Ventas\Entities\Venta;


class VentaDetalleExport implements FromView
{

    use Exportable;

    private $fileName = '';
    private $filters;
    public $endcell = '';
    // public $sumatorias = [];
    private $writerType = Excel::XLSX;

    public function __construct($filters)
    {
        $this->filters = $filters;
        $this->fileName = 'REPORTE_DETALLADO_DE_VENTAS_' . now('America/Lima')->format('dmYHis') . '.xlsx';
    }

    public function view(): View
    {
        $ventas = Venta::query()->select('ventas.*')
            ->with(['sucursal', 'client', 'typepayment', 'moneda', 'user', 'seriecomprobante.typecomprobante', 'tvitems' => function ($subq) {
                $subq->with(['almacen', 'producto' => function ($q) {
                    $q->select('id', 'name', 'unit_id')->with('unit');
                }]);
            }])->queryFilter($this->filters)->when($this->filters['typecomprobante_id'], function ($subq, $typecomprobante_id) {
                $subq->whereHas('seriecomprobante', function ($q) use ($typecomprobante_id) {
                    $q->where('typecomprobante_id', $typecomprobante_id);
                });
            })->when($this->filters['typereporte'] == FilterReportsEnum::VENTAS_POR_COBRAR->value, function ($subq) {
                $subq->whereColumn('paymentactual', '<', 'total');
            })->when($this->filters['typereporte'] == FilterReportsEnum::TOP_TEN_VENTAS->value, function ($subq) {
                $subq->orderByDesc('total')->take(10);
            }, function ($subq) {
                $subq->orderByDesc('date');
            })->get();

        $sumatorias = $ventas->groupBy('moneda_id')->map(function ($ventas) {
            $amounts = [
                'moneda_id' => $ventas->first()->moneda_id,
                'moneda' => $ventas->first()->moneda,
                'total' => $ventas->sum('total'),
            ];
            if ($this->filters['typereporte'] == FilterReportsEnum::VENTAS_POR_COBRAR->value) {
                $amounts['saldos'] = $ventas->sum('total') - $ventas->sum('paymentactual');
            }
            return (object) $amounts;
        })->sortBy('moneda_id')->values();

        return view('admin.reports.excel.report-detallado-ventas', compact('ventas', 'sumatorias'));
    }
}
