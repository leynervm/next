<?php

namespace Modules\Marketplace\Http\Controllers;

use App\Enums\PromocionesEnum;
use App\Mail\EnviarResumenOrder;
use App\Models\Category;
use App\Models\Employer;
use App\Models\Producto;
use App\Models\Promocion;
use App\Models\Sucursal;
use CodersFree\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Marketplace\Entities\Order;
use Modules\Marketplace\Entities\Shipmenttype;
use Nwidart\Modules\Facades\Module;
use Nwidart\Modules\Routing\Controller;

class MarketplaceController extends Controller
{

    public function __construct()
    {
        $this->middleware('verifycarshoop')->only(['create', 'productos', 'showproducto', 'carshoop', 'wishlist']);
    }

    public function carshoop()
    {
        $pricetype = getPricetypeAuth();
        return view('marketplace::carrito', compact('pricetype'));
    }

    public function wishlist()
    {
        $pricetype = getPricetypeAuth();
        return view('marketplace::wishlist', compact('pricetype'));
    }

    public function productos(Request $request)
    {
        $pricetype = getPricetypeAuth();
        return view('modules.marketplace.productos.index', compact('pricetype'));
    }

    public function profile()
    {
        return view('marketplace::profile');
    }

    public function ofertas()
    {

        $pricetype = getPricetypeAuth();
        $ofertas = Producto::query()->select(
            'productos.id',
            'productos.name',
            'productos.slug',
            'marca_id',
            'unit_id',
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
            ->with(['unit', 'imagen', 'almacens', 'compraitems.compra.proveedor'])
            ->withCount(['almacens as stock' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)')); // Suma de la cantidad en la tabla pivote
            }])->addSelect(['image_2' => function ($query) {
                $query->select('url')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('orden', 'asc')->orderBy('id', 'asc')->offset(1)->limit(1);
            }])->withWhereHas('promocions', function ($query) {
                $query->with('itempromos.producto.almacens')->availables()->disponibles();
            })->visibles()->publicados()->orderByDesc('novedad')->orderBy('subcategories.orden', 'ASC')
            ->orderBy('categories.orden', 'ASC')->paginate(30)->through(function ($producto) {
                // $producto->oferta = $producto->promocions->where('type', PromocionesEnum::OFERTA->value)->first()->descuento ?? 0;
                $producto->descuento = $producto->promocions->whereIn('type', [PromocionesEnum::DESCUENTO->value, PromocionesEnum::OFERTA->value])->first()->descuento ?? 0;
                $producto->liquidacion = $producto->promocions->where('type', PromocionesEnum::LIQUIDACION->value)->count() > 0 ? true : false;

                $combos = [];
                $pricetype = getPricetypeAuth();
                foreach ($producto->promocions->where('type', PromocionesEnum::COMBO->value)->all() as $item) {
                    $combo = getAmountCombo($item, $pricetype);
                    if ($combo->stock_disponible && $combo->is_disponible) {
                        $combos[] = $combo;
                    }
                }

                $producto->combos = $combos;
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
        $shoppings = getCartRelations('shopping', true);
        return view('modules.marketplace.orders.create', compact('shoppings', 'pricetype'));
    }

    public function resumenorder(Order $order)
    {
        // $this->authorize('user', $order);
        $order->load([
            'shipmenttype',
            'user',
            'entrega.sucursal.ubigeo',
            'client',
            'moneda',
            'direccion.ubigeo',
            'transaccion',
            'tvitems' => function ($query) {
                $query->with(['promocion', 'producto' => function ($subq) {
                    $subq->with(['unit', 'imagen', 'marca', 'category']);
                }, 'carshoopitems' => function ($q) {
                    $q->with(['itempromo', 'producto' => function ($db) {
                        $db->with(['unit', 'imagen', 'marca', 'category']);
                    }]);
                }]);
            },
            'trackings' => function ($query) {
                $query->with('trackingstate')->orderBy('date', 'asc');
            }
        ]);

        return view('modules.marketplace.orders.payment', compact('order'));
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

        $relacionados = Producto::query()->select('id', 'name', 'slug', 'modelo', 'sku', 'partnumber', 'marca_id', 'unit_id', 'subcategory_id', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5')
            ->with(['marca', 'imagen', 'promocions' => function ($query) {
                $query->with(['itempromos.producto' => function ($query) {
                    $query->with(['imagen', 'almacens']);
                }])->availables()->disponibles();
            }])->whereNot('id', $producto->id)->where('subcategory_id', $producto->subcategory_id)
            ->publicados()->visibles()->take(28)->orderBy('views', 'desc')
            ->orderBy('name', 'asc')->get()->map(function ($producto) {
                $producto->descuento = $producto->promocions->whereIn('type', [PromocionesEnum::DESCUENTO->value, PromocionesEnum::OFERTA->value])->first()->descuento ?? 0;
                $producto->liquidacion = $producto->promocions->where('type', PromocionesEnum::LIQUIDACION->value)->count() > 0 ? true : false;
                return $producto;
            });
        // dd($relacionados);
        $interesantes = Producto::query()->select('id', 'name', 'slug', 'marca_id', 'unit_id', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5')
            ->with(['marca', 'imagen', 'promocions' => function ($query) {
                $query->with(['itempromos.producto' => function ($query) {
                    $query->with(['imagen', 'almacens']);
                }])->availables()->disponibles();
            }])->whereNot('id', $producto->id)->publicados()->visibles()
            ->inRandomOrder()->take(28)->orderBy('views', 'desc')
            ->orderBy('name', 'asc')->get()->map(function ($producto) {
                $producto->descuento = $producto->promocions->whereIn('type', [PromocionesEnum::DESCUENTO->value, PromocionesEnum::OFERTA->value])->first()->descuento ?? 0;
                $producto->liquidacion = $producto->promocions->where('type', PromocionesEnum::LIQUIDACION->value)->count() > 0 ? true : false;
                return $producto;
            });

        $producto->load([
            'marca',
            'unit',
            'category' => function ($query) {
                $query->select('id', 'name', 'slug');
            },
            'subcategory',
            'especificacions.caracteristica',
            'detalleproducto',
            'garantiaproductos.typegarantia',
            'images',
            'promocions' => function ($query) {
                $query->with(['itempromos.producto' => function ($subQuery) {
                    $subQuery->with(['unit', 'almacens'])->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderBy('orden', 'asc')->orderBy('id', 'asc')->limit(1);
                    }]);
                }])->availables()->disponibles();
            },
            'almacens' => function ($query) {
                $query->wherePivot('cantidad', '>', 0);
            }
        ])->loadCount(['almacens as stock' => function ($query) {
            $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
        }]);

        $combos = [];
        $producto->descuento = $producto->promocions->whereIn('type', [PromocionesEnum::DESCUENTO->value, PromocionesEnum::OFERTA->value])->first()->descuento ?? 0;
        $producto->liquidacion = $producto->promocions->where('type', PromocionesEnum::LIQUIDACION->value)->count() > 0 ? true : false;
        foreach ($producto->promocions->where('type', \App\Enums\PromocionesEnum::COMBO->value)->all() as $item) {
            $combo = getAmountCombo($item, $pricetype);
            if ($combo->stock_disponible && $combo->is_disponible) {
                $combos[] = $combo;
            }
        }
        // $combos = response()->json($combos)->getData();
        return view('modules.marketplace.productos.show', compact('producto', 'shipmenttypes', 'relacionados', 'interesantes', 'pricetype', 'combos'));
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
        )->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->leftJoin('subcategories', 'productos.subcategory_id', '=', 'subcategories.id')
            ->leftJoin('categories', 'productos.category_id', '=', 'categories.id')
            ->with(['imagen']);
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
            $item->image = !empty($item->imagen) ? pathURLProductImage($item->imagen->url) : null;
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

