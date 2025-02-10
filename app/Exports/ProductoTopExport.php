<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductoTopExport implements FromView, WithTitle
{

    use Exportable;

    private $fileName = '';
    private $filters;
    public $empresa;

    public function __construct($filters)
    {
        $this->empresa = view()->shared('empresa');
        $this->filters = $filters;
        $this->fileName = 'REPORTE_TOP_10_PRODUCTOS_' . now('America/Lima')->format('dmYHis') . '.xlsx';
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $detallado = (bool) $this->filters['viewreporte'];

        $productos = Producto::query()->select('id', 'name', 'modelo', 'sku', 'partnumber', 'slug', 'marca_id', 'category_id', 'subcategory_id', 'unit_id')
            ->with(['category', 'marca', 'subcategory', 'unit'])
            ->withWhereHas('tvitems', function ($query) {
                $query->with(['almacen'])->when($this->filters['almacen_id'], function ($q, $almacen_id) {
                    $q->where('almacen_id', $almacen_id);
                })->when($this->filters['user_id'], function ($q, $user_id) {
                    $q->where('user_id', $user_id);
                });
            })
            ->withSum(['tvitems as vendidos' => function ($query) {
                $query->with(['almacen'])->when($this->filters['almacen_id'], function ($q, $almacen_id) {
                    $q->where('almacen_id', $almacen_id);
                })->when($this->filters['user_id'], function ($q, $user_id) {
                    $q->where('user_id', $user_id);
                });
            }], 'cantidad')
            ->orderByDesc('vendidos')->take(10)->get();

        return view('admin.reports.excel.productos.report-top-productos', compact('productos', 'detallado'));
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'LISTA DE PRODUCTOS';
    }
}
