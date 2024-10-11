<?php

namespace Modules\Marketplace\Http\Controllers;

use App\Enums\MethodPaymentOnlineEnum;
use App\Enums\StatusPayWebEnum;
use App\Models\Category;
use App\Models\Moneda;
use App\Models\Producto;
use App\Models\Sucursal;
use Gloudemans\Shoppingcart\Facades\Cart;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Modules\Marketplace\Entities\Order;
use Modules\Marketplace\Entities\Shipmenttype;
use Nwidart\Modules\Routing\Controller;

class MarketplaceController extends Controller
{


    public function __construct()
    {
        $this->middleware('verifyproductocarshoop')->only(['create', 'productos', 'showproducto', 'carshoop', 'wishlist']);
        $this->middleware('can:admin.almacen.caracteristicas')->only('caracteristicas');
        $this->middleware('permission:admin.marketplace.orders|admin.marketplace.transacciones|admin.marketplace.userweb|admin.marketplace.trackingstates|admin.marketplace.shipmenttypes|admin.marketplace.sliders')->only('index');
        $this->middleware('can:admin.marketplace.sliders')->only('sliders');
        $this->middleware('can:admin.marketplace.shipmenttypes')->only('shipmenttypes');
        $this->middleware('can:admin.marketplace.transacciones')->only('transacciones');
        $this->middleware('can:admin.marketplace.userweb')->only('usersweb');
        $this->middleware('can:admin.marketplace.trackingstates')->only('trackingstates');
    }

    public function index()
    {
        return view('marketplace::index');
    }


    public function caracteristicas()
    {
        return view('marketplace::caracteristicas.index');
    }