    public function additemtocart(Request $request)
    {

        $promocion = null;
        $pricetype = getPricetypeAuth();
        $moneda = view()->shared('moneda');
        $promocion_id = $request->input('promocion_id');
        $producto_id = $request->input('producto_id');
        $promocion_id = empty($promocion_id) ? null : decryptText($promocion_id);
        $producto_id = empty($producto_id) ? null : decryptText($producto_id);
        $cantidad = $request->input('cantidad');
        $qtyexistente = 0;
        $open_modal = filter_var($request->input('open_modal'), FILTER_VALIDATE_BOOLEAN);

        try {
            if (!empty($producto_id) && $open_modal) {
                $producto =  Producto::with(['imagen', 'promocions' => function ($query) use ($open_modal) {
                    if ($open_modal) {
                        $query->with(['itempromos.producto' => function ($subq) {
                            $subq->with(['unit', 'imagen', 'almacens']);
                        }])->combos()->availables()->disponibles();
                    }
                }])->find($producto_id);

                if (empty($producto)) {
                    return response()->json([
                        'error' => 'EL PRODUCTO NO SE ENCUENTRA DISPONIBLE !',
                    ])->getData();
                    return false;
                }

                if (count($producto->promocions) > 0) {
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
            }

            if (!empty($promocion_id)) {

                $promocion = Promocion::with(['producto', 'itempromos.producto.unit'])->find($promocion_id);
                $producto = $promocion->producto->load(['promocions' => function ($query) {
                    $query->availables()->disponibles();
                }])->loadCount(['almacens as stock' => function ($query) {
                    $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
                }]);

                if (empty(verifyPromocion($promocion)) && empty($producto_id)) {
                    return response()->json([
                        'error' => 'PROMOCIÓN NO SE ENCUENTRA DISPONIBLE !',
                    ])->getData();
                    return false;
                }

                $stock_disponible_promocion = $promocion->limit - $promocion->outs;
                $cart = Cart::instance('shopping')->content()->first(function ($item) use ($promocion) {
                    return $item->id == $promocion->producto_id && $item->options->promocion_id == $promocion->id;
                });

                if (!empty($cart) && $cart->options->promocion_id == $promocion_id) {
                    $qtyexistente = $cart->qty;
                }

                if ($promocion->producto->stock <= 0 || $promocion->producto->stock < ($cantidad + $qtyexistente)) {
                    return response()->json([
                        'error' => 'CANTIDAD SUPERA EL STOCK DISPONIBLE DEL PRODUCTO !',
                    ])->getData();
                    return false;
                }

                if (empty($producto_id) && ($cantidad + $qtyexistente) > $stock_disponible_promocion) {
                    return response()->json([
                        'error' => 'CANTIDAD SUPERA EL STOCK DISPONIBLE EN PROMOCIÓN !',
                    ])->getData();
                    return false;
                }

                $combo = getAmountCombo($promocion, $pricetype);
                // -1               =               1               -   (    1     +    1       )
                $qty_restante_oferta = $stock_disponible_promocion - ($cantidad + $qtyexistente);
                $qty_sin_oferta = ($cantidad + $qtyexistente) - $stock_disponible_promocion;
                $pricesale = $producto->getPrecioVenta($pricetype);

                if ($combo) {
                    $pricesale = $pricesale + $combo->total;
                }

                // return [
                //     $pricesale,
                //     "$stock_disponible_promocion - ($cantidad + $qtyexistente)",
                //     "CANT REST. OFER: ($qty_restante_oferta)",
                //     "OTRO ITEM SIN PRM: ($qty_sin_oferta)",
                //     $producto->getPrecioVentaDefault($pricetype),
                // ];

                if ($pricesale > 0) {
                    // Si $cant_restante_oferta es mayor igual que 0 agregar un solo item con promocion
                    if ($qty_restante_oferta >= 0) {
                        $cart = Cart::instance('shopping')->content()->first(function ($item) use ($promocion) {
                            return $item->id == $promocion->producto_id && $item->options->promocion_id == $promocion->id;
                        });

                        if ($cart) {
                            Self::addproductocart(null, $cart->rowId, $cart->qty + $cantidad, number_format($pricesale, 2, '.', ''));
                        } else {
                            Self::addproductocart([
                                'id' => $producto->id,
                                'name' => $producto->name,
                                'qty' => $cantidad,
                                'price' => number_format($pricesale, 2, '.', ''),
                                'options' => [
                                    'date' => now('America/Lima')->format('Y-m-d H:i:s'),
                                    'moneda_id' => $moneda->id,
                                    'currency' => $moneda->currency,
                                    'simbolo' => $moneda->simbolo,
                                    'modo_precios' => $pricetype->name ?? 'DEFAUL PRICESALE',
                                    'carshoopitems' => empty($combo) ? [] : $combo->products,
                                    'promocion_id' => $promocion_id,
                                    'igv' => 0,
                                    'subtotaligv' => 0,
                                    'stock_disponible' => true,
                                ]
                            ]);
                        }
                    } else {
                        // Si $cant_restante_oferta es menor que 0 se agregarán dos item
                        // Agregar item con promocion de unidades diponibles ($stock_disponible_promocion)
                        // Agregar item con $cant_restante_oferta (POSITIVO) sin promocion
                        $cart = Cart::instance('shopping')->content()->first(function ($item) use ($promocion) {
                            return $item->id == $promocion->producto_id && $item->options->promocion_id == $promocion->id;
                        });

                        // return [
                        //     [
                        //         'qty' => $stock_disponible_promocion,
                        //         'pricesale' => $pricesale
                        // 'promocion_id' => 
                        //     ],
                        //     [
                        //         'qty' => $qty_sin_oferta,
                        //         'pricesale' => $producto->getPrecioVentaDefault($pricetype)
                        // 'promocion_id' => null,
                        //     ]
                        // ];

                        if ($cart) {
                            Self::addproductocart(null, $cart->rowId, $stock_disponible_promocion, number_format($pricesale, 2, '.', ''));
                        } else {
                            Self::addproductocart([
                                'id' => $producto->id,
                                'name' => $producto->name,
                                'qty' => $stock_disponible_promocion,
                                'price' => number_format($pricesale, 2, '.', ''),
                                'options' => [
                                    'date' => now('America/Lima')->format('Y-m-d H:i:s'),
                                    'moneda_id' => $moneda->id,
                                    'currency' => $moneda->currency,
                                    'simbolo' => $moneda->simbolo,
                                    'modo_precios' => $pricetype->name ?? 'DEFAUL PRICESALE',
                                    'carshoopitems' => empty($combo) ? [] : $combo->products,
                                    'promocion_id' => $promocion_id,
                                    'igv' => 0,
                                    'subtotaligv' => 0,
                                    'stock_disponible' => true,
                                ]
                            ]);
                        }

                        // AGREGO EL SEGUNDO CART SIN PROMOCION PORQUE CANT. SUPERA AL DISPONIBLE
                        $cart_normal = Cart::instance('shopping')->content()->first(function ($item) use ($promocion) {
                            return $item->id == $promocion->producto_id && $item->options->promocion_id == null;
                        });

                        if (!$combo) {
                            $pricesale = $producto->getPrecioVentaDefault($pricetype);
                        }

                        if ($cart_normal) {
                            Self::addproductocart(null, $cart_normal->rowId, $cart_normal->qty + $qty_sin_oferta, number_format($pricesale, 2, '.', ''));
                            //    return $saved;
                        } else {
                            Self::addproductocart([
                                'id' => $producto->id,
                                'name' => $producto->name,
                                'qty' => $qty_sin_oferta,
                                'price' => number_format($pricesale, 2, '.', ''),
                                'options' => [
                                    'date' => now('America/Lima')->format('Y-m-d H:i:s'),
                                    'moneda_id' => $moneda->id,
                                    'currency' => $moneda->currency,
                                    'simbolo' => $moneda->simbolo,
                                    'modo_precios' => $pricetype->name ?? 'DEFAUL PRICESALE',
                                    'carshoopitems' => [],
                                    'promocion_id' => null,
                                    'igv' => 0,
                                    'subtotaligv' => 0,
                                    'stock_disponible' => true,
                                ]
                            ]);
                            // return $saved;
                        }
                    }

                    if (auth()->check()) {
                        Cart::instance('shopping')->store(auth()->id());
                    }

                    return response()->json([
                        'success' => true,
                        'mensaje' => "AÑADIDO AL CARRITO",
                    ])->getData();
                } else {
                    return response()->json([
                        'error' => 'ERROR AL OBTENER PRECIOS DE PROMOCIÓN !',
                    ])->getData();
                    return false;
                }
            } else {
                $producto = Producto::with(['promocions' => function ($query) {
                    $query->availables()->disponibles();
                }])->withCount(['almacens as stock' => function ($query) {
                    $query->select(DB::raw('COALESCE(SUM(cantidad), 0)'));
                }])->find($producto_id);

                $cart = Cart::instance('shopping')->content()->first(function ($item) use ($producto_id) {
                    return $item->id == $producto_id && $item->options->promocion_id == null;
                });

                if (!empty($cart)) {
                    $qtyexistente = $cart->qty;
                }

                if ($producto->maxstockweb > 0 && ($cantidad + $qtyexistente) > $producto->maxstockweb) {
                    return response()->json([
                        'error' => 'CANTIDAD SUPERA EL LÍMITE DE UNIDADES DE COMPRA POR PRODUCTO [MAX: ' . $cart->model->maxstockweb . ' ' . $cart->model->unit->name . '] !',
                    ])->getData();
                    return false;
                }

                if ($producto->stock <= 0 || $producto->stock < ($cantidad + $qtyexistente)) {
                    return response()->json([
                        'error' => 'CANTIDAD SUPERA EL STOCK DISPONIBLE DEL PRODUCTO !',
                    ])->getData();
                    return false;
                }

                // $priceold = $producto->getPrecioVentaDefault($pricetype);
                $pricesale = $producto->getPrecioVenta($pricetype);

                if ($pricesale > 0) {
                    if (!empty($cart)) {
                        $newcart = Self::addproductocart(null, $cart->rowId, $cart->qty + $cantidad, number_format($pricesale, 2, '.', ''));
                    } else {
                        $newcart = Self::addproductocart([
                            'id' => $producto->id,
                            'name' => $producto->name,
                            'qty' => $cantidad,
                            'price' => number_format($pricesale, 2, '.', ''),
                            'options' => [
                                'date' => now('America/Lima')->format('Y-m-d H:i:s'),
                                'moneda_id' => $moneda->id,
                                'currency' => $moneda->currency,
                                'simbolo' => $moneda->simbolo,
                                'modo_precios' => $pricetype->name ?? 'DEFAUL PRICESALE',
                                'carshoopitems' => [],
                                'promocion_id' => null,
                                'igv' => 0,
                                'subtotaligv' => 0,
                                'stock_disponible' => true,
                            ]
                        ]);
                    }

                    if (auth()->check()) {
                        Cart::instance('shopping')->store(auth()->id());
                    }

                    return response()->json([
                        'success' => true,
                        'mensaje' => "AÑADIDO AL CARRITO",
                    ])->getData();
                } else {
                    return response()->json([
                        'error' => 'ERROR AL OBTENER PRECIOS DE PROMOCIÓN !',
                    ])->getData();
                    return false;
                }
            }
            return response()->json([
                'error' => 'PROMOCIÓN NO SE ENCUENTRA DISPONIBLE !',
            ])->getData();
            return false;
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

    public function addproductocart($cart = null, $rowId = null, $new_qty = null, $new_price = null)
    {
        if (empty($rowId)) {
            $cart = Cart::instance('shopping')->add($cart)->associate(Producto::class);
            return $cart;
        } else {
            $cart = Cart::instance('shopping')->update($rowId, [
                'price' => number_format($new_price, 2, '.', ''),
                'qty' => $new_qty,
            ]);
            return $cart;
        }
    }

    public function increment($cart, $cantidad = 1)
    {
        $pricetype = getPricetypeAuth();
        if (!empty($cart->options->promocion_id)) {
            $promocion = Promocion::with('producto')->find($cart->options->promocion_id);

            if (empty(verifyPromocion($promocion)) && empty($producto_id)) {
                return response()->json([
                    'error' => 'PROMOCIÓN NO SE ENCUENTRA DISPONIBLE !',
                ])->getData();
                return false;
            }

            $promocion->producto->loadCount(['almacens as stock' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
            }]);

            $stock_disponible_promocion = $promocion->limit - $promocion->outs;

            if ($promocion->producto->stock <= 0 || $promocion->producto->stock < ($cart->qty + $cantidad)) {
                return response()->json([
                    'error' => 'CANTIDAD SUPERA EL STOCK DISPONIBLE DEL PRODUCTO !',
                ])->getData();
                return false;
            }

            if (($cart->qty + $cantidad) > $stock_disponible_promocion) {
                return response()->json([
                    'error' => 'CANTIDAD SUPERA EL STOCK DISPONIBLE EN PROMOCIÓN !',
                ])->getData();
                return false;
            }

            $promocion->load(['itempromos.producto' => function ($query) {
                $query->with(['almacens', 'unit']);
            }]);

            $combo = getAmountCombo($promocion, $pricetype);
            $pricesale = $promocion->producto->getPrecioVenta($pricetype);

            if ($pricesale > 0) {
                if ($combo) {
                    $pricesale = $pricesale + $combo->total;
                }

                Self::addproductocart(null, $cart->rowId, $cart->qty + $cantidad, $pricesale);

                if (auth()->check()) {
                    Cart::instance('shopping')->store(auth()->id());
                }

                return response()->json([
                    'success' => true,
                    'mensaje' => 'CARRITO ACTUALIZADO CORRECTAMENTE !',
                ])->getData();
            } else {
                return response()->json([
                    'error' => 'ERROR AL OBTENER PRECIOS DE VENTA DEL PRODUCTO !',
                ])->getData();

                return false;
            }
        } else {
            $cart->model->loadCount(['almacens as stock' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
            }]);

            if ($cart->model->maxstockweb > 0 && ($cart->qty + $cantidad) > $cart->model->maxstockweb) {
                return response()->json([
                    'error' => 'CANTIDAD SUPERA EL LÍMITE DE UNIDADES DE COMPRA POR PRODUCTO [MAX: ' . $cart->model->maxstockweb . ' ' . $cart->model->unit->name . '] !',
                ])->getData();
                return false;
            }

            if ($cart->model->stock <= 0 || $cart->model->stock < ($cart->qty + $cantidad)) {
                return response()->json([
                    'error' => 'CANTIDAD SUPERA EL STOCK DISPONIBLE DEL PRODUCTO !',
                ])->getData();
                return false;
            }

            $pricesale = $cart->model->getPrecioVenta($pricetype);

            if ($pricesale > 0) {
                Self::addproductocart(null, $cart->rowId, $cart->qty + $cantidad, $pricesale);

                if (auth()->check()) {
                    Cart::instance('shopping')->store(auth()->id());
                    // Cart::instance('shopping')->restore(auth()->id());
                }

                return response()->json([
                    'success' => true,
                    'mensaje' => 'CARRITO ACTUALIZADO CORRECTAMENTE !',
                ])->getData();
                return false;
            } else {
                return response()->json([
                    'error' => 'ERROR AL OBTENER PRECIOS DE VENTA DEL PRODUCTO !',
                ])->getData();
                return false;
            }
        }
    }

    private function decrement($cart, $cantidad = 1)
    {
        if ($cart->qty > 1) {
            Cart::instance('shopping')->update($cart->rowId, $cart->qty - $cantidad);
        } else {
            Cart::instance('shopping')->remove($cart->rowId);
        }
        if (auth()->check()) {
            Cart::instance('shopping')->store(auth()->id());
        }
        return response()->json([
            'success' => true,
            'mensaje' => 'CARRITO ACTUALIZADO CORRECTAMENTE !',
        ])->getData();
    }

    public function updatecart(Request $request)
    {
        $rowId = $request->input('rowId');
        $type = $request->input('type');
        $cantidad = $request->input('cantidad') ?? 1;
        $cart = Cart::instance('shopping')->get($rowId);

        if ($cart && $cart->model) {
            if ($type == 'increment') {
                return Self::increment($cart, $cantidad);
            } else if ($type == 'decrement') {
                return Self::decrement($cart);
            } else {
                return response()->json([
                    'error' => 'OPERACIÓN NO PERMITIDA !',
                ])->getData();
                return false;
            }
        } else {
            return response()->json([
                'error' => 'PRODUCTO NO SE ENCUENTRA DISPONIBLE !',
            ])->getData();
            return false;
        }
    }

    public function updateqty(Request $request)
    {
        $rowId = $request->input('rowId');
        $cantidad = $request->input('cantidad') ?? 1;
        $cart = Cart::instance('shopping')->get($rowId);

        if ($cart && $cart->model) {
            $pricetype = getPricetypeAuth();
            if (!empty($cart->options->promocion_id)) {
                $promocion = Promocion::with('producto')->find($cart->options->promocion_id);

                if (empty(verifyPromocion($promocion)) && empty($producto_id)) {
                    return response()->json([
                        'qty' => $cart->qty,
                        'error' => 'PROMOCIÓN NO SE ENCUENTRA DISPONIBLE !',
                    ])->getData();
                    return false;
                }

                $promocion->producto->loadCount(['almacens as stock' => function ($query) {
                    $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
                }]);

                $stock_disponible_promocion = $promocion->limit - $promocion->outs;

                if ($promocion->producto->stock <= 0 || $promocion->producto->stock < $cantidad) {
                    return response()->json([
                        'qty' => $cart->qty,
                        'error' => 'CANTIDAD SUPERA EL STOCK DISPONIBLE DEL PRODUCTO !',
                    ])->getData();
                    return false;
                }

                if ($cantidad > $stock_disponible_promocion) {
                    return response()->json([
                        'qty' => $cart->qty,
                        'error' => 'CANTIDAD SUPERA EL STOCK DISPONIBLE EN PROMOCIÓN !',
                    ])->getData();
                    return false;
                }

                $promocion->load(['itempromos.producto' => function ($query) {
                    $query->with(['almacens', 'unit']);
                }]);

                $combo = getAmountCombo($promocion, $pricetype);
                $pricesale = $promocion->producto->getPrecioVenta($pricetype);

                if ($pricesale > 0) {
                    if ($combo) {
                        $pricesale = $pricesale + $combo->total;
                    }

                    Self::addproductocart(null, $cart->rowId, $cantidad, $pricesale);

                    if (auth()->check()) {
                        Cart::instance('shopping')->store(auth()->id());
                    }

                    return response()->json([
                        'success' => true,
                        'mensaje' => 'CARRITO ACTUALIZADO CORRECTAMENTE !',
                    ])->getData();
                } else {
                    return response()->json([
                        'error' => 'ERROR AL OBTENER PRECIOS DE VENTA DEL PRODUCTO !',
                    ])->getData();

                    return false;
                }
            } else {
                $cart->model->loadCount(['almacens as stock' => function ($query) {
                    $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
                }]);

                if ($cart->model->maxstockweb > 0 && $cantidad > $cart->model->maxstockweb) {
                    return response()->json([
                        'error' => 'CANTIDAD SUPERA EL LÍMITE DE UNIDADES DE COMPRA POR PRODUCTO [MAX: ' . $cart->model->maxstockweb . ' ' . $cart->model->unit->name . '] !',
                    ])->getData();
                    return false;
                }

                if ($cart->model->stock <= 0 || $cart->model->stock < $cantidad) {
                    return response()->json([
                        'qty' => $cart->qty,
                        'error' => 'CANTIDAD SUPERA EL STOCK DISPONIBLE DEL PRODUCTO !',
                    ])->getData();
                    return false;
                }

                $pricesale = $cart->model->getPrecioVenta($pricetype);

                if ($pricesale > 0) {
                    Self::addproductocart(null, $cart->rowId, $cantidad, $pricesale);

                    if (auth()->check()) {
                        Cart::instance('shopping')->store(auth()->id());
                    }

                    return response()->json([
                        'success' => true,
                        'mensaje' => 'CARRITO ACTUALIZADO CORRECTAMENTE !',
                    ])->getData();
                    return false;
                } else {
                    return response()->json([
                        'error' => 'ERROR AL OBTENER PRECIOS DE VENTA DEL PRODUCTO !',
                    ])->getData();
                    return false;
                }
            }
        } else {
            return response()->json([
                'error' => 'PRODUCTO NO SE ENCUENTRA DISPONIBLE !',
            ])->getData();
            return false;
        }
    }

    public function deletecart(Request $request)
    {

        $rowId = $request->input('rowId');
        $cart = Cart::instance('shopping')->get($rowId);

        if ($cart) {
            Cart::instance('shopping')->remove($rowId);
            if (auth()->check()) {
                Cart::instance('shopping')->store(auth()->id());
                // Cart::instance('shopping')->destroy();
                // Cart::instance('shopping')->restore(auth()->id());
            }
            return response()->json([
                'success' => true,
                'mensaje' => 'ELIMINADO CORRECTAMENTE DEL CARRITO !',
            ])->getData();
        } else {
            return response()->json([
                'error' => 'PRODUCTO NO SE ENCUENTRA DISPONIBLE !',
            ])->getData();
            return false;
        }
    }

    public function addfavoritos(Request $request)
    {

        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $producto_id = $request->input('producto_id');
        $producto_id = empty($producto_id) ? null : decryptText($producto_id);

        $producto = Producto::find($producto_id);
        return $producto->addfavorito();
    }

    // Funcion para pruebas de envio de resumen order
    // public function sendemailorder(Order $order)
    // {
    //     $order->load(['shipmenttype', 'user',  'entrega.sucursal.ubigeo', 'client', 'moneda', 'direccion.ubigeo', 'transaccion', 'tvitems' => function ($query) {
    //         $query->with(['promocion', 'producto' => function ($q) {
    //             $q->with(['unit', 'imagen']);
    //         }]);
    //     }]);
    //     // return $order;
    //     $mail = Mail::to('lvegam0413@gmail.com')->send(new EnviarResumenOrder($order));
    // }
}
