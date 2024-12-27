<?php

namespace Modules\Ventas\Http\Controllers;

use App\Enums\PromocionesEnum;
use App\Models\Almacen;
use App\Models\Carshoop;
use App\Models\Category;
use App\Models\Concept;
use App\Models\Marca;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Monthbox;
use App\Models\Openbox;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Promocion;
use App\Models\Serie;
use App\Models\Subcategory;
use App\Models\Typepayment;
use App\Models\Ubigeo;
use App\Rules\ValidateStock;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Ventas\Entities\Venta;
use Nwidart\Modules\Facades\Module;
use Nwidart\Modules\Routing\Controller;

class VentaController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.ventas')->only('index');
        $this->middleware('can:admin.ventas.create')->only('create');
        $this->middleware('can:admin.ventas.edit')->only('show');
        $this->middleware('can:admin.ventas.delete')->only('delete');
        $this->middleware('can:admin.ventas.cobranzas')->only('cobranzas');
    }

    public function index()
    {
        $openbox = Openbox::mybox(auth()->user()->sucursal_id)->first();
        $monthbox = Monthbox::usando(auth()->user()->sucursal_id)->first();

        return view('ventas::index', compact('openbox', 'monthbox'));
    }

    public function create()
    {

        $empresa = view()->shared('empresa');
        $moneda = Moneda::default()->first();
        $concept = Concept::ventas()->first();
        $seriecomprobante = auth()->user()->sucursal->seriecomprobantes()->withTrashed()
            ->select('seriecomprobantes.*')
            ->join('typecomprobantes', 'seriecomprobantes.typecomprobante_id', '=', 'typecomprobantes.id')
            ->when(Module::isDisabled('Facturacion'), function ($query) {
                $query->whereHas('typecomprobante', function ($q) {
                    $q->default();
                });
            })->whereNotIn('typecomprobantes.code', ['07', '09', '13'])
            ->orderBy('seriecomprobantes.default', 'desc')->orderBy('typecomprobantes.code', 'asc')
            ->orderBy('seriecomprobantes.default', 'desc')->with('typecomprobante')->first();

        $pricetype = null;
        if ($empresa->usarlista()) {
            $pricetype = Pricetype::activos()->orderBy('default', 'desc')->orderBy('id', 'asc')->first();
        }

        return view('ventas::ventas.create', compact('seriecomprobante', 'empresa', 'moneda', 'concept', 'pricetype'));
    }

    public function show(Venta $venta)
    {
        $this->authorize('sucursal', $venta);
        $venta->load([
            'client',
            'seriecomprobante.typecomprobante',
            'moneda',
            'sucursal.empresa',
            'typepayment',
            'cuotas',
            'cajamovimientos' => function ($query) {
                $query->with(['openbox.box', 'moneda', 'methodpayment', 'monthbox']);
            },
            'tvitems' => function ($query) {
                $query->with(['almacen', 'producto' => function ($subQuery) {
                    $subQuery->withTrashed()->with('unit')->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderByDesc('default')->limit(1);
                    }]);
                }, 'itemseries.serie' => function ($query) {
                    $query->withTrashed();
                }]);
            }
        ]);
        return view('ventas::ventas.show', compact('venta'));
    }


    public function cobranzas()
    {
        return view('ventas::ventas.cobranzas');
    }

    public function imprimirticket(Venta $venta)
    {

        if (Module::isEnabled('Facturacion')) {
            if ($venta->comprobante) {
                return redirect()->route('admin.facturacion.print.ticket', $venta->comprobante);
            }
        }
        $heightHeader = 250;
        $heightBody = (count($venta->tvitems) * 3 * 12) * 2.8346;
        $heightFooter = 400; #Incl. Totales, QR, Leyenda, Info, Web
        $heightPage = number_format($heightHeader + $heightBody + $heightFooter, 2, '.', '');
        $pdf =  PDF::setPaper([0, 0, 226.77, $heightPage])->loadView('ventas::pdf.ticket', compact('venta'));
        return $pdf->stream();
    }


    // public function data(Request $request)
    // {
    //     $search = $request->input('search');
    //     $products = Producto::query()->select('id', 'name', 'slug');

    //     if (strlen(trim($search)) < 2) {
    //         $products->visibles()->orderBy('name', 'asc');
    //     } else {
    //         if (trim($search) !== '') {
    //             $searchTerms = explode(' ', $search);
    //             $products->where(function ($query) use ($searchTerms) {
    //                 foreach ($searchTerms as $term) {
    //                     $query->orWhere('name', 'ilike', '%' . $term . '%');
    //                 }
    //             })->visibles()->orderBy('name', 'asc')->limit(10);
    //         }
    //     }

    //     $products = $products->get()->map(function ($producto) {
    //         return [
    //             'value' => $producto->id,
    //             'text' => $producto->name,
    //         ];
    //     });

    //     return response()->json($products);
    // }

    public function marcas()
    {

        $marcas = Marca::whereHas('productos', function ($query) {
            $query->visibles();
        })->orderBy('name', 'asc');
        $marcas = $marcas->get()->map(function ($item) {
            return [
                'id' => $item->slug,
                'text' => $item->name,
            ];
        });

        return response()->json($marcas);
    }

    public function categories()
    {

        $categories = Category::whereHas('productos', function ($query) {
            $query->visibles();
        })->orderBy('orden', 'asc');
        $categories = $categories->get()->map(function ($item) {
            return [
                'id' => $item->slug,
                'text' => $item->name,
            ];
        });

        return response()->json($categories);
    }

    public function subcategories(Request $request)
    {

        $subcategories = [];
        $category_slug = $request->input('category_id');

        if (!empty($category_slug)) {
            $subcategories = Subcategory::whereHas('categories', function ($query) use ($category_slug) {
                $query->where('categories.slug', $category_slug);
            })->whereHas('productos', function ($query) {
                $query->visibles();
            })->orderBy('orden', 'asc')->get();
            $subcategories = $subcategories->map(function ($item) {
                return [
                    'id' => $item->slug,
                    'text' => $item->name,
                ];
            });
        }

        return response()->json($subcategories);
    }

    public function ubigeos()
    {
        $ubigeos = Ubigeo::query()->select('id', 'region', 'provincia', 'distrito', 'ubigeo_reniec')
            ->orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc');
        $ubigeos = $ubigeos->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' =>  "$item->region / $item->provincia / $item->distrito / $item->ubigeo_reniec",
            ];
        });

        return response()->json($ubigeos);
    }

    public function almacens()
    {
        $almacens = Almacen::whereHas('productos')->orderBy('name', 'asc');
        $almacens = $almacens->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' =>  $item->name,
            ];
        });

        return response()->json($almacens);
    }

    public function pricetypes()
    {
        $pricetypes = Pricetype::activos()->orderBy('id', 'asc')->get();
        $pricetypes = $pricetypes->map(function ($item) {
            return [
                'id' => $item->id,
                'text' =>  $item->name,
            ];
        });

        return response()->json($pricetypes);
    }

    public function typepayments()
    {
        $typepayments = Typepayment::activos()->orderBy('default', 'desc')->get();
        $typepayments = $typepayments->map(function ($item) {
            return [
                'id' => $item->id,
                'text' =>  $item->name,
                'paycuotas' => $item->isCredito()
            ];
        });

        return response()->json($typepayments);
    }

    public function methodpayments()
    {
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        $methodpayments = $methodpayments->map(function ($item) {
            return [
                'id' => $item->id,
                'text' =>  $item->name,
                'transferencia' => $item->isTransferencia()
            ];
        });

        return response()->json($methodpayments);
    }

    public function seriecomprobantes()
    {
        $typecomprobantes = auth()->user()->sucursal->seriecomprobantes()->withTrashed()
            ->select('seriecomprobantes.*')->join('typecomprobantes', 'seriecomprobantes.typecomprobante_id', '=', 'typecomprobantes.id')
            ->when(Module::isDisabled('Facturacion'), function ($query) {
                $query->whereHas('typecomprobante', function ($q) {
                    $q->default();
                });
            })->whereNotIn('typecomprobantes.code', ['07', '09', '13'])
            ->orderBy('seriecomprobantes.default', 'desc')->orderBy('typecomprobantes.code', 'asc')
            ->orderBy('seriecomprobantes.default', 'desc')->with('typecomprobante')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->typecomprobante->name . " [$item->serie]",
                    'code' => $item->typecomprobante->code,
                    'sunat' =>  $item->typecomprobante->isSunat(),
                ];
            });

        return response()->json($typecomprobantes);
    }

    public function seriesguia()
    {
        $typecomprobantes = auth()->user()->sucursal->seriecomprobantes()->withTrashed()
            ->select('seriecomprobantes.*')->join('typecomprobantes', 'seriecomprobantes.typecomprobante_id', '=', 'typecomprobantes.id')
            ->when(Module::isDisabled('Facturacion'), function ($query) {
                $query->whereHas('typecomprobante', function ($q) {
                    $q->default();
                });
            })->whereIn('typecomprobantes.code', ['09', '13'])
            ->orderBy('seriecomprobantes.default', 'desc')->orderBy('typecomprobantes.code', 'asc')
            ->orderBy('seriecomprobantes.default', 'desc')->with('typecomprobante')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->typecomprobante->name . " [$item->serie]",
                    'code' => $item->typecomprobante->code,
                    'sunat' =>  $item->typecomprobante->isSunat(),
                ];
            });

        return response()->json($typecomprobantes);
    }

    public function addcarshoop(Request $request)
    {

        DB::beginTransaction();
        try {
            $empresa = view()->shared('empresa');
            $pricetype = null;
            $cantidad = null;

            $rules = [
                'producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
                'price' => ['required', 'numeric', 'decimal:0,4', 'gt:0'],
                'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
                'pricetype_id' => ['nullable', Rule::requiredIf($empresa->usarLista()), 'integer', 'min:1', 'exists:pricetypes,id'],
            ];
            $attributes = [
                'serie_id' => 'serie',
                'producto_id' => 'producto',
            ];

            if ($request->filled('promocion_id')) {
                $rules['promocion_id'] = ['required', 'integer', 'min:1', 'exists:promocions,id'];
            }

            if ($request->filled('pricetype_id')) {
                $pricetype = Pricetype::find($request->input('pricetype_id'));
            }

            $open_modal = filter_var($request->input('open_modal'), FILTER_VALIDATE_BOOLEAN);
            if ($request->has('producto_id')) {
                $producto =  Producto::with(['promocions' => function ($query) use ($open_modal) {
                    if ($open_modal) {
                        $query->with(['itempromos.producto' => function ($subQuery) {
                            $subQuery->with(['unit', 'almacens'])->addSelect(['image' => function ($q) {
                                $q->select('url')->from('images')
                                    ->whereColumn('images.imageable_id', 'productos.id')
                                    ->where('images.imageable_type', Producto::class)
                                    ->orderByDesc('default')->limit(1);
                            }]);
                        }])->combos()->availables()->disponibles();
                    }
                }])->find($request->input('producto_id'));

                if (empty($producto)) {
                    return response()->json([
                        'error' => 'EL PRODUCTO NO SE ENCUENTRA DISPONIBLE !',
                    ])->getData();
                    return false;
                }

                $almacen_id = null;
                if ($request->hasAny(['serie_id', "selectedalmacen_$producto->id"])) {
                    $almacen_id = $request->input("selectedalmacen_$producto->id");

                    if ($request->filled('serie_id')) {
                        $serie = Serie::find($request->input('serie_id'));
                        $almacen_id = $serie->almacen_id;
                    };

                    $producto->loadCount(['almacens as stock' => function ($query) use ($almacen_id) {
                        if (!empty($almacen_id)) {
                            $query->where('almacen_id', $almacen_id)->select(DB::raw('COALESCE(SUM(cantidad), 0)'));
                        } else {
                            $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)'));
                        }
                    }]);
                }

                if ($open_modal && count($producto->promocions) > 0) {
                    $combosDisponibles = 0;
                    foreach ($producto->promocions as $item) {
                        $combo = getAmountCombo($item, $pricetype);
                        if ($combo->is_disponible && $combo->stock_disponible) {
                            $combosDisponibles++;
                            break;
                        }
                    }

                    if ($combosDisponibles > 0) {
                        return response()->json([
                            'success' => true,
                            'open_modal' => true,
                            'producto_id' => $producto->id,
                        ])->getData();
                        return false;
                    }
                }

                if ($request->has('serie_id')) {
                    $cantidad = 1;
                    $rules['serie_id'] = [Rule::requiredIf($producto->isRequiredserie()), 'integer', 'min:1', 'exists:series,id'];
                } else {
                    // 'almacen_id' => ($producto->isRequiredserie() && empty($cart['serie_id'])) ? [] : ['required', 'integer', 'min:1', 'exists:almacens,id', $producto->isRequiredserie() ? '' : new ValidateStock($producto->id, $cart["almacen_id"])],
                    $rules["selectedalmacen_$producto->id"] = [
                        'nullable',
                        Rule::requiredIf(!$producto->isRequiredserie()),
                        'integer',
                        'min:1',
                        'exists:series,id'
                    ];
                    $rules['cantidad'] = [
                        'required',
                        'numeric',
                        'min:1',
                        'integer',
                        empty($almacen_id) ? '' : new ValidateStock($producto->id, $almacen_id, $request->input('cantidad'))
                    ];
                    $cantidad = $request->input('cantidad');
                }

                $attributes["selectedalmacen_$producto->id"] = 'almacen';
                $validator = Validator::make($request->all(), $rules);
                $validator->setAttributeNames($attributes);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validation failed',
                        'errors' => $validator->errors(),
                        'producto_id' => $producto->id,
                    ])->getData();
                }

                $date = now('America/Lima')->format('Y-m-d H:i:s');
                $moneda_id = $request->input('moneda_id');
                $pricesale = $request->input('price');
                $igvsale = 0;
                $serie = null;
                $promocion = null;
                $stock_disponible_promocion = 0;
                $qty_sin_oferta = $cantidad;

                if ($request->filled('serie_id')) {
                    $serie = Serie::find($request->input('serie_id'));
                    if (empty($serie) || !$serie->isDisponible()) {
                        return response()->json([
                            'error' => 'SERIE NO SE ENCUENTRA DISPONIBLE'
                        ])->getData();
                        return false;
                    }
                }

                if ($request->filled('promocion_id')) {
                    $promocion = Promocion::find($request->input('promocion_id'));
                    if (!empty(verifyPromocion($promocion))) {
                        $stock_disponible_promocion = $promocion->limit - $promocion->outs;
                        $qty_sin_oferta = $cantidad - $stock_disponible_promocion;
                    } else {
                        $pricesale = $producto->getPrecioVentaDefault($pricetype);
                    }
                }

                if ($empresa->isAfectacionIGV()) {
                    $pricesale = getPriceIGV($pricesale, $empresa->igv)->price;
                    $igvsale = getPriceIGV($pricesale, $empresa->igv)->igv;

                    // $priceold = getPriceIGV($priceold, $empresa->igv)->price;
                    // $igveold = getPriceIGV($priceold, $empresa->igv)->igv;
                }

                // cantidad sin promocion xq supera stock disp. promocion
                // Ejm: lim=3, Outs=3, Cant=5, qtySO=(cant-disp)=5
                if ($cantidad == $qty_sin_oferta) {
                    $carshoop = Carshoop::with('kardex')->existsInCartVenta($producto->id, $almacen_id, $moneda_id, null)->first();

                    if (!empty($carshoop)) {
                        $carshoop->cantidad = $carshoop->cantidad + $cantidad;
                        $carshoop->pricebuy = $producto->pricebuy;
                        $carshoop->price = $pricesale;
                        $carshoop->igv = $igvsale;
                        $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                        $carshoop->total = ($pricesale + $igvsale) * $carshoop->cantidad;
                        $carshoop->save();
                    } else {
                        $carshoop = Carshoop::create([
                            'date' => $date,
                            'cantidad' => $cantidad,
                            'pricebuy' => $producto->pricebuy,
                            'price' => $pricesale,
                            'igv' => $igvsale,
                            'subtotal' => $pricesale * $cantidad,
                            'total' => ($pricesale + $igvsale) * $cantidad,
                            'gratuito' => 0,
                            'status' => 0,
                            'producto_id' => $producto->id,
                            'almacen_id' => $almacen_id,
                            'moneda_id' => $moneda_id,
                            'user_id' => auth()->user()->id,
                            'sucursal_id' => auth()->user()->sucursal_id,
                            'alterstock' => Almacen::DISMINUIR_STOCK,
                            'promocion_id' => null,
                            'cartable_type' => Venta::class,
                        ]);
                    }
                    // La funcion saveKardexCarshoop tambien descuenta la promocion
                    $carshoop->saveKardexCarshoop($producto->stock, $cantidad);
                    $producto->almacens()->updateExistingPivot($almacen_id, [
                        'cantidad' => $producto->stock - $cantidad,
                    ]);
                    if (!empty($serie)) {
                        $carshoop->descontarSerieCarshoop($serie->id);
                    }
                } else {
                    // Ejm: lim=3, Outs=1, Cant=2, qtySO=(cant-disp)=0
                    $carshoop = Carshoop::with(['kardex', 'carshoopitems'])->existsInCartVenta($producto->id, $almacen_id, $moneda_id, !empty($promocion) ? $promocion->id : null)->first();
                    $new_qty = $qty_sin_oferta > 0 ? $stock_disponible_promocion : $cantidad;

                    if (!empty($carshoop)) {
                        $carshoop->cantidad = $carshoop->cantidad + $new_qty;
                        $carshoop->pricebuy = $producto->pricebuy;
                        $carshoop->price = $pricesale;
                        $carshoop->igv = $igvsale;
                        $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                        $carshoop->total = ($pricesale + $igvsale) * $carshoop->cantidad;
                        $carshoop->save();
                    } else {
                        $carshoop = Carshoop::create([
                            'date' => $date,
                            'cantidad' => $new_qty,
                            'pricebuy' => $producto->pricebuy,
                            'price' => $pricesale,
                            'igv' => $igvsale,
                            'subtotal' => $pricesale * $new_qty,
                            'total' => ($pricesale + $igvsale) * $new_qty,
                            'gratuito' => 0,
                            'status' => 0,
                            'producto_id' => $producto->id,
                            'almacen_id' => $almacen_id,
                            'moneda_id' => $moneda_id,
                            'user_id' => auth()->user()->id,
                            'sucursal_id' => auth()->user()->sucursal_id,
                            'alterstock' => Almacen::DISMINUIR_STOCK,
                            'promocion_id' => !empty($promocion) ? $promocion->id : null,
                            'cartable_type' => Venta::class,
                        ]);
                    }
                    // La funcion saveKardexCarshoop tambien descuenta la promocion
                    $carshoop->saveKardexCarshoop($producto->stock, $new_qty);
                    $producto->almacens()->updateExistingPivot($almacen_id, [
                        'cantidad' => $producto->stock - $new_qty,
                    ]);

                    if (!empty($serie)) {
                        $carshoop->descontarSerieCarshoop($serie->id);
                    }

                    // Ejm: lim=3, Outs=0, Cant=5, qtySO=(cant-disp)=2
                    if ($qty_sin_oferta > 0) {
                        //Agregar con precio normal
                        $pricesale = $producto->getPrecioVentaDefault($pricetype);
                        $carshoop = Carshoop::with('kardex')->existsInCartVenta($producto->id, $almacen_id, $moneda_id, null)->first();

                        if (!empty($carshoop)) {
                            $carshoop->cantidad = $carshoop->cantidad + $qty_sin_oferta;
                            $carshoop->pricebuy = $producto->pricebuy;
                            $carshoop->price = $pricesale;
                            $carshoop->igv = $igvsale;
                            $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                            $carshoop->total = ($pricesale + $igvsale) * $carshoop->cantidad;
                            $carshoop->save();
                        } else {
                            $carshoop = Carshoop::create([
                                'date' => $date,
                                'cantidad' => $qty_sin_oferta,
                                'pricebuy' => $producto->pricebuy,
                                'price' => $pricesale,
                                'igv' => $igvsale,
                                'subtotal' => $pricesale * $qty_sin_oferta,
                                'total' => ($pricesale + $igvsale) * $qty_sin_oferta,
                                'gratuito' => 0,
                                'status' => 0,
                                'producto_id' => $producto->id,
                                'almacen_id' => $almacen_id,
                                'moneda_id' => $moneda_id,
                                'user_id' => auth()->user()->id,
                                'sucursal_id' => auth()->user()->sucursal_id,
                                'alterstock' => Almacen::DISMINUIR_STOCK,
                                'promocion_id' => null,
                                'cartable_type' => Venta::class,
                            ]);
                        }
                        // La funcion saveKardexCarshoop tambien descuenta la promocion
                        $carshoop->saveKardexCarshoop($producto->stock, $qty_sin_oferta);
                        $producto->almacens()->updateExistingPivot($almacen_id, [
                            'cantidad' => $producto->stock - $qty_sin_oferta,
                        ]);
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'mensaje' => 'AGREGADO AL CARRITO CORRECTAMENTE'
                ])->getData();
            } else {
                // sino envio producto_id es porque estoy agregando un combo
                $pricetype = null;
                $almacen_id =  $request->input('almacen_id');
                $pricetype_id =  $request->input('pricetype_id');
                $moneda_id =  $request->input('moneda_id');
                $date = now('America/Lima')->format('Y-m-d H:i:s');

                $promocion = Promocion::with(['itempromos.producto' => function ($query) {
                    $query->with(['unit', 'almacens'])->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderByDesc('default')->limit(1);
                    }]);
                }, 'producto' => function ($query) use ($almacen_id) {
                    $query->withCount(['almacens as stock' => function ($q) use ($almacen_id) {
                        if (!empty($almacen_id)) {
                            $q->where('almacen_id', $almacen_id);
                        }
                        $q->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)'));
                    }]);
                }])->find($request->input('promocion_id'));

                // return $promocion;

                if (empty(verifyPromocion($promocion))) {
                    return response()->json([
                        'error' => 'PROMOCIÓN NO SE ENCUENTRA DISPONIBLE !',
                    ])->getData();
                    return false;
                }

                $rules = [
                    // 'promocion_id' => ['required', 'integer', 'min:1', 'exists:promocions,id'],
                    'almacen_id' => ['required', 'integer', 'min:1', 'exists:almacens,id'],
                    'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
                    'pricetype_id' => ['nullable', Rule::requiredIf($empresa->usarLista()), 'integer', 'min:1', 'exists:pricetypes,id'],
                ];
                $attributes = [
                    // 'serie_id' => 'serie',
                    // 'producto_id' => 'producto',
                ];

                $validator = Validator::make($request->all(), $rules);
                $validator->setAttributeNames($attributes);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validation failed',
                        'errors' => $validator->errors(),
                        'promocion_id' => $promocion->id,
                    ])->getData();
                }

                // Ejm: lim=3, Outs=1, Cant=2, qtySO=(cant-disp)=0
                $stock_disponible_promocion = $promocion->limit - $promocion->outs;

                if ($promocion->producto->stock < 1) {
                    return response()->json([
                        'error' => 'CANTIDAD SUPERA EL STOCK DISPONIBLE DEL PRODUCTO !',
                    ])->getData();
                    return false;
                }

                if (1 > $stock_disponible_promocion) {
                    return response()->json([
                        'error' => 'CANTIDAD SUPERA EL STOCK DISPONIBLE EN PROMOCIÓN !',
                    ])->getData();
                    return false;
                }

                $pricetype = $empresa->usarLista() ? Pricetype::find($pricetype_id) : null;
                $pricesale = $promocion->producto->getPrecioventa($pricetype);
                $igvsale = 0;
                $combo = getAmountCombo($promocion, $pricetype, $almacen_id);

                if (!$combo->is_disponible) {
                    return response()->json([
                        'error' => 'PROMOCIÓN NO SE ENCUENTRA DISPONIBLE !',
                    ])->getData();
                    return false;
                }

                if (!$combo->stock_disponible) {
                    return response()->json([
                        'error' => 'STOCK DE PRODUCTO EN COMBO NO DISPONIBLE PARA EL ALMACÉN SELECCIONADO !',
                    ])->getData();
                    return false;
                }


                if ($combo) {
                    $pricesale = $pricesale + $combo->total;
                }
                $carshoop = Carshoop::with(['kardex', 'carshoopitems'])->existsInCartVenta($promocion->producto_id, $almacen_id, $moneda_id, $promocion->id)->first();
                // return [$request->input(), $pricesale, getPriceIGV($pricesale, $empresa->igv)];
                if ($empresa->isAfectacionIGV()) {
                    $pricesale = getPriceIGV($pricesale, $empresa->igv)->price;
                    $igvsale = getPriceIGV($pricesale, $empresa->igv)->igv;
                }

                if (!empty($carshoop)) {
                    $carshoop->cantidad = $carshoop->cantidad + 1;
                    $carshoop->pricebuy = $promocion->producto->pricebuy;
                    $carshoop->price = $pricesale;
                    $carshoop->igv = $igvsale;
                    $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                    $carshoop->total = ($pricesale + $igvsale) * $carshoop->cantidad;
                    $carshoop->save();
                } else {
                    $carshoop = Carshoop::create([
                        'date' => $date,
                        'cantidad' => 1,
                        'pricebuy' => $promocion->producto->pricebuy,
                        'price' => $pricesale,
                        'igv' => $igvsale,
                        'subtotal' => $pricesale,
                        'total' => $pricesale + $igvsale,
                        'gratuito' => 0,
                        'status' => 0,
                        'producto_id' => $promocion->producto_id,
                        'almacen_id' => $almacen_id,
                        'moneda_id' => $moneda_id,
                        'user_id' => auth()->user()->id,
                        'sucursal_id' => auth()->user()->sucursal_id,
                        'alterstock' => Almacen::DISMINUIR_STOCK,
                        'promocion_id' => $promocion->id,
                        'cartable_type' => Venta::class,
                    ]);
                }

                // La funcion saveKardexCarshoop tambien descuenta la promocion
                $carshoop->saveKardexCarshoop($promocion->producto->stock, 1);
                $promocion->producto->almacens()->updateExistingPivot($almacen_id, [
                    'cantidad' => $promocion->producto->stock - 1,
                ]);

                foreach ($combo->products as $item) {
                    $priceitem = $item->price;
                    $igvitem = 0;
                    if ($empresa->isAfectacionIGV()) {
                        $priceitem = getPriceIGV($priceitem, $empresa->igv)->price;
                        $igvitem = getPriceIGV($priceitem, $empresa->igv)->igv;
                    }

                    $carshoopitem = $carshoop->carshoopitems()->firstOrCreate([
                        'pricebuy' => $item->pricebuy
                    ], [
                        'price' => $priceitem,
                        'igv' => $igvitem,
                        'producto_id' => $item->producto_id,
                    ]);

                    $carshoopitem->producto->almacens()->updateExistingPivot($almacen_id, [
                        'cantidad' => $item->stock - 1,
                    ]);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'mensaje' => 'COMBO AGREGADO CORRECTAMENTE !',
                ])->getData();
                return false;
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ])->getData();
            return false;
            throw $e;
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ])->getData();
            return false;
            throw $e;
        }
    }

    public function getproductobyserie(Request $request)
    {
        DB::beginTransaction();
        try {
            $empresa = view()->shared('empresa');
            $pricetype = null;

            $rules = [
                'searchserie' => ['required', 'string', 'min:4'],
                'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
                'pricetype_id' => ['nullable', Rule::requiredIf($empresa->usarLista()), 'integer', 'min:1', 'exists:pricetypes,id'],
            ];
            $attributes = [
                'serie_id' => 'serie',
                'moneda_id' => 'moneda'
            ];

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($attributes);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'search' => $request->input('searchserie'),
                ])->getData();
            }

            $cantidad = 1;
            $date = now('America/Lima');
            $existskardex = false;
            $promocion_id = null;
            $moneda_id = $request->input('moneda_id');
            $searchserie = trim(mb_strtoupper($request->input('searchserie'), "UTF-8"));

            $serie = Serie::with(['almacen', 'producto' => function ($query) {
                $query->select('id', 'name', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5', 'requireserie', 'unit_id')
                    ->with(['unit', 'promocions' => function ($subQuery) {
                        $subQuery->with(['itempromos.producto' => function ($q) {
                            $q->with(['unit', 'almacens']);
                        }])->availables()->disponibles();
                    }])->visibles();
            }])->disponibles()->whereRaw('UPPER(serie) = ?', $searchserie)
                ->whereIn('almacen_id', auth()->user()->sucursal->almacens->pluck('id'))->first();

            if (empty($serie)) {
                $mensaje =  response()->json([
                    'error' => "SERIE $searchserie NO SE ENCUENTRA DISPONIBLE !",
                ])->getData();
                return $mensaje;
            }

            if (!$serie->isDisponible()) {
                $mensaje =  response()->json([
                    'error' => "SERIE $searchserie NO SE ENCUENTRA DISPONIBLE !",
                ])->getData();
                return $mensaje;
            }

            $serie->producto->loadCount(['almacens as stock' => function ($query) use ($serie) {
                $query->where('almacen_id', $serie->almacen_id)->select(DB::raw('COALESCE(SUM(cantidad), 0)'));
            }]);

            if ($serie->producto->stock <= 0) {
                return response()->json([
                    'error' => "STOCK DEL PRODUCTO NO DISPONIBLE !",
                ])->getData();
                return false;
            }

            $promociones = $serie->producto->promocions;
            $combos = $promociones->where('type', PromocionesEnum::COMBO->value);
            $promocion = null;

            if (count($promociones) > 0) {
                $serie->producto->descuento = $serie->producto->promocions->where('type', PromocionesEnum::DESCUENTO->value)->first()->descuento ?? 0;
                $serie->producto->liquidacion = $serie->producto->promocions->where('type', PromocionesEnum::LIQUIDACION->value)->count() > 0 ? true : false;

                if ($serie->producto->descuento > 0 || $serie->producto->liquidacion) {
                    $promocion = $serie->producto->promocions->where('type', '<>', \App\Enums\PromocionesEnum::COMBO->value)->first();
                    $promocion = verifyPromocion($promocion);
                }
            }

            if ($empresa->usarLista()) {
                $pricetype = Pricetype::find($request->input('pricetype_id'));
            }

            if ($combos) {
                $combosDisponibles = 0;
                foreach ($combos as $item) {
                    $combo = getAmountCombo($item, $pricetype);
                    if ($combo->is_disponible && $combo->stock_disponible) {
                        $combosDisponibles++;
                        break;
                    }
                }

                if ($combosDisponibles > 0) {
                    return response()->json([
                        'success' => true,
                        'open_modal' => true,
                        'producto_id' => $serie->producto_id,
                    ])->getData();
                    return false;
                }
            }

            $priceold = $serie->producto->getPrecioVentaDefault($pricetype);
            $pricesale = $serie->producto->getPrecioVenta($pricetype);
            $igvsale = 0;
            $qty_sin_oferta = $cantidad;

            if (!empty($promocion)) {
                $promocion_id = $promocion->id;
                $stock_disponible_promocion = $promocion->limit - $promocion->outs;
                $qty_sin_oferta = $cantidad - $stock_disponible_promocion;
            } else {
                $pricesale = $priceold;
            }

            if ($empresa->isAfectacionIGV()) {
                $pricesale = getPriceIGV($pricesale, $empresa->igv)->price;
                $igvsale = getPriceIGV($pricesale, $empresa->igv)->igv;
            }

            if ($cantidad == $qty_sin_oferta) {
                $carshoop = Carshoop::with('kardex')->existsInCartVenta($serie->producto_id, $serie->almacen_id, $moneda_id, null)->first();

                if (!empty($carshoop)) {
                    $carshoop->cantidad = $carshoop->cantidad + $cantidad;
                    $carshoop->pricebuy = $serie->producto->pricebuy;
                    $carshoop->price = $pricesale;
                    $carshoop->igv = $igvsale;
                    $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                    $carshoop->total = ($pricesale + $igvsale) * $carshoop->cantidad;
                    $carshoop->save();
                } else {
                    $carshoop = Carshoop::create([
                        'date' => $date,
                        'cantidad' => $cantidad,
                        'pricebuy' => $serie->producto->pricebuy,
                        'price' => $pricesale,
                        'igv' => $igvsale,
                        'subtotal' => $pricesale * $cantidad,
                        'total' => ($pricesale + $igvsale) * $cantidad,
                        'gratuito' => 0,
                        'status' => 0,
                        'producto_id' => $serie->producto->id,
                        'almacen_id' => $serie->almacen_id,
                        'moneda_id' => $moneda_id,
                        'user_id' => auth()->user()->id,
                        'sucursal_id' => auth()->user()->sucursal_id,
                        'alterstock' => Almacen::DISMINUIR_STOCK,
                        'promocion_id' => null,
                        'cartable_type' => Venta::class,
                    ]);
                }
                // La funcion saveKardexCarshoop tambien descuenta la promocion
                $carshoop->saveKardexCarshoop($serie->producto->stock, $cantidad);
                $serie->producto->almacens()->updateExistingPivot($serie->almacen_id, [
                    'cantidad' => $serie->producto->stock - $cantidad,
                ]);
                $carshoop->descontarSerieCarshoop($serie->id);
            } else {
                // Ejm: lim=3, Outs=1, Cant=2, qtySO=(cant-disp)=0
                $carshoop = Carshoop::with(['kardex', 'carshoopitems'])->existsInCartVenta($serie->producto_id, $serie->almacen_id, $moneda_id, !empty($promocion) ? $promocion->id : null)->first();
                $new_qty = $qty_sin_oferta > 0 ? $stock_disponible_promocion : $cantidad;

                if (!empty($carshoop)) {
                    $carshoop->cantidad = $carshoop->cantidad + $new_qty;
                    $carshoop->pricebuy = $serie->producto->pricebuy;
                    $carshoop->price = $pricesale;
                    $carshoop->igv = $igvsale;
                    $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                    $carshoop->total = ($pricesale + $igvsale) * $carshoop->cantidad;
                    $carshoop->save();
                } else {
                    $carshoop = Carshoop::create([
                        'date' => $date,
                        'cantidad' => $new_qty,
                        'pricebuy' => $serie->producto->pricebuy,
                        'price' => $pricesale,
                        'igv' => $igvsale,
                        'subtotal' => $pricesale * $new_qty,
                        'total' => ($pricesale + $igvsale) * $new_qty,
                        'gratuito' => 0,
                        'status' => 0,
                        'producto_id' => $serie->producto_id,
                        'almacen_id' => $serie->almacen_id,
                        'moneda_id' => $moneda_id,
                        'user_id' => auth()->user()->id,
                        'sucursal_id' => auth()->user()->sucursal_id,
                        'alterstock' => Almacen::DISMINUIR_STOCK,
                        'promocion_id' => !empty($promocion) ? $promocion->id : null,
                        'cartable_type' => Venta::class,
                    ]);
                }
                // La funcion saveKardexCarshoop tambien descuenta la promocion
                $carshoop->saveKardexCarshoop($serie->producto->stock, $new_qty);
                $serie->producto->almacens()->updateExistingPivot($serie->almacen_id, [
                    'cantidad' => $serie->producto->stock - $new_qty,
                ]);
                $carshoop->descontarSerieCarshoop($serie->id);

                // Ejm: lim=3, Outs=0, Cant=5, qtySO=(cant-disp)=2
                if ($qty_sin_oferta > 0) {
                    //Agregar con precio normal
                    $pricesale = $serie->producto->getPrecioVentaDefault($pricetype);
                    $carshoop = Carshoop::with('kardex')->existsInCartVenta($serie->producto_id, $serie->almacen_id, $moneda_id, null)->first();

                    if (!empty($carshoop)) {
                        $carshoop->cantidad = $carshoop->cantidad + $qty_sin_oferta;
                        $carshoop->pricebuy = $serie->producto->pricebuy;
                        $carshoop->price = $pricesale;
                        $carshoop->igv = $igvsale;
                        $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                        $carshoop->total = ($pricesale + $igvsale) * $carshoop->cantidad;
                        $carshoop->save();
                    } else {
                        $carshoop = Carshoop::create([
                            'date' => $date,
                            'cantidad' => $qty_sin_oferta,
                            'pricebuy' => $serie->producto->pricebuy,
                            'price' => $pricesale,
                            'igv' => $igvsale,
                            'subtotal' => $pricesale * $qty_sin_oferta,
                            'total' => ($pricesale + $igvsale) * $qty_sin_oferta,
                            'gratuito' => 0,
                            'status' => 0,
                            'producto_id' => $serie->producto_id,
                            'almacen_id' => $serie->almacen_id,
                            'moneda_id' => $moneda_id,
                            'user_id' => auth()->user()->id,
                            'sucursal_id' => auth()->user()->sucursal_id,
                            'alterstock' => Almacen::DISMINUIR_STOCK,
                            'promocion_id' => null,
                            'cartable_type' => Venta::class,
                        ]);
                    }
                    // La funcion saveKardexCarshoop tambien descuenta la promocion
                    $serie->carshoop->saveKardexCarshoop($serie->producto->stock, $qty_sin_oferta);
                    $serie->producto->almacens()->updateExistingPivot($serie->almacen_id, [
                        'cantidad' => $serie->producto->stock - $qty_sin_oferta,
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'mensaje' => 'AGREGADO CORRECTAMENTE !',
            ])->getData();
            return false;

            // return $serie;
            // $carshoop = "([])";
            // $carshoop->saveKardexCarshoop($serie->producto->stock, 1);
            // $serie->producto->almacens()->updateExistingPivot($serie->almacen_id, [
            //     'cantidad' => $serie->producto->stock - 1,
            // ]);
            // $carshoop->descontarSerieCarshoop($serie->id);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ])->getData();
            return false;
            throw $e;
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ])->getData();
            return false;
            throw $e;
        }
    }
}
