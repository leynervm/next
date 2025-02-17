<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductoKardexExport implements FromView, WithTitle
{

    use Exportable;

    private $fileName = '';
    private $filters;
    public $empresa;

    public function __construct($filters)
    {
        $this->empresa = view()->shared('empresa');
        $this->filters = $filters;
        $this->fileName = 'REPORTE_KARDEX_PRODUCTOS_' . now('America/Lima')->format('dmYHis') . '.xlsx';
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        $detallado = (bool) $this->filters['viewreporte'];

        $productos = Producto::query()->select('id', 'name', 'modelo', 'sku', 'partnumber', 'slug', 'created_at', 'marca_id', 'category_id', 'subcategory_id', 'unit_id')
            ->with(['category', 'marca', 'subcategory', 'unit', 'imagen', 'kardexes' => function ($query) {
                $query->with(['almacen']);
            }, 'compraitems' => function ($query) {
                $query->with(['almacencompras' => function ($q) {
                    $q->with(['almacen', 'series']);
                }, 'compra.proveedor']);
            }, 'tvitems' => function ($query) {
                $query->with(['promocion', 'almacen', 'itemseries']);
            }, 'series' => function ($query) {
                $query->with(['almacen', 'itemserie.tvitem', 'almacencompra.compraitem.compra']);
            }])->where('id', $this->filters['producto_id'])->get();

        return view('admin.reports.excel.productos.report-kardex-productos', compact('productos', 'detallado'));
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'KARDEX DE PRODUCTOS';
    }
}
