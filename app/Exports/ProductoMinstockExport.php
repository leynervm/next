<?php

namespace App\Exports;

use App\Models\Pricetype;
use App\Models\Producto;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductoMinstockExport implements FromView, WithTitle
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

        if ($this->empresa->usarLista()) {
            $pricetypes = Pricetype::activos();

            if (!empty($filters['pricetype_id'])) {
                $pricetypes->where('id', $this->filters['pricetype_id']);
            }
            $pricetypes = $pricetypes->orderBy('id', 'asc')->orderBy('default', 'asc')->get();
        }

        $productos = Producto::query()->select(
            'productos.id',
            'productos.name',
            'productos.slug',
            'unit_id',
            'marca_id',
            'category_id',
            'subcategory_id',
            'visivility',
            'publicado',
            'novedad',
            'sku',
            'modelo',
            'pricebuy',
            'pricesale',
            'precio_1',
            'precio_2',
            'precio_3',
            'precio_4',
            'precio_5',
            'minstock',
            'partnumber',
            'marcas.name as name_marca',
            'categories.name as name_category',
            'subcategories.name as name_subcategory',
        )->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->leftJoin('subcategories', 'productos.subcategory_id', '=', 'subcategories.id')
            ->leftJoin('categories', 'productos.category_id', '=', 'categories.id')
            ->with(['unit', 'imagen', 'almacens', 'compraitems.compra.proveedor'])
            ->withCount(['almacens as stock' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)')); // Suma de la cantidad en la tabla pivote
            }])->whereHas('almacens', function ($query) {
                $query->select(DB::raw('SUM(almacen_producto.cantidad) as total_stock'))
                    ->groupBy('almacen_producto.producto_id')
                    ->havingRaw('SUM(almacen_producto.cantidad) <= productos.minstock');
            })->visibles()->orderBy('novedad', 'DESC')->orderBy('subcategories.orden', 'ASC')
            ->orderBy('categories.orden', 'ASC')->get();

        return view('admin.reports.excel.productos.report-min-stock-productos', compact('productos', 'detallado'));
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'PRODUCTOS PRÓXIMOS AGOTARSE';
    }
}
