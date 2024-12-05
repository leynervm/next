<?php

namespace Modules\Ventas\Http\Controllers;

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
use App\Models\Subcategory;
use App\Models\Typepayment;
use App\Models\Ubigeo;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
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
                $query->with(['itemseries.serie', 'almacen', 'producto' => function ($subQuery) {
                    $subQuery->with('unit')->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderBy('default', 'desc')->limit(1);
                    }]);
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


    public function data(Request $request)
    {
        $search = $request->input('search');
        $products = Producto::query()->select('id', 'name', 'slug');

        if (strlen(trim($search)) < 2) {
            $products->visibles()->orderBy('name', 'asc');
        } else {
            if (trim($search) !== '') {
                $searchTerms = explode(' ', $search);
                $products->where(function ($query) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $query->orWhere('name', 'ilike', '%' . $term . '%');
                    }
                })->visibles()->orderBy('name', 'asc')->limit(10);
            }
        }

        $products = $products->get()->map(function ($producto) {
            return [
                'value' => $producto->id,
                'text' => $producto->name,
            ];
        });

        return response()->json($products);
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
}
