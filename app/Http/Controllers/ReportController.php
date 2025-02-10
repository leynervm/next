<?php

namespace App\Http\Controllers;

use App\Enums\FilterReportsEnum;
use App\Enums\MovimientosEnum;
use App\Models\Cajamovimiento;
use App\Models\Employer;
use App\Models\Employerpayment;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Tvitem;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Ventas\Entities\Venta;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function movimientos(Request $request)
    {

        $filters =  [
            "typereporte" => "",
            "sucursal_id" => "",
            "concept_id" => "",
            "typemovement" => "",
            "methodpayment_id" => "",
            "user_id" => "",
            "monthbox_id" => "",
            "openbox_id" => "",
            "moneda_id" => "",
            "date" => "",
            "dateto" => "",
            "week" => "",
            "month" => "",
            "monthto" => "",
            "year" => "",
            "days" => [],
            "months" => [],
        ];

        $empresa = view()->shared('empresa');
        $typereportvalue = FilterReportsEnum::getValue(Str::upper($request->input('typereporte')));

        if ($typereportvalue === null) {
            $titulo = FilterReportsEnum::getLabel($typereportvalue);
            $cajamovimientos = [];
            $sumatorias = [];
        } else {
            $titulo = 'REPORTE DE CAJA ' . FilterReportsEnum::getLabel($typereportvalue);
            foreach (array_keys($request->input()) as $param) {
                if (in_array($param, array_keys($filters))) {
                    if ($param == "typereporte") {
                        $filters[$param] = FilterReportsEnum::getValue(Str::upper($request->input($param)));
                    } else {
                        $filters[$param] = $request->input($param);
                    }
                }
            }
            $cajamovimientos = Cajamovimiento::with(['sucursal', 'openbox.box', 'monthbox', 'concept', 'methodpayment', 'moneda', 'user'])
                ->queryFilter($filters)->orderByDesc('date')->get();

            $totales = Cajamovimiento::with('moneda')
                ->select('typemovement', 'moneda_id', DB::raw('SUM(amount) as total'))
                ->queryFilter($filters)->groupBy('typemovement', 'moneda_id')
                ->orderBy('moneda_id', 'asc')->orderByDesc('typemovement')->get();

            $sumatorias = response()->json($totales->groupBy('moneda_id')->map(function ($items, $moneda_id) {
                return [
                    'moneda' => $items->firstWhere('moneda_id', $moneda_id)->moneda->toArray(),
                    'totales' => [
                        MovimientosEnum::INGRESO->name => $items->where('typemovement', MovimientosEnum::INGRESO)->sum('total'),
                        MovimientosEnum::EGRESO->name => $items->where('typemovement', MovimientosEnum::EGRESO)->sum('total'),
                    ],
                ];
            }))->getData();
            // dd($sumatorias);
            // foreach ($sumatorias as $k => $value) {
            //     foreach ($value['totales'] as $key => $total) {
            //         dd($k, $value, $key, $total);
            //     }
            // }
        }

        $pdf = Pdf::loadView('admin.reports.report-cajamovimientos', compact('cajamovimientos', 'sumatorias', 'empresa', 'titulo'));
        return $pdf->stream("$titulo.pdf");
        // return $pdf->download('reporte_movimientos.pdf');
    }

    public function employers(Request $request)
    {
        $filters =  [
            "typereporte" => "",
            "sucursal_id" => "",
            "employer_id" => "",
            "user_id" => "",
            "monthbox_id" => "",
            "date" => "",
            "dateto" => "",
            "month" => "",
            "monthto" => "",
            "year" => "",
            "days" => [],
            "months" => [],
        ];

        $empresa = view()->shared('empresa');
        $typereportvalue = FilterReportsEnum::getValue(Str::upper($request->input('typereporte')));

        if ($typereportvalue === null) {
            $titulo = FilterReportsEnum::getLabel($typereportvalue);
            $cajamovimientos = [];
            $sumatorias = [];
        } else {
            $titulo = 'REPORTE DE PAGOS DE TRABAJADORES ' . FilterReportsEnum::getLabel($typereportvalue);
            foreach (array_keys($request->input()) as $param) {
                if (in_array($param, array_keys($filters))) {
                    if ($param == "typereporte") {
                        $filters[$param] = FilterReportsEnum::getValue(Str::upper($request->input($param)));
                    } else {
                        $filters[$param] = $request->input($param);
                    }
                }
            }
            $payments = Employerpayment::with(['employer' => function ($query) {
                $query->with(['sucursal', 'areawork'])
                    ->when($filters['sucursal_id'] ?? null, function ($subq, $sucursal_id) {
                        $subq->where('sucursal_id', $sucursal_id);
                    });
            }])->withWhereHas('cajamovimientos', function ($subq) use ($filters) {
                $subq->when($filters['monthbox_id'] ?? null, function ($q, $monthbox_id) {
                    $q->where('monthbox_id', $monthbox_id);
                })->when($filters['user_id'] ?? null, function ($q, $user_id) {
                    $q->where('user_id', $user_id);
                });
            })->when($filters['year'] ?? null, function ($subq, $year) {
                $subq->whereRaw("TO_CHAR(TO_DATE(month, 'YYYY-MM'), 'YYYY') = ?", [$year]);
            })->when($filters['employer_id'] ?? null, function ($subq, $employer_id) {
                $subq->where('employer_id', $employer_id);
            });

            if ($filters['typereporte'] == FilterReportsEnum::MES_ACTUAL->value) {
                $payments->where("month", Carbon::now('America/Lima')->format('Y-m'));
            } elseif ($filters['typereporte'] == FilterReportsEnum::MENSUAL->value) {
                $payments->where("month", Carbon::parse($filters['month'])->format('Y-m'));
            } elseif ($filters['typereporte'] == FilterReportsEnum::RANGO_MESES->value) {
                $payments->whereRaw("TO_CHAR(TO_DATE(month, 'YYYY-MM'), 'YYYY-MM')>= ? AND TO_CHAR(TO_DATE(month, 'YYYY-MM'), 'YYYY-MM')<= ?", [
                    Carbon::parse($filters['month'])->format('Y-m'),
                    Carbon::parse($filters['monthto'])->format('Y-m'),
                ]);
            } elseif ($filters['typereporte'] == FilterReportsEnum::ANIO_ACTUAL->value) {
                $payments->whereRaw("TO_CHAR(TO_DATE(month, 'YYYY-MM'), 'YYYY') = ?", [
                    Carbon::now('America/Lima')->format('Y'),
                ]);
            } elseif ($filters['typereporte'] == FilterReportsEnum::ULTIMO_MES->value) {
                $payments->where("month", Carbon::now('America/Lima')->subMonth()->format('Y-m'));
            } elseif ($filters['typereporte'] == FilterReportsEnum::ULTIMO_ANIO->value) {
                $payments->whereRaw("TO_CHAR(TO_DATE(month, 'YYYY-MM'), 'YYYY') = ?", [
                    Carbon::now('America/Lima')->subYear()->year,
                ]);
            } elseif ($filters['typereporte'] == FilterReportsEnum::MESES_SELECCIONADOS->value) {
                // $payments->whereRaw("TO_CHAR(month, 'YYYY-MM') IN (" . implode(',', array_fill(0, count($filters['months']), '?')) . ")", $filters['months']);
                $payments->whereIn('month', $filters['months']);
            }

            $adelantos = Employer::with('sucursal')->withWhereHas('cajamovimientos', function ($query) use ($filters) {
                $query->with(['monthbox', 'methodpayment'])->when($filters['monthbox_id'] ?? null, function ($subq, $monthbox_id) {
                    $subq->where('monthbox_id', $monthbox_id);
                })->when($filters['user_id'] ?? null, function ($subq, $user_id) {
                    $subq->where('user_id', $user_id);
                });

                if ($filters['typereporte'] == FilterReportsEnum::MES_ACTUAL->value) {
                    $query->whereRaw("TO_CHAR(date, 'YYYY-MM')= ?", [
                        Carbon::now('America/Lima')->format('Y-m'),
                    ]);
                } elseif ($filters['typereporte'] == FilterReportsEnum::MENSUAL->value) {
                    $query->whereRaw("TO_CHAR(date, 'YYYY-MM')= ?", [
                        Carbon::parse($filters['month'])->format('Y-m'),
                    ]);
                } elseif ($filters['typereporte'] == FilterReportsEnum::RANGO_MESES->value) {
                    $query->whereRaw("TO_CHAR(date, 'YYYY-MM')>= ? AND TO_CHAR(date, 'YYYY-MM')<= ?", [
                        Carbon::parse($filters['month'])->format('Y-m'),
                        Carbon::parse($filters['monthto'])->format('Y-m'),
                    ]);
                } elseif ($filters['typereporte'] == FilterReportsEnum::ANUAL->value) {
                    $query->whereYear('date', $filters['year']);
                } elseif ($filters['typereporte'] == FilterReportsEnum::ANIO_ACTUAL->value) {
                    $query->whereRaw("TO_CHAR(date, 'YYYY') = ?", [
                        Carbon::now('America/Lima')->format('Y'),
                    ]);
                } elseif ($filters['typereporte'] == FilterReportsEnum::ULTIMO_MES->value) {
                    $query->whereMonth("date", Carbon::now('America/Lima')->subMonth()->format('Y-m'));
                } elseif ($filters['typereporte'] == FilterReportsEnum::ULTIMO_ANIO->value) {
                    $query->whereYear('date', Carbon::now('America/Lima')->subYear()->year);
                } elseif ($filters['typereporte'] == FilterReportsEnum::MESES_SELECCIONADOS->value) {
                    $query->whereRaw("TO_CHAR(date, 'YYYY-MM') IN (" . implode(',', array_fill(0, count($filters['months']), '?')) . ")", $filters['months']);
                }
            })->when($filters['sucursal_id'] ?? null, function ($query, $sucursal_id) {
                $query->where('sucursal_id', $sucursal_id);
            })->when($filters['employer_id'] ?? null, function ($subq, $employer_id) {
                $subq->where('id', $employer_id);
            });

            $payments = $payments->orderByDesc('month')->get();
            $adelantos = $adelantos->orderBy('id', 'asc')->get();

            // $sumatorias = Employerpayment::with('moneda')
            //     ->select('moneda_id', DB::raw('SUM(amount) as total'))
            //     ->groupBy('moneda_id')->get();
            // dd($filters);
        }

        $pdf = Pdf::loadView('admin.reports.report-payment-employers', compact('payments', 'adelantos', 'empresa', 'titulo'));
        return $pdf->stream('cajamovimientos.pdf');
    }

    public function ventas(Request $request)
    {

        $filters =  [
            "typereporte" => "",
            "viewreporte" => "",
            "sucursal_id" => "",
            "typepayment_id" => "",
            "typecomprobante_id" => "",
            "user_id" => "",
            // "methodpayment_id" => "",
            // "monthbox_id" => "",
            // "openbox_id" => "",
            "client_id" => "",
            "moneda_id" => "",
            "date" => "",
            "dateto" => "",
            "week" => "",
            "month" => "",
            "monthto" => "",
            "year" => "",
            "days" => [],
            "months" => [],
        ];

        $detallado =  false;
        $empresa = view()->shared('empresa');
        $typereportvalue = FilterReportsEnum::getValue(Str::upper($request->input('typereporte')));

        if ($typereportvalue === null) {
            $titulo = FilterReportsEnum::getLabel($typereportvalue);
            $ventas = [];
            $sumatorias = [];
        } else {
            $titulo = 'REPORTE DE VENTA ' . FilterReportsEnum::getLabel($typereportvalue);
            foreach (array_keys($request->input()) as $param) {
                if (in_array($param, array_keys($filters))) {
                    if ($param == "typereporte") {
                        $filters[$param] = FilterReportsEnum::getValue(Str::upper($request->input($param)));
                    } else {
                        $filters[$param] = $request->input($param);
                    }
                }
            }

            $ventas = Venta::query()->select('ventas.*')
                ->with(['sucursal', 'client', 'typepayment', 'moneda', 'user', 'seriecomprobante.typecomprobante'])
                ->when($filters['viewreporte'] == 1, function ($query) {
                    $query->with(['tvitems' => function ($subq) {
                        $subq->with(['almacen', 'producto' => function ($q) {
                            $q->select('id', 'name', 'unit_id')->with('unit');
                        }]);
                    }]);
                })->queryFilter($filters)->when($filters['typecomprobante_id'], function ($subq, $typecomprobante_id) {
                    $subq->whereHas('seriecomprobante', function ($q) use ($typecomprobante_id) {
                        $q->where('typecomprobante_id', $typecomprobante_id);
                    });
                })->when($filters['typereporte'] == FilterReportsEnum::VENTAS_POR_COBRAR->value, function ($subq) {
                    $subq->whereColumn('paymentactual', '<', 'total');
                })->when($filters['typereporte'] == FilterReportsEnum::TOP_TEN_VENTAS->value, function ($subq) {
                    $subq->orderByDesc('total')->take(10);
                }, function ($subq) {
                    $subq->orderByDesc('date');
                })->get();
            // dd($ventas->toSql(), $ventas->getBindings());

            $sumatorias = $ventas->groupBy('moneda_id')->map(function ($ventas) use ($filters) {
                $amounts = [
                    'moneda_id' => $ventas->first()->moneda_id,
                    'moneda' => $ventas->first()->moneda,
                    'total' => $ventas->sum('total'),
                ];
                if ($filters['typereporte'] == FilterReportsEnum::VENTAS_POR_COBRAR->value) {
                    $amounts['saldos'] = $ventas->sum('total') - $ventas->sum('paymentactual');
                }
                return (object) $amounts;
            })->sortBy('moneda_id')->values();
            $detallado = (bool) $filters['viewreporte'];
        }

        $pdf = Pdf::loadView('admin.reports.report-ventas', compact('ventas', 'sumatorias', 'empresa', 'titulo', 'detallado'));
        return $pdf->stream("$titulo.pdf");
        // return $pdf->download('reporte_movimientos.pdf');
    }


    public function productos(Request $request)
    {
        $filters =  [
            "typereporte" => "",
            "viewreporte" => "",
            "producto_id" => "",
            "user_id" => "",
            "almacen_id" => "",
            "category_id" => "",
            "subcategory_id" => "",
            "pricetype_id" => "",
            // "date" => "",
            // "dateto" => "",
            // "week" => "",
            // "month" => "",
            // "monthto" => "",
            // "year" => "",
            // "days" => [],
            // "months" => [],
            "serie" => ""
        ];

        $detallado =  false;
        $pricetypes =  [];
        $empresa = view()->shared('empresa');
        $typereportvalue = FilterReportsEnum::getValue(Str::upper($request->input('typereporte')));

        if ($typereportvalue === null) {
            $titulo = FilterReportsEnum::getLabel($typereportvalue);
            $ventas = [];
        } else {
            $titulo = 'REPORTE DE PRODUCTOS ' . FilterReportsEnum::getLabel($typereportvalue);
            foreach (array_keys($request->input()) as $param) {
                if (in_array($param, array_keys($filters))) {
                    if ($param == "typereporte") {
                        $filters[$param] = FilterReportsEnum::getValue(Str::upper($request->input($param)));
                    } else {
                        $filters[$param] = $request->input($param);
                    }
                }
            }

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
                // ->withCount(['almacens as stock' => function ($query) use ($filters) {
                //     $query->when($filters['almacen_id'] ?? null, function ($q, $almacen_id) {
                //         $q->where('almacens.id', $almacen_id);
                //     })->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)')); // Suma de la cantidad en la tabla pivote
                // }])
                ->with(['unit', 'imagen', 'almacens' => function ($query) use ($filters) {
                    $query->when($filters['almacen_id'] ?? null, function ($q, $almacen_id) {
                        $q->where('almacens.id', $almacen_id);
                    });
                }, 'series' => function ($query) {
                    $query->when($filters['almacen_id'] ?? null, function ($q, $almacen_id) {
                        $q->where('almacen_id', $almacen_id);
                    });
                }])->when($filters['viewreporte'] == 1, function ($query) {
                    $query->with(['compraitems.compra.proveedor', 'tvitems' => function ($subq) {
                        $subq->with(['almacen', 'producto' => function ($q) {
                            $q->select('id', 'name', 'unit_id')->with('unit');
                        }]);
                    }, 'especificacions' => function ($subq) {
                        $subq->with('caracteristica');
                    }]);
                })->when($filters['typereporte'] == FilterReportsEnum::PRODUCTOS_PROMOCIONADOS->value, function ($query) {
                    $query->with(['promocions' => function ($subq) {
                        $subq->with(['itempromos.producto.unit', 'producto'])->disponibles()->availables();
                    }]);
                })->when($filters['typereporte'] == FilterReportsEnum::MIN_STOCK->value, function ($query) {
                    $query->whereHas('almacens', function ($query) {
                        $query->select(DB::raw('SUM(almacen_producto.cantidad) as total_stock'))
                            ->groupBy('almacen_producto.producto_id')
                            ->havingRaw('SUM(almacen_producto.cantidad) <= productos.minstock');
                    });
                });

            if (!empty($filters['producto_id'])) {
                $productos->when($filters['producto_id'], function ($query, $producto_id) {
                    $query->where('productos.id', $producto_id);
                });
            } else {
                $productos->queryFilter($filters)->when($filters['category_id'], function ($query, $category_id) {
                    $query->where('category_id', $category_id);
                })->when($filters['subcategory_id'], function ($query, $subcategory_id) {
                    $query->where('subcategory_id', $subcategory_id);
                });
            }

            $productos = $productos->orderByDesc('novedad')->orderBy('categories.orden', 'asc')
                ->orderBy('subcategories.orden', 'asc')->get();
            $detallado = (bool) $filters['viewreporte'];
        }

        if ($filters['typereporte'] == FilterReportsEnum::TOP_TEN_PRODUCTOS->value) {

            $productos = Producto::query()->select('id', 'name', 'modelo', 'sku', 'partnumber', 'slug', 'marca_id', 'category_id', 'subcategory_id', 'unit_id')
                ->with(['category', 'marca', 'subcategory', 'unit'])
                ->withWhereHas('tvitems', function ($query) use ($filters) {
                    $query->with(['almacen'])->when($filters['almacen_id'], function ($q, $almacen_id) {
                        $q->where('almacen_id', $almacen_id);
                    })->when($filters['user_id'], function ($q, $user_id) {
                        $q->where('user_id', $user_id);
                    });
                })
                ->withSum(['tvitems as vendidos' => function ($query) use ($filters) {
                    $query->with(['almacen'])->when($filters['almacen_id'], function ($q, $almacen_id) {
                        $q->where('almacen_id', $almacen_id);
                    })->when($filters['user_id'], function ($q, $user_id) {
                        $q->where('user_id', $user_id);
                    });
                }], 'cantidad')
                ->orderByDesc('vendidos')->take(10)->get();

            $pdf = SnappyPdf::loadView('admin.reports.productos.report-top-productos', compact('productos', 'empresa', 'detallado', 'titulo',))
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
        } elseif ($filters['typereporte'] == FilterReportsEnum::KARDEX_PRODUCTOS->value) {
            // dd((int) $filters['producto_id']);
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
                }])->where('id', $filters['producto_id'])->get();

            $pdf = SnappyPdf::loadView('admin.reports.productos.report-kardex-productos', compact('productos', 'empresa', 'detallado', 'titulo',))
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

            // return $productos;
        } elseif ($filters['typereporte'] == FilterReportsEnum::MIN_STOCK->value) {

            if ($empresa->usarLista()) {
                $pricetypes = Pricetype::activos();

                if (!empty($filters['pricetype_id'])) {
                    $pricetypes->where('id', $filters['pricetype_id']);
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
                }])->whereHas('almacens', function ($query) use ($filters) {
                    $query->select(DB::raw('SUM(almacen_producto.cantidad) as total_stock'))
                        ->groupBy('almacen_producto.producto_id')
                        ->havingRaw('SUM(almacen_producto.cantidad) <= productos.minstock');
                })->visibles()->orderBy('novedad', 'DESC')->orderBy('subcategories.orden', 'ASC')
                ->orderBy('categories.orden', 'ASC')->get();

            $pdf = SnappyPdf::loadView('admin.reports.productos.report-min-stock-productos', compact('productos', 'empresa', 'pricetypes', 'detallado', 'titulo',))
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
        } elseif ($filters['typereporte'] == FilterReportsEnum::PRODUCTOS_PROMOCIONADOS->value) {

            $productos = Producto::query()->select(
                'productos.id',
                'productos.name',
                'productos.slug',
                'unit_id',
                'modelo',
                'marca_id',
                'category_id',
                'subcategory_id',
                'visivility',
                'publicado',
                'novedad',
                'sku',
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
                ->with(['unit', 'imagen', 'almacens'])
                ->addSelect(['image' => function ($query) {
                    $query->select('url')->from('images')
                        ->whereColumn('images.imageable_id', 'productos.id')
                        ->where('images.imageable_type', Producto::class)
                        ->orderBy('orden', 'asc')->orderBy('id', 'asc')->limit(1);
                }])
                ->withWhereHas('promocions', function ($query) {
                    $query->with(['itempromos' => function ($q) {
                        $q->with(['producto' => function ($subq) {
                            $subq->with(['imagen', 'unit', 'almacens'])
                                ->addSelect(['image' => function ($query) {
                                    $query->select('url')->from('images')
                                        ->whereColumn('images.imageable_id', 'productos.id')
                                        ->where('images.imageable_type', Producto::class)
                                        ->orderBy('orden', 'asc')->orderBy('id', 'asc')->limit(1);
                                }]);
                        }]);
                    }])->disponibles()->availables();
                })->visibles()->orderBy('novedad', 'DESC')->orderBy('subcategories.orden', 'ASC')
                ->orderBy('categories.orden', 'ASC')->get();

            $pdf = SnappyPdf::loadView('admin.reports.productos.report-productos-promocion', compact('productos', 'empresa', 'pricetypes', 'detallado', 'titulo',))
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
        } else {

            if ($empresa->usarLista()) {
                $pricetypes = Pricetype::activos();

                if (!empty($filters['pricetype_id'])) {
                    $pricetypes->where('id', $filters['pricetype_id']);
                }
                $pricetypes = $pricetypes->orderBy('id', 'asc')->orderBy('default', 'asc')->get();
            }
            $pdf = SnappyPdf::loadView('admin.reports.productos.report-productos', compact('productos', 'empresa', 'pricetypes', 'titulo', 'detallado'))
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
        }

        return $pdf->inline("$titulo.pdf");
    }
}
