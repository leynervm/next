<?php

namespace Modules\Ventas\Http\Controllers;

use App\Enums\PromocionesEnum;
use App\Models\Almacen;
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
use App\Models\Tvitem;
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
                $query->with(['kardexes.almacen', 'itemseries' => function ($subq) {
                    $subq->with(['serie.almacen']);
                }, 'producto' => function ($subq) {
                    $subq->withTrashed()->with(['unit', 'imagen', 'almacens', 'seriesdisponibles']);
                }, 'carshoopitems' => function ($subq) {
                    $subq->with(['kardexes.almacen', 'itempromo', 'producto' => function ($q) {
                        $q->with(['unit', 'imagen', 'almacens', 'seriesdisponibles']);
                    }, 'itemseries' => function ($subq) {
                        $subq->with(['serie.almacen']);
                    }]);
                }]);
            },
            'otheritems' => function ($query) {
                $query->with(['unit', 'user']);
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
                        $query->with(['itempromos.producto.almacens'])
                            ->combos()->availables()->disponibles();
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
                    $priceIGV = getPriceIGV($pricesale, $empresa->igv);
                    $pricesale = $priceIGV->price;
                    $igvsale = $priceIGV->igv;
                }

                // cantidad sin promocion xq supera stock disp. promocion
                // Ejm: lim=3, Outs=3, Cant=5, qtySO=(cant-disp)=5
                if ($cantidad == $qty_sin_oferta) {
                    $carshoop = Tvitem::with('kardexes')->ventas()->micart()
                        ->inCart($producto->id, Tvitem::NO_GRATUITO, Almacen::DISMINUIR_STOCK, $moneda_id, null)
                        ->first();

                    if (!empty($carshoop)) {
                        $carshoop->cantidad = $carshoop->cantidad + $cantidad;
                        $carshoop->pricebuy = $producto->pricebuy;
                        $carshoop->price = $pricesale;
                        $carshoop->igv = $igvsale;
                        $carshoop->subtotaligv = $carshoop->cantidad * $igvsale;
                        $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                        $carshoop->total = ($pricesale + $igvsale) * $carshoop->cantidad;
                        $carshoop->save();
                    } else {
                        $carshoop = Tvitem::create([
                            'date' => $date,
                            'cantidad' => $cantidad,
                            'pricebuy' => $producto->pricebuy,
                            'price' => $pricesale,
                            'igv' => $igvsale,
                            'subtotaligv' => $cantidad * $igvsale,
                            'subtotal' => $pricesale * $cantidad,
                            'total' => ($pricesale + $igvsale) * $cantidad,
                            'gratuito' => Tvitem::NO_GRATUITO,
                            'alterstock' => Almacen::DISMINUIR_STOCK,
                            'producto_id' => $producto->id,
                            'moneda_id' => $moneda_id,
                            'user_id' => auth()->user()->id,
                            'sucursal_id' => auth()->user()->sucursal_id,
                            'promocion_id' => null,
                            'tvitemable_type' => Venta::class,
                        ]);
                    }
                    // La funcion updateOrCreateKardex || updateOrCreateKardex tambien descuenta la promocion
                    $kardex = $carshoop->updateOrCreateKardex($almacen_id, $producto->stock, $cantidad);
                    $producto->descontarStockProducto($almacen_id, $cantidad);
                    if (!empty($serie)) {
                        $carshoop->registrarSalidaSerie($serie->id);
                    }
                } else {
                    // Ejm: lim=3, Outs=1, Cant=2, qtySO=(cant-disp)=0
                    $carshoop = Tvitem::with('kardexes')->ventas()->micart()
                        ->inCart($producto->id, Tvitem::NO_GRATUITO, Almacen::DISMINUIR_STOCK, $moneda_id, !empty($promocion) ? $promocion->id : null)
                        ->first();

                    $new_qty = $qty_sin_oferta > 0 ? $stock_disponible_promocion : $cantidad;

                    if (!empty($carshoop)) {
                        $carshoop->cantidad = $carshoop->cantidad + $new_qty;
                        $carshoop->pricebuy = $producto->pricebuy;
                        $carshoop->price = $pricesale;
                        $carshoop->igv = $igvsale;
                        $carshoop->subtotaligv = $carshoop->cantidad * $igvsale;
                        $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                        $carshoop->total = ($pricesale + $igvsale) * $carshoop->cantidad;
                        $carshoop->save();
                    } else {
                        $carshoop = Tvitem::create([
                            'date' => $date,
                            'cantidad' => $new_qty,
                            'pricebuy' => $producto->pricebuy,
                            'price' => $pricesale,
                            'igv' => $igvsale,
                            'subtotal' => $pricesale * $new_qty,
                            'subtotaligv' => $cantidad * $igvsale,
                            'total' => ($pricesale + $igvsale) * $new_qty,
                            'gratuito' => Tvitem::NO_GRATUITO,
                            'alterstock' => Almacen::DISMINUIR_STOCK,
                            'producto_id' => $producto->id,
                            'moneda_id' => $moneda_id,
                            'user_id' => auth()->user()->id,
                            'sucursal_id' => auth()->user()->sucursal_id,
                            'promocion_id' => !empty($promocion) ? $promocion->id : null,
                            'tvitemable_type' => Venta::class,
                        ]);
                    }
                    // La funcion updateOrCreateKardex tambien descuenta la promocion
                    $kardex = $carshoop->updateOrCreateKardex($almacen_id, $producto->stock, $new_qty);
                    $producto->descontarStockProducto($almacen_id, $new_qty);
                    if (!empty($serie)) {
                        $carshoop->registrarSalidaSerie($serie->id);
                    }

                    // Ejm: lim=3, Outs=0, Cant=5, qtySO=(cant-disp)=2
                    if ($qty_sin_oferta > 0) {
                        //Agregar con precio normal
                        $pricesale = $producto->getPrecioVentaDefault($pricetype);
                        if ($empresa->isAfectacionIGV()) {
                            $priceIGV = getPriceIGV($pricesale, $empresa->igv);
                            $pricesale = $priceIGV->price;
                            $igvsale = $priceIGV->igv;
                        }

                        $carshoop = Tvitem::with('kardexes')->ventas()->micart()
                            ->inCart($producto->id, Tvitem::NO_GRATUITO, Almacen::DISMINUIR_STOCK, $moneda_id, null)
                            ->first();

                        if (!empty($carshoop)) {
                            $carshoop->cantidad = $carshoop->cantidad + $qty_sin_oferta;
                            $carshoop->pricebuy = $producto->pricebuy;
                            $carshoop->price = $pricesale;
                            $carshoop->igv = $igvsale;
                            $carshoop->subtotaligv = $carshoop->cantidad * $igvsale;
                            $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                            $carshoop->total = ($pricesale + $igvsale) * $carshoop->cantidad;
                            $carshoop->save();
                        } else {
                            $carshoop = Tvitem::create([
                                'date' => $date,
                                'cantidad' => $qty_sin_oferta,
                                'pricebuy' => $producto->pricebuy,
                                'price' => $pricesale,
                                'igv' => $igvsale,
                                'subtotal' => $pricesale * $qty_sin_oferta,
                                'subtotaligv' => $qty_sin_oferta * $igvsale,
                                'total' => ($pricesale + $igvsale) * $qty_sin_oferta,
                                'gratuito' => Tvitem::NO_GRATUITO,
                                'alterstock' => Almacen::DISMINUIR_STOCK,
                                'producto_id' => $producto->id,
                                'moneda_id' => $moneda_id,
                                'user_id' => auth()->user()->id,
                                'sucursal_id' => auth()->user()->sucursal_id,
                                'promocion_id' => null,
                                'tvitemable_type' => Venta::class,
                            ]);
                        }
                        // La funcion updateOrCreateKardex tambien descuenta la promocion
                        $kardex = $carshoop->updateOrCreateKardex($almacen_id, $producto->stock, $qty_sin_oferta);
                        $producto->descontarStockProducto($almacen_id, $qty_sin_oferta);
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'mensaje' => 'AGREGADO AL CARRITO'
                ])->getData();
            } else {
                // sino envio producto_id es porque estoy agregando un combo
                $pricetype = null;
                $serie = null;
                $almacen_id =  $request->input('almacen_id');
                $pricetype_id =  $request->input('pricetype_id');
                $moneda_id =  $request->input('moneda_id');
                $date = now('America/Lima')->format('Y-m-d H:i:s');

                $promocion = Promocion::with(['itempromos.producto' => function ($query) {
                    $query->with(['unit', 'almacens', 'imagen']);
                }, 'producto' => function ($query) use ($almacen_id) {
                    $query->withCount(['almacens as stock' => function ($subq) use ($almacen_id) {
                        $subq->when($almacen_id, function ($q, $almacen_id) {
                            $q->where('almacen_id', $almacen_id);
                        })->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)'));
                    }]);

                    // $query->withCount(['almacens as stock' => function ($q) use ($almacen_id) {
                    //     if (!empty($almacen_id)) {
                    //         $q->where('almacen_id', $almacen_id);
                    //     }
                    //     $q->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)'));
                    // }]);
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
                    // 'almacen_id' => ['required', 'integer', 'min:1', 'exists:almacens,id'],
                    'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
                    'pricetype_id' => ['nullable', Rule::requiredIf($empresa->usarLista()), 'integer', 'min:1', 'exists:pricetypes,id'],
                ];

                if ($promocion->producto->isRequiredserie()) {
                    $rules['serie_id'] = ['required', 'integer', 'min:1', 'exists:series,id'];
                } else {
                    $rules['almacen_id'] = ['required', 'integer', 'min:1', 'exists:almacens,id'];
                }
                $attributes = [
                    'almacen_id' => 'almacén',
                    'serie_id' => 'serie',
                    'producto_id' => 'producto',
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

                if ($request->filled('serie_id')) {
                    $serie = Serie::find($request->input('serie_id'));
                    if (empty($serie) || !$serie->isDisponible()) {
                        return response()->json([
                            'error' => 'SERIE NO SE ENCUENTRA DISPONIBLE'
                        ])->getData();
                        return false;
                    }
                    $almacen_id = $serie->almacen_id;
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
                $combo = getAmountCombo($promocion, $pricetype, null /* $almacen_id */);

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
                $carshoop = Tvitem::with('kardexes')->ventas()->micart()
                    ->inCart($promocion->producto_id, Tvitem::NO_GRATUITO, Almacen::DISMINUIR_STOCK, $moneda_id, $promocion->id)
                    ->first();
                // return [$request->input(), $pricesale, getPriceIGV($pricesale, $empresa->igv)];
                if ($empresa->isAfectacionIGV()) {
                    $priceIGV = getPriceIGV($pricesale, $empresa->igv);
                    $pricesale = $priceIGV->price;
                    $igvsale = $priceIGV->igv;
                }

                if (!empty($carshoop)) {
                    $carshoop->cantidad = $carshoop->cantidad + 1;
                    $carshoop->pricebuy = $promocion->producto->pricebuy;
                    $carshoop->price = $pricesale;
                    $carshoop->igv = $igvsale;
                    $carshoop->subtotaligv = $carshoop->cantidad * $igvsale;
                    $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                    $carshoop->total = ($pricesale + $igvsale) * $carshoop->cantidad;
                    $carshoop->save();
                } else {
                    $carshoop = Tvitem::create([
                        'date' => $date,
                        'cantidad' => 1,
                        'pricebuy' => $promocion->producto->pricebuy,
                        'price' => $pricesale,
                        'igv' => $igvsale,
                        'subtotaligv' => $igvsale,
                        'subtotal' => $pricesale,
                        'total' => $pricesale + $igvsale,
                        'gratuito' => Tvitem::NO_GRATUITO,
                        'alterstock' => Almacen::DISMINUIR_STOCK,
                        'producto_id' => $promocion->producto_id,
                        'moneda_id' => $moneda_id,
                        'user_id' => auth()->user()->id,
                        'sucursal_id' => auth()->user()->sucursal_id,
                        'promocion_id' => $promocion->id,
                        'tvitemable_type' => Venta::class,
                    ]);
                }

                if (!empty($serie)) {
                    $itemserie = $carshoop->registrarSalidaSerie($serie->id);
                }

                // La funcion updateOrCreateKardex tambien descuenta la promocion
                $kardex = $carshoop->updateOrCreateKardex($almacen_id, $promocion->producto->stock, 1);
                $promocion->producto->descontarStockProducto($almacen_id, 1);

                foreach ($combo->products as $item) {
                    $priceitem = $item->price;
                    $igvitem = 0;
                    if ($empresa->isAfectacionIGV()) {
                        $priceIGV = getPriceIGV($priceitem, $empresa->igv);
                        $priceitem = $priceIGV->price;
                        $igvitem = $priceIGV->igv;
                    }

                    $carshoopitem = $carshoop->carshoopitems()->where('producto_id', $item->producto_id)->first();
                    if (!empty($carshoopitem)) {
                        $carshoopitem->cantidad = $carshoopitem->cantidad + 1;
                        $carshoopitem->price = $priceitem;
                        $carshoopitem->igv = $igvitem;
                        $carshoopitem->subtotaligv = $carshoopitem->cantidad * $igvitem;
                        $carshoopitem->subtotal = $carshoopitem->cantidad * $priceitem;
                        $carshoopitem->total = $carshoopitem->cantidad * ($priceitem + $igvitem);
                        $carshoopitem->save();
                    } else {
                        $carshoopitem = $carshoop->carshoopitems()->create([
                            'cantidad' => 1,
                            'pricebuy' => $item->pricebuy,
                            'price' => $priceitem,
                            'igv' => $igvitem,
                            'subtotaligv' => $igvitem,
                            'subtotal' => $priceitem,
                            'total' => $priceitem + $igvitem,
                            'itempromo_id' => $item->itempromo_id,
                            'producto_id' => $item->producto_id,
                        ]);
                    }

                    // No descuento stock ni genero kardex, porque prod. de combo 
                    // puedo sacarlos de otro almacen no necesariamente del almacen prod. principal
                    // $stockcarshoopitem = $carshoopitem->producto->almacens()->find($almacen_id)->pivot->cantidad;
                    // $kardex = $carshoopitem->updateOrCreateKardex($almacen_id, $stockcarshoopitem, 1);
                    // $carshoopitem->producto->descontarStockProducto($almacen_id, 1);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'mensaje' => 'COMBO AGREGADO !',
                    'promocion_id' => $promocion->id ?? null
                ])->getData();
                return false;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                // 'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'error' => 'eRROR:' . $e,
            ])->getData();
            // return false;
            // throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                // 'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'error' => 'eRROR:' . $e,
            ])->getData();
            // return false;
            // throw $e;
        }
    }

    public function getproductobyserie(Request $request)
    {
        // return
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
                        $subQuery->with(['itempromos.producto.almacens'])->availables()->disponibles();
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
                $serie->producto->descuento = $serie->producto->promocions->whereIn('type', [PromocionesEnum::DESCUENTO->value, PromocionesEnum::OFERTA->value])->first()->descuento ?? 0;
                $serie->producto->liquidacion = $serie->producto->promocions->where('type', PromocionesEnum::LIQUIDACION->value)->count() > 0 ? true : false;

                if ($serie->producto->descuento > 0 || $serie->producto->liquidacion) {
                    $promocion = $serie->producto->promocions->where('type', '<>', PromocionesEnum::COMBO->value)->first();
                    $promocion = verifyPromocion($promocion);
                }
            }

            if ($empresa->usarLista()) {
                $pricetype = Pricetype::find($request->input('pricetype_id'));
            }

            if ($combos) {
                $combosDisponibles = 0;
                foreach ($combos as $item) {
                    $combo = getAmountCombo($item, $pricetype, null/* $serie->almacen_id */);
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
                $priceIGV = getPriceIGV($pricesale, $empresa->igv);
                $pricesale = $priceIGV->price;
                $igvsale = $priceIGV->igv;
            }

            if ($cantidad == $qty_sin_oferta) {
                $carshoop = Tvitem::with('kardexes')->ventas()->micart()
                    ->inCart($serie->producto_id, Tvitem::NO_GRATUITO, Almacen::DISMINUIR_STOCK, $moneda_id, null)
                    ->first();

                if (!empty($carshoop)) {
                    $carshoop->cantidad = $carshoop->cantidad + $cantidad;
                    $carshoop->pricebuy = $serie->producto->pricebuy;
                    $carshoop->price = $pricesale;
                    $carshoop->igv = $igvsale;
                    $carshoop->subtotaligv = $carshoop->cantidad * $igvsale;
                    $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                    $carshoop->total = ($pricesale + $igvsale) * $carshoop->cantidad;
                    $carshoop->save();
                } else {
                    $carshoop = Tvitem::create([
                        'date' => $date,
                        'cantidad' =>  $cantidad,
                        'pricebuy' => $serie->producto->pricebuy,
                        'price' => $pricesale,
                        'igv' => $igvsale,
                        'subtotaligv' => $igvsale,
                        'subtotal' => $pricesale,
                        'total' => $pricesale + $igvsale,
                        'gratuito' => Tvitem::NO_GRATUITO,
                        'alterstock' => Almacen::DISMINUIR_STOCK,
                        'producto_id' => $serie->producto->id,
                        'moneda_id' => $moneda_id,
                        'user_id' => auth()->user()->id,
                        'sucursal_id' => auth()->user()->sucursal_id,
                        'promocion_id' => null,
                        'tvitemable_type' => Venta::class,
                    ]);
                }

                // La funcion updateOrCreateKardex tambien descuenta la promocion
                $kardex = $carshoop->updateOrCreateKardex($serie->almacen_id, $serie->producto->stock, $cantidad);
                $serie->producto->descontarStockProducto($serie->almacen_id, $cantidad);
                $carshoop->registrarSalidaSerie($serie->id);
            } else {
                // Ejm: lim=3, Outs=1, Cant=2, qtySO=(cant-disp)=0
                $carshoop = Tvitem::with('kardexes')->ventas()->micart()
                    ->inCart($serie->producto_id, Tvitem::NO_GRATUITO, Almacen::DISMINUIR_STOCK, $moneda_id, !empty($promocion) ? $promocion->id : null)
                    ->first();
                $new_qty = $qty_sin_oferta > 0 ? $stock_disponible_promocion : $cantidad;

                if (!empty($carshoop)) {
                    $carshoop->cantidad = $carshoop->cantidad + $new_qty;
                    $carshoop->pricebuy = $serie->producto->pricebuy;
                    $carshoop->price = $pricesale;
                    $carshoop->igv = $igvsale;
                    $carshoop->subtotaligv = $carshoop->cantidad * $igvsale;
                    $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                    $carshoop->total = ($pricesale + $igvsale) * $carshoop->cantidad;
                    $carshoop->save();
                } else {
                    $carshoop = Tvitem::create([
                        'date' => $date,
                        'cantidad' =>  $new_qty,
                        'pricebuy' => $serie->producto->pricebuy,
                        'price' => $pricesale,
                        'igv' => $igvsale,
                        'subtotaligv' => $igvsale,
                        'subtotal' => $pricesale,
                        'total' => $pricesale + $igvsale,
                        'gratuito' => Tvitem::NO_GRATUITO,
                        'alterstock' => Almacen::DISMINUIR_STOCK,
                        'producto_id' => $serie->producto->id,
                        'moneda_id' => $moneda_id,
                        'user_id' => auth()->user()->id,
                        'sucursal_id' => auth()->user()->sucursal_id,
                        'promocion_id' => !empty($promocion) ? $promocion->id : null,
                        'tvitemable_type' => Venta::class,
                    ]);
                }
                // La funcion updateOrCreateKardex tambien descuenta la promocion
                $kardex = $carshoop->updateOrCreateKardex($serie->almacen_id, $serie->producto->stock, $new_qty);
                $serie->producto->descontarStockProducto($serie->almacen_id, $new_qty);
                $carshoop->registrarSalidaSerie($serie->id);

                // Ejm: lim=3, Outs=0, Cant=5, qtySO=(cant-disp)=2
                if ($qty_sin_oferta > 0) {
                    //Agregar con precio normal
                    $pricesale = $serie->producto->getPrecioVentaDefault($pricetype);
                    if ($empresa->isAfectacionIGV()) {
                        $priceIGV = getPriceIGV($pricesale, $empresa->igv);
                        $pricesale = $priceIGV->price;
                        $igvsale = $priceIGV->igv;
                    }

                    $carshoop = Tvitem::with('kardexes')->ventas()->micart()
                        ->inCart($serie->producto_id, Tvitem::NO_GRATUITO, Almacen::DISMINUIR_STOCK, $moneda_id, null)
                        ->first();

                    if (!empty($carshoop)) {
                        $carshoop->cantidad = $carshoop->cantidad + $qty_sin_oferta;
                        $carshoop->pricebuy = $serie->producto->pricebuy;
                        $carshoop->price = $pricesale;
                        $carshoop->igv = $igvsale;
                        $carshoop->subtotaligv = $carshoop->cantidad * $igvsale;
                        $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                        $carshoop->total = ($pricesale + $igvsale) * $carshoop->cantidad;
                        $carshoop->save();
                    } else {
                        $carshoop = Tvitem::create([
                            'date' => $date,
                            'cantidad' =>  $qty_sin_oferta,
                            'pricebuy' => $serie->producto->pricebuy,
                            'price' => $pricesale,
                            'igv' => $igvsale,
                            'subtotaligv' => $igvsale,
                            'subtotal' => $pricesale,
                            'total' => $pricesale + $igvsale,
                            'gratuito' => Tvitem::NO_GRATUITO,
                            'alterstock' => Almacen::DISMINUIR_STOCK,
                            'producto_id' => $serie->producto->id,
                            'moneda_id' => $moneda_id,
                            'user_id' => auth()->user()->id,
                            'sucursal_id' => auth()->user()->sucursal_id,
                            'promocion_id' => null,
                            'tvitemable_type' => Venta::class,
                        ]);
                    }
                    // La funcion updateOrCreateKardex tambien descuenta la promocion
                    $kardex = $carshoop->updateOrCreateKardex($serie->almacen_id, $serie->producto->stock, $qty_sin_oferta);
                    $serie->producto->descontarStockProducto($serie->almacen_id, $qty_sin_oferta);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'mensaje' => 'AGREGADO AL CARRITO',
            ])->getData();
            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ])->getData();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ])->getData();
        }
    }

    public function updatemoneda(Request $request)
    {
        $moneda_id = $request->get('moneda_id');
        $empresa = view()->shared('empresa');

        try {
            DB::beginTransaction();
            $tvitems = Tvitem::with(['moneda', 'promocion', 'producto', 'carshoopitems'])
                ->where('moneda_id', '<>', $moneda_id)
                ->ventas()->micart()->orderBy('id', 'asc')->get();

            if (count($tvitems) > 0) {
                foreach ($tvitems as $item) {
                    $pricesale = number_format($item->price + $item->igv, 3, '.', '');
                    $igvsale = 0;

                    if ($empresa->isAfectacionIGV()) {
                        $priceIGV = getPriceIGV($pricesale, $empresa->igv);
                        $pricesale = number_format($priceIGV->price, 3, '.', '');
                        $igvsale = number_format($priceIGV->igv, 3, '.', '');
                    }

                    Self::updatemonedaitem($item, $item->moneda, $pricesale, $igvsale, $moneda_id);

                    if (count($item->carshoopitems) > 0) {
                        foreach ($item->carshoopitems as $carshoopitem) {
                            $pricesaleitem = number_format($carshoopitem->price + $carshoopitem->igv, 3, '.', '');
                            $igvsaleitem = 0;

                            if ($empresa->isAfectacionIGV()) {
                                $priceIGV = getPriceIGV($pricesaleitem, $empresa->igv);
                                $pricesaleitem = number_format($priceIGV->price, 3, '.', '');
                                $igvsaleitem = number_format($priceIGV->igv, 3, '.', '');
                            }

                            Self::updatemonedaitem($carshoopitem, $item->moneda, $pricesaleitem, $igvsaleitem, $moneda_id, false);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'mensaje' => 'CARRITO ACTUALIZADO',
                // 'totales'   => self::total(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => $e->getMessage(),
                'error' => $e,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => $e->getMessage(),
                'error' => $e,
            ]);
        }
    }

    // public function total()
    // {
    //     $sumatorias = Tvitem::select(
    //         DB::raw("COALESCE(SUM(total),0) as total"),
    //         DB::raw("COALESCE(SUM(CASE WHEN igv > 0 AND gratuito = '0' THEN igv * cantidad ELSE 0 END),0) as igv"),
    //         DB::raw("COALESCE(SUM(CASE WHEN igv > 0 AND gratuito = '1' THEN igv * cantidad ELSE 0 END), 0) as igvgratuito"),
    //         DB::raw("COALESCE(SUM(CASE WHEN igv > 0 AND gratuito = '0' THEN price * cantidad ELSE 0 END), 0) as gravado"),
    //         DB::raw("COALESCE(SUM(CASE WHEN igv = 0 AND gratuito = '0' THEN price * cantidad ELSE 0 END), 0) as exonerado"),
    //         DB::raw("COALESCE(SUM(CASE WHEN gratuito = '1' THEN price * cantidad ELSE 0 END), 0) as gratuito")
    //     )->ventas()->micart()->get();
    //     return  $sumatorias[0];
    // }

    private function updatemonedaitem($model, $moneda, $pricesale, $igvsale, $moneda_id, $istvitem = true)
    {
        $empresa = view()->shared('empresa');
        // Moneda actual del tvitem
        if ($moneda->isDolar()) {
            $pricesale = convertMoneda($pricesale, 'PEN', $empresa->tipocambio ?? 1, 2);
            if ($igvsale > 0) {
                $igvsale = convertMoneda($igvsale, 'PEN', $empresa->tipocambio ?? 1, 2);
            }
        } else {
            $pricesale = convertMoneda($pricesale, 'USD', $empresa->tipocambio ?? 1, 3);
            if ($igvsale > 0) {
                $igvsale = convertMoneda($igvsale, 'USD', $empresa->tipocambio ?? 1, 3);
            }
        }

        $data = [
            'price' => $pricesale,
            'igv' => $igvsale,
            'subtotaligv' => number_format($igvsale * $model->cantidad, 3, '.', ''),
            'subtotal' => number_format($pricesale * $model->cantidad, 3, '.', ''),
            'total' => number_format(($pricesale + $igvsale) * $model->cantidad, 3, '.', ''),
        ];
        if ($istvitem) {
            $data['moneda_id'] = $moneda_id; //Moneda nueva a actualizar
        }
        $model->update($data);
    }
}