    public function ofertas()
    {
        $ofertas = Producto::query()->select('id', 'name', 'slug', 'marca_id', 'subcategory_id', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5')
            ->addSelect(['image' => function ($query) {
                $query->select('url')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('default', 'desc')->limit(1);
            }])->withCount(['almacens as stock' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
            }])->whereHas('promocions', function ($query) {
                $query->disponibles();
            })->with(['marca', 'promocions' => function ($query) {
                $query->with(['itempromos.producto' => function ($itemQuery) {
                    $itemQuery->with('unit')->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderBy('default', 'desc')->limit(1);
                    }]);
                }])->disponibles();
            }])->publicados()->visibles()->orderBy('views', 'desc')
            ->orderBy('name', 'asc')->paginate(30)->through(function ($producto) {
                $producto->promocion = $producto->promocions->first();
                return $producto;
            });

        return view('modules.marketplace.productos.ofertas', compact('ofertas'));
    }

    public function orders()
    {
        return view('modules.marketplace.orders.index');
    }

    public function create()
    {
        return view('modules.marketplace.orders.create');
    }

    public function generateSessionToken($order)
    {
        $auth = base64_encode(config('services.niubiz.user') . ':' . config('services.niubiz.password'));
        $accessToken = Http::withHeaders([
            'Authorization' => "Basic $auth",
            'Content-Type' => "application/json",
        ])->get(config('services.niubiz.url_api') . 'api.security/v1/security')->body();


        $sessionToken = Http::withHeaders([
            'Authorization' => $accessToken,
            'Content-Type' => "application/json",
        ])->post(config('services.niubiz.url_api') . 'api.ecommerce/v2/ecommerce/token/session/' . config('services.niubiz.merchant_id'), [
            'channel' => 'web',
            'amount' => formatDecimalOrInteger($order->total),
            'antifraud' => [
                'clientIp' => request()->ip(),
                'merchantDefineData' => [
                    'MDD4' => auth()->user()->email,
                    'MDD21' => 0,
                    'MDD32' => auth()->id(),
                    'MDD75' => 'Registrado',
                    'MDD77' => now('America/Lima')->diffInDays(auth()->user()->created_at) + 1,
                ],
            ],
            // 'dataMap' => [
            //     'cardholderCity' => ,
            // 'cardholderCountry' => ,
            // 'cardholderAddress' => ,
            // 'cardholderPostalCode' => ,
            // 'cardholderState' => ,
            // 'cardholderPhoneNumber' => ,
            // ],
        ])->json();

        return $sessionToken['sessionKey'];
    }

    public function payment(Order $order)
    {
        $this->authorize('user', $order);
        $order = Order::with('transaccion')->find($order->id);
        $session_token = $this->generateSessionToken($order);
        return view('modules.marketplace.orders.payment', compact('order', 'session_token'));
    }

    public function deposito(Request $request, Order $order,)
    {
        $this->authorize('user', $order);
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png|max:12288'
        ]);

        DB::beginTransaction();
        try {
            if ($request->file('file')) {

                $compressedImage = Image::make($request->file('file')->getRealPath())
                    ->resize(1000, 1000, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->orientate()->encode('jpg', 100);

                $url = uniqid() . '.' . $request->file('file')->getClientOriginalExtension();
                $compressedImage->save(public_path('storage/payments/depositos/' . $url));

                if ($compressedImage->filesize() > 1048576) { //10MB
                    $compressedImage->destroy();
                    return redirect()->back()->withErrors([
                        'file' => 'La imagen excede el tamaño máximo permitido.'
                    ])->withInput();
                }

                // $url = Storage::put('payments/depositos', $request->file('file'));
                $order->image()->create(['url' => $url]);
            }

            $order->methodpay = MethodPaymentOnlineEnum::DEPOSITO_BANCARIO->value;
            $order->status = StatusPayWebEnum::CONFIRMAR_PAGO;
            $order->save();
            DB::commit();
            return redirect()->route('orders.payment', $order)->with('info', 'Pago de orden registrado correctamente');;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function productos(Request $request)
    {
        return view('modules.marketplace.productos.index');
    }

    public function showproducto(Producto $producto)
    {
        // $stocksucursalsarray = DB::select("SELECT 
        // SUCURSALS.name,SUCURSALS.direccion,CONCAT(UBIGEOS.region , ', ',UBIGEOS.provincia, ', ', UBIGEOS.distrito )as lugar, sum(cantidad) as TOTAL 
        // FROM ALMACEN_SUCURSAL
        // INNER JOIN ALMACEN_PRODUCTO ON ALMACEN_PRODUCTO.almacen_id = ALMACEN_SUCURSAL.almacen_id
        // INNER JOIN SUCURSALS ON SUCURSALS.id = ALMACEN_SUCURSAL.sucursal_id
        // INNER JOIN UBIGEOS ON SUCURSALS.ubigeo_id = UBIGEOS.id
        // WHERE PRODUCTO_ID = ? GROUP BY name,direccion,lugar", [$producto->id]) ?? [];
        $this->authorize('publicado', $producto);
        // $stocksucursals = DB::table('almacen_sucursal')
        //     ->join('almacen_producto', 'almacen_producto.almacen_id', '=', 'almacen_sucursal.almacen_id')
        //     ->join('sucursals', 'sucursals.id', '=', 'almacen_sucursal.sucursal_id')
        //     ->join('ubigeos', 'sucursals.ubigeo_id', '=', 'ubigeos.id')
        //     ->select(
        //         'sucursals.name',
        //         'sucursals.direccion',
        //         DB::raw("CONCAT(ubigeos.region , ', ',ubigeos.provincia, ', ', ubigeos.distrito ) as lugar"),
        //         DB::raw('SUM(cantidad) as total')
        //     )
        //     ->where('almacen_producto.producto_id', $producto->id)
        //     ->groupBy('sucursals.name', 'sucursals.direccion', 'lugar')
        //     ->get();

        // $empresa = mi_empresa();
        // $pricetype = getPricetypeAuth($empresa);
        $shipmenttypes = Shipmenttype::get();
        $producto->views = $producto->views + 1;
        $producto->save();

        $relacionados = Producto::query()->select('id', 'name', 'slug', 'marca_id', 'subcategory_id', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5')
            ->addSelect(['image' => function ($query) {
                $query->select('url')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('default', 'desc')->limit(1);
            }])->with(['marca', 'promocions' => function ($query) {
                $query->with(['itempromos.producto' => function ($query) {
                    $query->with('unit')->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderBy('default', 'desc')->limit(1);
                    }]);
                }])->disponibles()->take(1);
            }])
            ->whereNot('id', $producto->id)->where('subcategory_id', $producto->subcategory_id)
            ->publicados()->visibles()->take(28)->orderBy('views', 'desc')
            ->orderBy('name', 'asc')->get()->map(function ($producto) {
                $producto->promocion = $producto->promocions->first();
                return $producto;
            });
        // dd($relacionados);
        $interesantes = Producto::query()->select('id', 'name', 'slug', 'marca_id', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5')
            ->addSelect(['image' => function ($query) {
                $query->select('url')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('default', 'desc')->limit(1);
            }])->with(['marca', 'promocions' => function ($query) {
                $query->with(['itempromos.producto' => function ($query) {
                    $query->with('unit')->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderBy('default', 'desc')->limit(1);
                    }]);
                }])->disponibles()->take(1);
            }])
            ->whereNot('id', $producto->id)->publicados()->visibles()
            ->inRandomOrder()->take(28)->orderBy('views', 'desc')
            ->orderBy('name', 'asc')->get()->map(function ($producto) {
                $producto->promocion = $producto->promocions->first();
                return $producto;
            });

        $producto->load([
            'marca',
            'category' => function ($query) {
                $query->select('id', 'name', 'slug');
            },
            'subcategory',
            'especificacions.caracteristica',
            'detalleproducto',
            'garantiaproductos.typegarantia',
            'images' => function ($query) {
                $query->orderBy('default', 'desc');
            },
            'promocions' => function ($query) {
                $query->with(['itempromos.producto' => function ($query) {
                    $query->with('unit')->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderBy('default', 'desc')->limit(1);
                    }]);
                }])->disponibles()->take(1);
            },
            'almacens' => function ($query) {
                $query->wherePivot('cantidad', '>', 0);
            }
        ])->loadCount(['almacens as stock' => function ($query) {
            $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
        }]);

        return view('modules.marketplace.productos.show', compact('producto', 'shipmenttypes', 'relacionados', 'interesantes'));
    }

    public function carshoop()
    {
        return view('marketplace::carrito');
    }

    public function wishlist()
    {
        $countwish = 0;
        if (Cart::instance('wishlist')->count() > 0) {
            foreach (Cart::instance('wishlist')->content() as $item) {
                if (is_null($item->model)) {
                    Cart::instance('wishlist')->get($item->rowId);
                    Cart::instance('wishlist')->remove($item->rowId);
                    $countwish++;
                }
            }

            if ($countwish > 0) {
                if (auth()->check()) {
                    Cart::instance('wishlist')->store(auth()->id());
                }

                $mensaje = response()->json([
                    'title' => "ALGUNOS PRODUCTOS FUERON REMOVIDOS DEL CARRITO.",
                    'text' => 'Carrito de compras actualizado, algunos productos han dejado de estar disponibles en tienda web.',
                    'type' => 'warning'
                ])->getData();
                session()->now('message', $mensaje);
            }
        }
        return view('marketplace::wishlist');
    }

    public function profile()
    {
        return view('marketplace::profile');
    }

    public function trackingstates()
    {
        return view('marketplace::admin.trackingstates.index');
    }

    public function transacciones()
    {
        return view('marketplace::admin.transacciones.index');
    }

    public function shipmenttypes()
    {
        return view('marketplace::admin.shipmenttypes.index');
    }

    public function usersweb()
    {
        return view('marketplace::admin.usersweb.index');
    }

    public function sliders()
    {
        return view('marketplace::admin.sliders.index');
    }

    public function nosotros()
    {
        return view('partials.nosotros');
    }

    public function contactanos()
    {
        return view('partials.contactanos');
    }

    public function centroautorizado()
    {
        return view('partials.centro-autorizado');
    }

    public function ubicanos()
    {
        $sucursals = Sucursal::orderBy('codeanexo')->get();
        return view('partials.ubicanos', compact('sucursals'));
    }

    public function trabaja()
    {
        return view('partials.trabaja-nosotros');
    }

    public function search(Request $request)
    {

        $search = $request->input('search');
        $products = Producto::query()->select('id', 'name', 'slug', 'marca_id')
            ->with(['images' => function ($query) {
                $query->default();
            }])->with('marca');

        if (strlen(trim($search)) < 2) {
            return response()->json([]);
        }

        $searchTerms = explode(' ', $search);
        $products->where(function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query->orWhere('name', 'ilike', '%' . $term . '%')
                    ->orWhereHas('marca', function ($q) use ($term) {
                        $q->whereNull('deleted_at')->where('name', 'ilike', '%' . $term . '%');
                    })
                    ->orWhereHas('category', function ($q) use ($term) {
                        $q->whereNull('deleted_at')->where('name', 'ilike', '%' . $term . '%');
                    })
                    ->orWhereHas('especificacions', function ($q) use ($term) {
                        $q->where('especificacions.name', 'ilike', '%' . $term . '%');
                    });
            }
        })->visibles()->publicados()->orderBy('name', 'asc')->limit(10);

        return response()->json($products->get());
    }

    public function searchsubcategories(Request $request)
    {
        $category_id = $request->input('category_id');
        $subcategories = Category::find($category_id)->subcategories()->orderBy('name', 'asc')->get();
        return response()->json($subcategories);
    }
}
