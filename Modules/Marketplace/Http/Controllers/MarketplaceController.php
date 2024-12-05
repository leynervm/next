<?php

namespace Modules\Marketplace\Http\Controllers;

use App\Enums\MethodPaymentOnlineEnum;
use App\Enums\StatusPayWebEnum;
use App\Models\Category;
use App\Models\Employer;
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
use Nwidart\Modules\Facades\Module;
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
        // $ofertas = Producto::query()->select('id', 'name', 'slug', 'marca_id', 'subcategory_id', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5')
        // ->addSelect(['image' => function ($query) {
        //     $query->select('url')->from('images')
        //         ->whereColumn('images.imageable_id', 'productos.id')
        //         ->where('images.imageable_type', Producto::class)
        //         ->orderBy('default', 'desc')->limit(1);
        // }])->withCount(['almacens as stock' => function ($query) {
        //     $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
        // }])->whereHas('promocions', function ($query) {
        //     $query->availables()->disponibles();
        // })->with(['marca', 'promocions' => function ($query) {
        //     $query->with(['itempromos.producto' => function ($itemQuery) {
        //         $itemQuery->with('unit')->addSelect(['image' => function ($q) {
        //             $q->select('url')->from('images')
        //                 ->whereColumn('images.imageable_id', 'productos.id')
        //                 ->where('images.imageable_type', Producto::class)
        //                 ->orderBy('default', 'desc')->limit(1);
        //         }]);
        //     }])->availables()->disponibles();
        // }])->publicados()->visibles()->orderBy('views', 'desc')
        // ->orderBy('name', 'asc')->paginate(30)->through(function ($producto) {
        //     $producto->promocion = $producto->promocions->first();
        //     return $producto;
        // });

        $pricetype = getPricetypeAuth();
        $ofertas = Producto::query()->select(
            'productos.id',
            'productos.name',
            'productos.slug',
            'marca_id',
            'category_id',
            'subcategory_id',
            'visivility',
            'publicado',
            'novedad',
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
            'subcategories.name as name_subcategory'
        )->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->leftJoin('subcategories', 'productos.subcategory_id', '=', 'subcategories.id')
            ->leftJoin('categories', 'productos.category_id', '=', 'categories.id')
            ->with(['unit', 'almacens', 'compraitems.compra.proveedor'])
            ->withCount(['almacens as stock' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)')); // Suma de la cantidad en la tabla pivote
            }])->addSelect(['image' => function ($query) {
                $query->select('url')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('default', 'desc')->limit(1);
            }])->with(['almacens'])->withCount(['almacens as stock' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)')); // Suma de la cantidad en la tabla pivote
            }])->addSelect(['image' => function ($query) {
                $query->select('url')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('default', 'desc')->limit(1);
            }])->addSelect(['image_2' => function ($query) {
                $query->select('url')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('default', 'desc')
                    ->offset(1)->limit(1);
            }])->withWhereHas('promocions', function ($query) {
                $query->with(['itempromos.producto' => function ($itemQuery) {
                    $itemQuery->with('unit')->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderBy('default', 'desc')->limit(1);
                    }]);
                }])->availables()->disponibles();
            })->visibles()->publicados()->orderBy('novedad', 'desc')->orderBy('subcategories.orden', 'ASC')
            ->orderBy('categories.orden', 'ASC')->paginate(30)->through(function ($producto) {
                $producto->promocion = $producto->promocions->first();
                return $producto;
            });

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

    public function quiensomos()
    {
        $employers = [];
        if (Module::isEnabled('Employer')) {
            $employers = Employer::orderBy('name')->get();
        }
        return view('modules.marketplace.quienes-somos', compact('employers'));
    }

    public function contactanos()
    {
        return view('modules.marketplace.contactanos');
    }

    public function centroautorizado()
    {
        $brother = [
            'requisitos' => [
                [
                    'id' => 1,
                    'titulo' => "Validez de la Garantía",
                    'descripcion' => "su producto se mantenga válida, es fundamental que el equipo no haya sido manipulado. Cualquier daño causado por reparaciones no autorizadas
                                    o modificaciones puede anular la garantía."
                ],
                [
                    'id' => 2,
                    'titulo' => "Comprobante de Compra",
                    'descripcion' => "Se requiere presentar el comprobante de compra original, que debe incluir la fecha de adquisición y los datos del vendedor."
                ],
                [
                    'id' => 3,
                    'titulo' => "Accesorios Incluidos",
                    'descripcion' => "Si corresponde, asegúrese de incluir todos los accesorios originales que venían con el producto al solicitar el servicio (tintas, cable de datos, cable de energía, caja y otros)."
                ],
                [
                    'id' => 4,
                    'titulo' => "Estado del Producto",
                    'descripcion' => "El equipo debe estar en condiciones adecuadas para su evaluación, sin daños visibles que no estén cubiertos por la garantía."
                ],
                [
                    'id' => 5,
                    'titulo' => "Periodo de Garantía",
                    'descripcion' => "Asegúrese de que el producto se encuentre dentro del período de garantía estipulado, que generalmente es de un año a partir de la fecha de compra, aunque puede variar según el modelo."
                ],
                [
                    'id' => 6,
                    'titulo' => "Uso Adecuado",
                    'descripcion' => "El producto debe haber sido utilizado conforme a las instrucciones del fabricante y no debe haber sido sometido a modificaciones no autorizadas."
                ],
            ]
        ];
        $lenovo = [
            'requisitos' => [
                [
                    'id' => 1,
                    'titulo' => "Registro del Producto",
                    'descripcion' => " Si su compra ha sido realizada en algun centro o negocio, crear N° caso llamando al 0800-55-98."
                ],
                [
                    'id' => 2,
                    'titulo' => "Validez de la Garantía",
                    'descripcion' => "Para que la garantía de su producto se mantenga válida, es fundamental que el equipo no haya sido manipulado. Cualquier daño causado por reparaciones no autorizadas o modificaciones puede anular la garantía."
                ],
                [
                    'id' => 3,
                    'titulo' => "Período de Garantía",
                    'descripcion' => "Verifique que el producto esté dentro del período de garantía, que generalmente es de un año, aunque puede variar según el modelo."
                ],
                [
                    'id' => 4,
                    'titulo' => "Comprobante de Compra",
                    'descripcion' => "Presente el comprobante de compra original, que debe incluir la fecha de adquisición y el nombre del vendedor."
                ],
                [
                    'id' => 5,
                    'titulo' => "Uso Correcto",
                    'descripcion' => ": El producto debe haber sido utilizado de acuerdo con las instrucciones del fabricante y no debe haber sido sometido a modificaciones no autorizadas."
                ],
                [
                    'id' => 6,
                    'titulo' => "Accesorios Originales",
                    'descripcion' => " Si aplica, incluya todos los accesorios originales que vinieron con el dispositivo al solicitar el servicio (cargador, cable de energía, caja y otros)."
                ],
            ]
        ];
        $asus = [
            'requisitos' => [
                [
                    'id' => 1,
                    'titulo' => "Comprobante de Compra",
                    'descripcion' => "Debes presentar la factura o recibo de compra que demuestre la fecha de adquisición del producto."
                ],
                [
                    'id' => 2,
                    'titulo' => "Accesorios Originales",
                    'descripcion' => "Si aplica, incluya todos los accesorios originales que vinieron con el dispositivo al solicitar el servicio (cargador, cable de energía, caja y otros)."
                ],
                [
                    'id' => 3,
                    'titulo' => "Condiciones de Uso",
                    'descripcion' => "El producto debe haber sido utilizado de acuerdo con las instrucciones del fabricante y no debe mostrar daños por mal uso."
                ],
                [
                    'id' => 4,
                    'titulo' => "Plazo de Garantía",
                    'descripcion' => "Verificar que el producto esté dentro del período de garantía especificado, que varía según el tipo de producto."
                ],
                [
                    'id' => 5,
                    'titulo' => "Documentación Adicional",
                    'descripcion' => "En algunos casos, puede ser necesario completar formularios o proporcionar información adicional."
                ]
            ]
        ];
        $garantias = response()->json([
            'brother' => $brother,
            'lenovo' => $lenovo,
            'asus' => $asus,
        ])->getData();
        return view('modules.marketplace.centro-autorizado', compact('garantias'));
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
            'image' =>   asset('images/tic/ds_principal.jpg'),
            'content' => [
                [
                    'title' => 'DevOps',
                    'description' => 'Implementamos procesos a través de la configuración de software para la gestión automatizada, pruebas de regresión, otros sistemas software y la nube que impulsan soluciones competitivas en el mercado.',
                    'url' => asset('images/tic/ds_devops.jpg'),
                ],
                [
                    'title' => 'Ingeniería de Calidad',
                    'description' => 'Maximizamos la estabilidad de las aplicaciones y optimizamos el tiempo de las pruebas y los costos. Al mismo tiempo que incluimos pruebas unitarias, funcionales, integradas a los sistemas de rendimiento, de seguridad y de aceptación del usuario. El alcance de la automatización inteligente comprende de áreas móviles, web, APIs y servicios.',
                    'url' => asset('images/tic/ds_ingenieria.jpg'),
                ]
            ]
        ];
        $soporte = [
            'image' =>   asset('images/tic/st_principal.jpg'),
            'content' => [
                [
                    'title' => 'Cambio de Pantalla',
                    'description' => 'Devuélvele la claridad a tu dispositivo: cambio de pantalla para que disfrutes cada detalle de nuevo.',
                    'url' => asset('images/tic/st_cambio_pantalla.jpg'),
                ],
                [
                    'title' => 'Repotenciamos tu case',
                    'description' => 'Repotenciamos tu case para que luzca y rinda como nuevo, llevando tu tecnología al siguiente nivel.',
                    'url' => asset('images/tic/st_repotenciamos_pc.jpg'),
                ],
                [
                    'title' => 'Cambio de teclado',
                    'description' => 'Renueva tu experiencia de escritura: cambia tu teclado y siente la diferencia en cada pulsación.',
                    'url' => asset('images/tic/st_cambio_teclado.jpg'),
                ],
                [
                    'title' => 'Instalación de programas de ingeniería',
                    'description' => 'Instala tus herramientas de ingeniería y transforma ideas en soluciones innovadoras.',
                    'url' => asset('images/tic/st_instalacion_sw.jpg'),
                ],
                [
                    'title' => 'Soporte técnico de impresora',
                    'description' => 'Estamos aquí para asegurar que tu impresión fluya sin problemas: ¡tu soporte técnico de confianza para impresoras!.',
                    'url' => asset('images/tic/st_impresora.jpg'),
                ],
                [
                    'title' => 'Reparación de case',
                    'description' => 'Devuelve la vida a tu equipo: reparación de cases para que tu tecnología siempre esté protegida y lista para rendir.',
                    'url' => asset('images/tic/st_reparacion_case.jpg'),
                ],
            ]
        ];
        $seguridad = [
            'image' =>   asset('images/tic/se_principal.jpg'),
            'content' => [
                [
                    'title' => 'Cámaras de vigilancia (CCTV)',
                    'description' => 'Las cámaras de vigilancia (CCTV) son tus ojos en todo momento, asegurando la seguridad y protección de tus espacios.',
                    'url' => asset('images/tic/se_camaras.jpg'),
                ],
                [
                    'title' => 'Alarmas de intrusión',
                    'description' => 'Las alarmas de intrusión son tu primera línea de defensa, alertando sobre cualquier acceso no autorizado y manteniendo tu hogar o negocio a salvo.',
                    'url' => asset('images/tic/se_alarmas.jpg'),
                ],
                [
                    'title' => 'Control de acceso',
                    'description' => 'El control de acceso garantiza que solo las personas autorizadas ingresen a áreas sensibles, protegiendo tu seguridad y la de tus bienes.',
                    'url' => asset('images/tic/se_acceso.jpg'),
                ],
                [
                    'title' => 'Sensores de humo y fuego',
                    'description' => 'Los sensores de humo y fuego son guardianes silenciosos que salvan vidas al detectar peligros a tiempo.',
                    'url' => asset('images/tic/se_sensores_humo.jpg'),
                ],
                [
                    'title' => 'Sistema de videovigilancia en la nube',
                    'description' => 'El sistema de videovigilancia en la nube te ofrece seguridad y acceso instantáneo a tus grabaciones desde cualquier lugar.',
                    'url' => asset('images/tic/se_vigilancia_nube.jpg'),
                ],
                [
                    'title' => 'Sensores de movimiento',
                    'description' => 'Los sensores de movimiento son la primera alerta ante intrusos, protegiendo tus espacios con precisión.',
                    'url' => asset('images/tic/se_sensores_movimiento.jpg'),
                ],
            ]
        ];
        $datacenter = [
            'image' => asset('images/tic/dc_principal.jpg'),
            'content' => []
        ];
        $redes = [
            'image' =>   asset('images/tic/rt_principal.jpg'),
            'content' => [
                [
                    'title' => 'Conectividad a Internet',
                    'description' => 'Ofrecemos acceso a internet de alta velocidad con opciones de redundancia para una conexión ininterrumpida.',
                    'url' => asset('images/tic/rt_conectividad.jpg'),
                ],
                [
                    'title' => 'Redes Privadas Virtuales (VPN)',
                    'description' => 'Conéctate de forma segura a tu red corporativa desde cualquier lugar con nuestras soluciones VPN.',
                    'url' => asset('images/tic/rt_redes_privadas.jpg'),
                ],
                [
                    'title' => 'Servicios de Telefonía IP',
                    'description' => 'Reduce costos y mejora la comunicación con nuestras soluciones de telefonía VoIP avanzadas.',
                    'url' => asset('images/tic/rt_telefonia.jpg'),
                ],
                [
                    'title' => 'Gestión de Redes',
                    'description' => 'Monitorea y optimiza tu red con nuestros servicios de gestión integral y soporte técnico.',
                    'url' => asset('images/tic/rt_gestion_redes.jpg'),
                ],
                [
                    'title' => 'Instalación y Mantenimiento de Infraestructura de Red',
                    'description' => 'Diseñamos e implementamos la infraestructura de red perfecta para tus necesidades, con soporte continuo.',
                    'url' => asset('images/tic/rt_instalacion.jpg'),
                ],
                [
                    'title' => 'Servicios de Seguridad de Redes',
                    'description' => 'Protege tu red y datos con nuestras soluciones avanzadas de ciberseguridad y monitoreo constante.',
                    'url' => asset('images/tic/rt_seguridad_redes.jpg'),
                ],
            ]
        ];
        $electricidad = [
            'image' =>   asset('images/tic/ea_principal.jpg'),
            'content' => [
                [
                    'title' => 'Instalación de Iluminación LED',
                    'description' => 'Implementación de soluciones de iluminación LED para mejorar la eficiencia energética y reducir costos de electricidad.',
                    'url' => asset('images/tic/ea_instalaciones_led.jpg'),
                ],
                [
                    'title' => 'Control de Climatización Inteligente',
                    'description' => 'Instalación de sistemas de control inteligente para aire acondicionado, permitiendo la programación y el control remoto de la temperatura.',
                    'url' => asset('images/tic/ea_control_climatizacion.jpg'),
                ],
                [
                    'title' => 'Actualización de Paneles Eléctricos',
                    'description' => 'Modernización de paneles eléctricos para mejorar la capacidad y seguridad de la instalación eléctrica.',
                    'url' => asset('images/tic/ea_paneles_electricos.jpg'),
                ],
                [
                    'title' => 'Inspección de Seguridad Eléctrica',
                    'description' => 'Evaluaciones detalladas de sistemas eléctricos para identificar riesgos y garantizar el cumplimiento de las normativas de seguridad.',
                    'url' => asset('images/tic/ea_inspeccion_seguridad.jpg'),
                ],
                [
                    'title' => 'Deshumidificación y Purificación de Aire',
                    'description' => 'Instalación de sistemas adicionales para deshumidificar y purificar el aire en espacios cerrados, mejorando la calidad del aire interior.',
                    'url' => asset('images/tic/ea_deshumidificacion.jpg'),
                ],
                [
                    'title' => 'Asesoramiento Energético',
                    'description' => 'Consultoría sobre soluciones energéticas sostenibles y eficientes, ayudando a los clientes a tomar decisiones informadas sobre sus sistemas eléctricos y de aire acondicionado.',
                    'url' => asset('images/tic/ea_asesoramiento_energetico.jpg'),
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

    public function servicesnetwork()
    {
        $zonas = [
            [
                'id' => 1,
                'name' => 'ZONA RURAL',
            ],
            [
                'id' => 2,
                'name' => 'ZONA URBANA',
            ]
        ];

        $zona_rural = [
            'dedicado' => [
                'lugar' => "Jaén - San Ignacio - Bagua Capital - Bagua Grande",
                'planes' => [
                    [
                        'type' => "Ilimitado/Empresarial",
                        'download' => "20MB",
                        'upload' => "20MB",
                        'price' => 1640
                    ],
                    [
                        'type' => "Ilimitado/Empresarial",
                        'download' => "40MB",
                        'upload' => "40MB",
                        'price' => 2890
                    ]
                ],
                'costos' => [
                    'titulos' => [
                        '*Las distancias se calculan de ciudad Jaén a zona rural',
                        '*Contrato minimo 01 año'
                    ],
                    'descripcion' => [
                        [
                            'descripcion' => "Distancias menores a 1 hora",
                            'price' => 500
                        ],
                        [
                            'descripcion' => "Distancias entre 1 - 2 horas",
                            'price' => 600
                        ],
                        [
                            'descripcion' => "Distancias entre 2 - 3 horas",
                            'price' => 700
                        ],
                        [
                            'descripcion' => "Distancias entre 3 - 4 horas",
                            'price' => 800
                        ],
                        [
                            'descripcion' => "Distancias entre 4 - 5 horas",
                            'price' => 900
                        ],
                        [
                            'descripcion' => "Distancias mayores a 5 horas",
                            'price' => 1000
                        ]
                    ]
                ],
                'soporte' => [
                    [
                        'icono' => 1,
                        'titulo' => "Atención al cliente",
                        'descripcion' => "Reporte de averías 8:00 am a 6:00 pm (10 horas)"
                    ],
                    [
                        'icono' => 2,
                        'titulo' => "Tiempo de respuesta",
                        'descripcion' => "Reporte de avería: Máximo 24 horas"
                    ]
                ]
            ],
            'convencional' => [
                'lugar' => "Jaén - San Ignacio - Bagua Capital - Bagua Grande",
                'planes' => [
                    [
                        'type' => "Ilimitado/Hogar - Negocio",
                        'download' => "10MB",
                        'upload' => "1MB",
                        'price' => 180
                    ],
                    [
                        'type' => "Ilimitado/Hogar - Negocio",
                        'download' => "20MB",
                        'upload' => "2MB",
                        'price' => 340
                    ]
                ],
                'costos' => [
                    'titulos' => ['*Las distancias se calculan de ciudad Jaén a zona rural'],
                    'descripcion' => [
                        [
                            'descripcion' => "Distancias menores a 1 hora",
                            'price' => 200
                        ],
                        [
                            'descripcion' => "Distancias entre 1 - 2 horas",
                            'price' => 300
                        ],
                        [
                            'descripcion' => "Distancias entre 2 - 3 horas",
                            'price' => 400
                        ],
                        [
                            'descripcion' => "Distancias entre 3 - 4 horas",
                            'price' => 500
                        ],
                        [
                            'descripcion' => "Distancias entre 4 - 5 horas",
                            'price' => 600
                        ],
                        [
                            'descripcion' => "Distancias mayores a 5 horas",
                            'price' => 800
                        ]
                    ]
                ],
                'soporte' => [
                    [
                        'icono' => 1,
                        'titulo' => "Atención al cliente",
                        'descripcion' => "Reporte de averías 8:00 am a 6:00 pm (10 horas)"
                    ],
                    [
                        'icono' => 2,
                        'titulo' => "Tiempo de respuesta",
                        'descripcion' => "Reporte de avería: Máximo 72 horas"
                    ]
                ]
            ]
        ];
        $zona_urbana = [
            'dedicado' => [
                'lugar' => "Jaén - San Ignacio - Bagua Capital - Bagua Grande",
                'planes' => [
                    [
                        'type' => "Ilimitado/Empresarial",
                        'download' => "20MB",
                        'upload' => "20MB",
                        'price' => 800
                    ],
                    [
                        'type' => "Ilimitado/Empresarial",
                        'download' => "40MB",
                        'upload' => "40MB",
                        'price' => 1500
                    ],
                    [
                        'type' => "Ilimitado/Empresarial",
                        'download' => "60MB",
                        'upload' => "60MB",
                        'price' => 2100
                    ],
                    [
                        'type' => "Ilimitado/Empresarial",
                        'download' => "100MB",
                        'upload' => "100MB",
                        'price' => 3300
                    ],
                    [
                        'type' => "Ilimitado/Empresarial",
                        'download' => "200MB",
                        'upload' => "200MB",
                        'price' => 5800
                    ],
                ],
                'costos' => [
                    'titulos' => ['*Las distancias se calculan de ciudad Jaén a zona rural'],
                    'descripcion' => [
                        [
                            'descripcion' => "Contrato 6 meses",
                            'price' => 1000
                        ],
                        [
                            'descripcion' => "*Tiempo de instalación 20 días hábiles",
                            'price' => ""
                        ],
                        [
                            'descripcion' => "Contrato minimo 1 año",
                            'price' => "*Cero costo de instalación"
                        ],
                        [
                            'descripcion' => "*Tiempo de instalación 20 días hábiles",
                            'price' => ""
                        ]
                    ]
                ],
                'soporte' => [
                    [
                        'icono' => 1,
                        'titulo' => "Atención al cliente",
                        'descripcion' => "Reporte de averías 8:00 am a 6:00 pm (10 horas)"
                    ],
                    [
                        'icono' => 2,
                        'titulo' => "Tiempo de respuesta",
                        'descripcion' => "Reporte de avería: Máximo 72 horas"
                    ]
                ]
            ],
            'convencional' => [
                'lugar' => "Jaén - San Ignacio - Bagua Capital - Bagua Grande",
                'planes' => [
                    [
                        'type' => "Ilimitado/Hogar - Negocio",
                        'download' => "40MB",
                        'upload' => "4MB",
                        'price' => 70
                    ],
                    [
                        'type' => "Ilimitado/Hogar - Negocio",
                        'download' => "80MB",
                        'upload' => "8MB",
                        'price' => 120
                    ],
                    [
                        'type' => "Ilimitado/Hogar - Negocio",
                        'download' => "120MB",
                        'upload' => "12MB",
                        'price' => 170
                    ],
                    [
                        'type' => "Ilimitado/Hogar - Negocio",
                        'download' => "160MB",
                        'upload' => "16MB",
                        'price' => 220
                    ],
                    [
                        'type' => "Ilimitado/Hogar - Negocio",
                        'download' => "200MB",
                        'upload' => "20MB",
                        'price' => 260
                    ],
                    [
                        'type' => "Ilimitado/Hogar - Negocio",
                        'download' => "400MB",
                        'upload' => "40MB",
                        'price' => 490
                    ]
                ],
                'costos' => [
                    'titulos' => ['*Las distancias se calculan de ciudad Jaén a zona rural'],
                    'descripcion' => [
                        [
                            'descripcion' => "Contrato 6 meses",
                            'price' => 200
                        ],
                        [
                            'descripcion' => "Contrato minimo 1 año",
                            'price' => "*Cero costo de instalación"
                        ],
                        [
                            'descripcion' => "*Tiempo de instalación 5 días hábiles",
                            'price' => ""
                        ]
                    ]
                ],
                'soporte' => [
                    [
                        'icono' => 1,
                        'titulo' => "Atención al cliente",
                        'descripcion' => "Reporte de averías 8:00 am a 6:00 pm (10 horas)"
                    ],
                    [
                        'icono' => 2,
                        'titulo' => "Tiempo de respuesta",
                        'descripcion' => "Reporte de avería: Máximo 72 horas"
                    ]
                ]
            ]
        ];

        $networks =  response()->json([
            'zona_rural' => $zona_rural,
            'zona_urbana' => $zona_urbana
        ])->getData();

        return view('modules.marketplace.services-network', compact('networks', 'zonas'));
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
        )->orderByDesc('novedad')->orderBy('subcategories.orden')
            ->orderBy('categories.orden')->orderByDesc('rank')
            ->orderByDesc(DB::raw("similarity(productos.name, '" . $search . "')"))
            ->visibles()->publicados()->limit(30);

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
