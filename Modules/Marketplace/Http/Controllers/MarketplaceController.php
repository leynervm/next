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
                $query->availables()->disponibles();
            })->with(['marca', 'promocions' => function ($query) {
                $query->with(['itempromos.producto' => function ($itemQuery) {
                    $itemQuery->with('unit')->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderBy('default', 'desc')->limit(1);
                    }]);
                }])->availables()->disponibles();
            }])->publicados()->visibles()->orderBy('views', 'desc')
            ->orderBy('name', 'asc')->paginate(30)->through(function ($producto) {
                $producto->promocion = $producto->promocions->first();
                return $producto;
            });
        $pricetype = getPricetypeAuth();

        return view('modules.marketplace.productos.ofertas', compact('ofertas', 'pricetype'));
    }

    public function orders()
    {
        return view('modules.marketplace.orders.index');
    }

    public function create()
    {
        $pricetype = getPricetypeAuth();
        return view('modules.marketplace.orders.create', compact('pricetype'));
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
            'amount' => decimalOrInteger($order->total),
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
        $order->load(['tvitems' => function ($query) {
            $query->with(['producto.unit']);
        }, 'transaccion', 'trackings' => function ($query) {
            $query->with('trackingstate')->orderBy('date', 'asc');
        }]);
        return view('modules.marketplace.orders.payment', compact('order'));
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
        $pricetype = getPricetypeAuth();
        return view('modules.marketplace.productos.index', compact('pricetype'));
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
        $pricetype = getPricetypeAuth();
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
        $shipmenttypes = Shipmenttype::get();
        $producto->views = $producto->views + 1;
        $producto->save();

        $relacionados = Producto::query()->select('id', 'name', 'slug', 'modelo', 'sku', 'partnumber', 'marca_id', 'subcategory_id', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5')
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
                }])->availables()->disponibles();
            }])->whereNot('id', $producto->id)->where('subcategory_id', $producto->subcategory_id)
            ->publicados()->visibles()->take(28)->orderBy('views', 'desc')
            ->orderBy('name', 'asc')->get()->map(function ($producto) {
                $producto->promocion = $producto->promocions->first();
                return $producto;
            });
        // dd($relacionados);
        $interesantes = Producto::query()->select('id', 'name', 'slug', 'marca_id', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5')
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
                }])->availables()->disponibles();
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
                $query->with(['itempromos.producto' => function ($subQuery) {
                    $subQuery->with('unit')->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderBy('default', 'desc')->limit(1);
                    }]);
                }])->availables()->disponibles()->take(1);
            },
            'almacens' => function ($query) {
                $query->wherePivot('cantidad', '>', 0);
            }
        ])->loadCount(['almacens as stock' => function ($query) {
            $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
        }]);

        return view('modules.marketplace.productos.show', compact('producto', 'shipmenttypes', 'relacionados', 'interesantes', 'pricetype'));
    }

    public function carshoop()
    {
        $pricetype = getPricetypeAuth();
        return view('marketplace::carrito', compact('pricetype'));
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
        return view('modules.marketplace.nosotros');
    }

    public function contactanos()
    {
        return view('modules.marketplace.contactanos');
    }

    public function centroautorizado()
    {
        return view('modules.marketplace.centro-autorizado');
    }

    public function ubicanos()
    {
        $sucursals = Sucursal::orderBy('codeanexo')->get();
        return view('modules.marketplace.ubicanos', compact('sucursals'));
    }

    public function trabaja()
    {
        return view('modules.marketplace.trabaja-nosotros');
    }

    public function tic()
    {
        $desarrollo = [
            'image' =>   asset('images/home/recursos/recurso_5.jpg'),
            'content' => [
                [
                    'title' => 'DevOps',
                    'description' => 'Implementamos procesos a través de la configuración de software para la gestión automatizada, pruebas de regresión, otros sistemas software y la nube que impulsan soluciones competitivas en el mercado.',
                    'url' => asset('images/home/recursos/recurso_6.jpg'),
                ],
                [
                    'title' => 'Ingeniería de Calidad',
                    'description' => 'Maximizamos la estabilidad de las aplicaciones y optimizamos el tiempo de las pruebas y los costos. Al mismo tiempo que incluimos pruebas unitarias, funcionales, integradas a los sistemas de rendimiento, de seguridad y de aceptación del usuario. El alcance de la automatización inteligente comprende de áreas móviles, web, APIs y servicios.',
                    'url' => asset('images/home/recursos/recurso_6.jpg'),
                ]
            ]
        ];
        $soporte = [
            'image' =>   asset('images/home/recursos/recurso_5.jpg'),
            'content' => [
                [
                    'title' => 'Cambio de Pantalla',
                    'description' => 'Devuélvele la claridad a tu dispositivo: cambio de pantalla para que disfrutes cada detalle de nuevo.',
                    'url' => asset('images/home/recursos/recurso_3.jpg'),
                ],
                [
                    'title' => 'Repotenciamos tu case',
                    'description' => 'Repotenciamos tu case para que luzca y rinda como nuevo, llevando tu tecnología al siguiente nivel.',
                    'url' => asset('images/home/recursos/reparacion_hw_sw.jpg'),
                ],
                [
                    'title' => 'Cambio de teclado',
                    'description' => 'Renueva tu experiencia de escritura: cambia tu teclado y siente la diferencia en cada pulsación.',
                    'url' => asset('images/home/recursos/recurso_3.jpg'),
                ],
                [
                    'title' => 'Instalación de programas de ingeniería',
                    'description' => 'Instala tus herramientas de ingeniería y transforma ideas en soluciones innovadoras.',
                    'url' => asset('images/home/recursos/recurso_3.jpg'),
                ],
                [
                    'title' => 'Soporte técnico de impresora',
                    'description' => 'Estamos aquí para asegurar que tu impresión fluya sin problemas: ¡tu soporte técnico de confianza para impresoras!.',
                    'url' => asset('images/home/recursos/recurso_3.jpg'),
                ],
                [
                    'title' => 'Reparación de case',
                    'description' => 'Devuelve la vida a tu equipo: reparación de cases para que tu tecnología siempre esté protegida y lista para rendir.',
                    'url' => asset('images/home/recursos/recurso_3.jpg'),
                ],
            ]
        ];
        $seguridad = [
            'image' =>   asset('images/home/recursos/recurso_5.jpg'),
            'content' => [
                [
                    'title' => 'Cámaras de vigilancia (CCTV)',
                    'description' => 'Las cámaras de vigilancia (CCTV) son tus ojos en todo momento, asegurando la seguridad y protección de tus espacios.',
                    'url' => asset('images/home/recursos/recurso_2.jpg'),
                ],
                [
                    'title' => 'Alarmas de intrusión',
                    'description' => 'Las alarmas de intrusión son tu primera línea de defensa, alertando sobre cualquier acceso no autorizado y manteniendo tu hogar o negocio a salvo.',
                    'url' => asset('images/home/recursos/recurso_2.jpg'),
                ],
                [
                    'title' => 'Control de acceso',
                    'description' => 'El control de acceso garantiza que solo las personas autorizadas ingresen a áreas sensibles, protegiendo tu seguridad y la de tus bienes.',
                    'url' => asset('images/home/recursos/recurso_2.jpg'),
                ],
                [
                    'title' => 'Sensores de humo y fuego',
                    'description' => 'Los sensores de humo y fuego son guardianes silenciosos que salvan vidas al detectar peligros a tiempo.',
                    'url' => asset('images/home/recursos/recurso_2.jpg'),
                ],
                [
                    'title' => 'Sistema de videovigilancia en la nube',
                    'description' => 'El sistema de videovigilancia en la nube te ofrece seguridad y acceso instantáneo a tus grabaciones desde cualquier lugar.',
                    'url' => asset('images/home/recursos/recurso_2.jpg'),
                ],
                [
                    'title' => 'Sensores de movimiento',
                    'description' => 'Los sensores de movimiento son la primera alerta ante intrusos, protegiendo tus espacios con precisión.',
                    'url' => asset('images/home/recursos/recurso_2.jpg'),
                ],
            ]
        ];
        $datacenter = [
            'image' => asset('images/home/recursos/recurso_5.jpg'),
            'content' => []
        ];
        $redes = [
            'image' =>   asset('images/home/recursos/recurso_5.jpg'),
            'content' => [
                [
                    'title' => 'Conectividad a Internet',
                    'description' => 'Ofrecemos acceso a internet de alta velocidad con opciones de redundancia para una conexión ininterrumpida.',
                    'url' => asset('images/home/recursos/recurso_2.jpg'),
                ],
                [
                    'title' => 'Redes Privadas Virtuales (VPN)',
                    'description' => 'Conéctate de forma segura a tu red corporativa desde cualquier lugar con nuestras soluciones VPN.',
                    'url' => asset('images/home/recursos/recurso_2.jpg'),
                ],
                [
                    'title' => 'Servicios de Telefonía IP',
                    'description' => 'Reduce costos y mejora la comunicación con nuestras soluciones de telefonía VoIP avanzadas.',
                    'url' => asset('images/home/recursos/recurso_2.jpg'),
                ],
                [
                    'title' => 'Gestión de Redes',
                    'description' => 'Monitorea y optimiza tu red con nuestros servicios de gestión integral y soporte técnico.',
                    'url' => asset('images/home/recursos/recurso_2.jpg'),
                ],
                [
                    'title' => 'Instalación y Mantenimiento de Infraestructura de Red',
                    'description' => 'Diseñamos e implementamos la infraestructura de red perfecta para tus necesidades, con soporte continuo.',
                    'url' => asset('images/home/recursos/recurso_2.jpg'),
                ],
                [
                    'title' => 'Servicios de Seguridad de Redes',
                    'description' => 'Protege tu red y datos con nuestras soluciones avanzadas de ciberseguridad y monitoreo constante.',
                    'url' => asset('images/home/recursos/recurso_2.jpg'),
                ],
            ]
        ];
        $electricidad = [
            'image' =>   asset('images/home/recursos/recurso_5.jpg'),
            'content' => [
                [
                    'title' => 'Instalación de Iluminación LED',
                    'description' => 'Implementación de soluciones de iluminación LED para mejorar la eficiencia energética y reducir costos de electricidad.',
                    'url' => asset('images/home/recursos/recurso_6.jpg'),
                ],
                [
                    'title' => 'Control de Climatización Inteligente',
                    'description' => 'Instalación de sistemas de control inteligente para aire acondicionado, permitiendo la programación y el control remoto de la temperatura.',
                    'url' => asset('images/home/recursos/recurso_6.jpg'),
                ],
                [
                    'title' => 'Actualización de Paneles Eléctricos',
                    'description' => 'Modernización de paneles eléctricos para mejorar la capacidad y seguridad de la instalación eléctrica.',
                    'url' => asset('images/home/recursos/recurso_6.jpg'),
                ],
                [
                    'title' => 'Inspección de Seguridad Eléctrica',
                    'description' => 'Evaluaciones detalladas de sistemas eléctricos para identificar riesgos y garantizar el cumplimiento de las normativas de seguridad.',
                    'url' => asset('images/home/recursos/recurso_6.jpg'),
                ],
                [
                    'title' => 'Deshumidificación y Purificación de Aire',
                    'description' => 'Instalación de sistemas adicionales para deshumidificar y purificar el aire en espacios cerrados, mejorando la calidad del aire interior.',
                    'url' => asset('images/home/recursos/recurso_6.jpg'),
                ],
                [
                    'title' => 'Asesoramiento Energético',
                    'description' => 'Consultoría sobre soluciones energéticas sostenibles y eficientes, ayudando a los clientes a tomar decisiones informadas sobre sus sistemas eléctricos y de aire acondicionado.',
                    'url' => asset('images/home/recursos/recurso_6.jpg'),
                ]
            ]
        ];

        $data = response()->json([
            'image' => asset('images/home/recursos/tic.jpg'),
            'desarrollo' => $desarrollo,
            'soporte' => $soporte,
            'seguridad' => $seguridad,
            'datacenter' => $datacenter,
            'redes' => $redes,
            'electricidad' => $electricidad,
        ])->getData();

        return view('modules.marketplace.tic', ['data' => $data]);
    }

    public function search(Request $request)
    {
        $empresa = view()->shared('empresa');
        $search = $request->input('search');
        if (strlen(trim($search)) < 2) {
            return response()->json([]);
        }
        $products =  Producto::query()->select(
            'productos.id',
            'productos.name',
            'productos.slug',
            'marca_id',
            DB::raw(
                "ts_rank(to_tsvector('spanish', 
                    COALESCE(productos.name, '') || ' ' || 
                    COALESCE(marcas.name, '') || ' ' || 
                    COALESCE(categories.name, '')
                ), plainto_tsquery('spanish', '" . $search . "')) AS rank"
            )
        )->addSelect(['image' => function ($query) {
            $query->select('url')->from('images')
                ->whereColumn('images.imageable_id', 'productos.id')
                ->where('images.imageable_type', Producto::class)
                ->orderBy('default', 'desc')->limit(1);
        }])->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->leftJoin('subcategories', 'productos.subcategory_id', '=', 'subcategories.id')
            ->leftJoin('categories', 'productos.category_id', '=', 'categories.id');

        if ($empresa->viewOnlyDisponibles()) {
            $products->withWhereHas('almacens', function ($query) {
                $query->where('cantidad', '>', 0);
            });
        }

        $products->whereRaw(
            "to_tsvector('spanish', 
            COALESCE(productos.name, '') || ' ' || 
            COALESCE(marcas.name, '') || ' ' || 
            COALESCE(categories.name, '')) @@ plainto_tsquery('spanish', '" . $search . "')",
        )->orWhereRaw(
            "similarity(productos.name, '" . $search . "') > 0.5 
            OR similarity(marcas.name, '" . $search . "') > 0.5 
            OR similarity(categories.name, '" . $search . "') > 0.5",
        )->orderByDesc('rank')->orderByDesc(DB::raw("similarity(productos.name, '" . $search . "')"))
            ->orderBy('subcategories.orden', 'ASC')->orderBy('categories.orden', 'ASC')
            ->visibles()->publicados()->orderBy('name', 'asc')->limit(15);

        $products = $products->get()->transform(function ($item) {
            $item->image = pathURLProductImage($item->image);
            return $item;
        });

        return response()->json($products);
    }

    public function searchsubcategories(Request $request)
    {
        $category_id = $request->input('category_id');
        $subcategories = Category::find($category_id)->subcategories()->orderBy('orden', 'asc')->get();
        return response()->json($subcategories);
    }
}
