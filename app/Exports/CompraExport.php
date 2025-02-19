<?php

namespace App\Exports;

use Modules\Almacen\Entities\Compra;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class CompraExport implements FromView, WithTitle
{
    use Exportable;

    private $fileName = '';
    private $filters;
    public $empresa;

    public function __construct($filters)
    {
        $this->empresa = view()->shared('empresa');
        $this->filters = $filters;
        $this->fileName = 'REPORTE_DE_COMPRAS_' . now('America/Lima')->format('dmYHis') . '.xlsx';
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $detallado = (bool) $this->filters['viewreporte'];

        $compras = Compra::query()->select('compras.*')
            ->with(['sucursal', 'proveedor', 'typepayment', 'moneda', 'user'])
            ->when($this->filters['viewreporte'] == 1, function ($query) {
                $query->with(['cuotas' => function ($subquery) {
                    $subquery->with(['cajamovimientos' => function ($q) {
                        // $q->with(['moneda', 'methodpayment']);
                    }]);
                }, 'cajamovimientos' => function ($subquery) {
                    $subquery->with(['moneda', 'methodpayment']);
                }]);
            })->queryFilter($this->filters)->get();

        $sumatorias = $compras->groupBy('moneda_id')->map(function ($compras) {
            $amounts = [
                'moneda_id' => $compras->first()->moneda_id,
                'moneda' => $compras->first()->moneda,
                'total' => $compras->sum('total'),
            ];
            return (object) $amounts;
        })->sortBy('moneda_id')->values();

        return view('admin.reports.excel.compras.report-compras', compact('compras', 'sumatorias', 'detallado'));
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'LISTA DE COMPRAS';
    }
}
