<?php

namespace App\Exports;

use App\Enums\FilterReportsEnum;
use App\Models\Pricetype;
use App\Models\Producto;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductoExport implements FromView, WithTitle
{

    use Exportable;

    private $fileName = '';
    private $filters;
    public $empresa;
    // public $endcell = '';
    // public $sumatorias = [];
    // private $writerType = Excel::XLSX;

    public function __construct($filters)
    {
        $this->empresa = view()->shared('empresa');
        $this->filters = $filters;
        $this->fileName = 'REPORTE_DE_PRODUCTOS_' . now('America/Lima')->format('dmYHis') . '.xlsx';
    }

    public function view(): View
    {
        $pricetypes =  [];
        $detallado = (bool) $this->filters['viewreporte'];
        // $typereportvalue = FilterReportsEnum::getValue(Str::upper($request->input('typereporte')));
        // $titulo = 'REPORTE DE PRODUCTOS ' . FilterReportsEnum::getLabel($this->filters['typereporte']);

        $productos = Producto::query()->select(
            'productos.id',
            'productos.name',
            'modelo',
            'marca_id',
            'unit_id',
            'category_id',
            'subcategory_id',
            'publicado',
            'sku',
            'visivility',
            'comentario',
            'partnumber',
            'pricebuy',
            'pricesale',
            'precio_1',
            'precio_2',
            'precio_3',
            'precio_4',
            'precio_5',
            'marcas.name as name_marca',
            'categories.name as name_category',
            'subcategories.name as name_subcategory',
        )->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->leftJoin('subcategories', 'productos.subcategory_id', '=', 'subcategories.id')
            ->leftJoin('categories', 'productos.category_id', '=', 'categories.id')
            // ->withCount(['almacens as stock' => function ($query) use ($this->filters) {
            //     $query->when($this->filters['almacen_id'] ?? null, function ($q, $almacen_id) {
            //         $q->where('almacens.id', $almacen_id);
            //     })->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)')); // Suma de la cantidad en la tabla pivote
            // }])
            ->with(['unit', 'imagen', 'series' => function ($query) {
                $query->when($this->filters['almacen_id'] ?? null, function ($q, $almacen_id) {
                    $q->where('almacen_id', $almacen_id);
                });
            }])->when($this->filters['viewstock'] == '0', function ($query) {
                $query->with(['almacens' => function ($subq) {
                    $subq->when($this->filters['almacen_id'] ?? null, function ($q, $almacen_id) {
                        $q->where('almacens.id', $almacen_id);
                    });
                }]);
            })->when($this->filters['viewstock'] == '1', function ($query) {
                $query->whereHas('almacens', function ($q) {
                    $q->select(DB::raw('SUM(almacen_producto.cantidad) as total_stock'))
                        ->groupBy('almacen_producto.producto_id')
                        ->havingRaw('SUM(almacen_producto.cantidad) > 0');
                });
            })->when($this->filters['viewstock'] == '2', function ($query) {
                $query->whereHas('almacens', function ($q) {
                    $q->select(DB::raw('SUM(almacen_producto.cantidad) as total_stock'))
                        ->groupBy('almacen_producto.producto_id')
                        ->havingRaw('SUM(almacen_producto.cantidad) = 0');
                });
            })
            ->when($this->filters['viewreporte'] == 1, function ($query) {
                $query->with(['compraitems.compra.proveedor', 'tvitems' => function ($subq) {
                    $subq->with(['almacen', 'producto' => function ($q) {
                        $q->select('id', 'name', 'unit_id')->with('unit');
                    }]);
                }, 'especificacions' => function ($subq) {
                    $subq->with('caracteristica');
                }]);
            })->when($this->filters['typereporte'] == FilterReportsEnum::PRODUCTOS_PROMOCIONADOS->value, function ($query) {
                $query->with(['promocions' => function ($subq) {
                    $subq->with(['itempromos.producto.unit', 'producto'])->disponibles()->availables();
                }]);
            })->when($this->filters['typereporte'] == FilterReportsEnum::MIN_STOCK->value, function ($query) {
                $query->whereHas('almacens', function ($query) {
                    $query->select(DB::raw('SUM(almacen_producto.cantidad) as total_stock'))
                        ->groupBy('almacen_producto.producto_id')
                        ->havingRaw('SUM(almacen_producto.cantidad) <= productos.minstock');
                });
            });

        if (!empty($this->filters['producto_id'])) {
            $productos->when($this->filters['producto_id'], function ($query, $producto_id) {
                $query->where('productos.id', $producto_id);
            });
        } else {
            $productos->queryFilter($this->filters)->when($this->filters['category_id'], function ($query, $category_id) {
                $query->where('category_id', $category_id);
            })->when($this->filters['subcategory_id'], function ($query, $subcategory_id) {
                $query->where('subcategory_id', $subcategory_id);
            });
        }

        $productos = $productos->orderByDesc('novedad')->orderBy('categories.orden', 'asc')
            ->orderBy('subcategories.orden', 'asc')->get();


        if ($this->empresa->usarLista()) {
            $pricetypes = Pricetype::activos();

            if (!empty($this->filters['pricetype_id'])) {
                $pricetypes->where('id', $this->filters['pricetype_id']);
            }
            $pricetypes = $pricetypes->orderBy('id', 'asc')->orderBy('default', 'asc')->get();
        }

        return view('admin.reports.excel.productos.report-productos', compact('productos', 'detallado', 'pricetypes'));
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'LISTA DE PRODUCTOS';
    }
}
