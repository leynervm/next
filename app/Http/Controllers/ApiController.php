<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Direccion;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Ubigeo;
use App\Rules\ValidateDocument;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use jossmp\sunat\ruc;
use jossmp\sunat\tipo_cambio;
use Nwidart\Modules\Facades\Module;

class ApiController extends Controller
{
    public function productos(Request $request)
    {

        $search = $request->input('search');
        $products = Producto::query()->select(
            'productos.id',
            'productos.name',
            'productos.slug',
            'productos.novedad',
            'productos.pricebuy',
            'productos.pricesale',
            'precio_1',
            'precio_2',
            'precio_3',
            'precio_4',
            'precio_5',
            'requireserie',
            'marca_id',
            'category_id',
            'subcategory_id',
            'unit_id',
            'marcas.name as name_marca',
        )->addSelect(['image' => function ($query) {
            $query->select('url')->from('images')
                ->whereColumn('images.imageable_id', 'productos.id')
                ->where('images.imageable_type', Producto::class)
                ->orderBy('orden', 'asc')->orderBy('id', 'asc')->limit(1);
        }])->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->leftJoin('subcategories', 'productos.subcategory_id', '=', 'subcategories.id')
            ->leftJoin('categories', 'productos.category_id', '=', 'categories.id')
            ->with(['unit', 'almacens']);

        // if (trim($search) !== '' && strlen(trim($search)) > 2) {
        //     $products->whereRaw(
        //         "to_tsvector('spanish', 
        //             COALESCE(productos.name, '') || ' ' || 
        //             COALESCE(marcas.name, '') || ' ' || 
        //             COALESCE(categories.name, '')) @@ plainto_tsquery('spanish', '" . $search . "')",
        //     )->orWhereRaw(
        //         "similarity(productos.name, '" . $search . "') > 0.5 
        //             OR similarity(marcas.name, '" . $search . "') > 0.5 
        //             OR similarity(categories.name, '" . $search . "') > 0.5",
        //     )->orderByDesc('novedad')->orderBy('subcategories.orden')
        //         ->orderBy('categories.orden')->orderByDesc('rank')
        //         ->orderByDesc(DB::raw("similarity(productos.name, '" . $search . "')"))
        //         ->visibles();
        // } else {
        $products->visibles()->orderByDesc('novedad')->orderBy('subcategories.orden')
            ->orderBy('categories.orden')->get()->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'name' => $producto->name,
                    'slug' => $producto->slug,
                    'requireserie' => $producto->isRequiredserie(),
                    'marca' => $producto->name_marca,
                    'unit' => $producto->unit->name,
                    'almacens' => $producto->almacens,
                    'image_url' => ($producto->image && pathURLProductImage($producto->image)) ? pathURLProductImage($producto->image) : null,
                ];
            });

        return response()->json($products);
    }

    public function consultacliente(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'document'  => ['required', 'numeric', new ValidateDocument, 'regex:/^\d{8}(?:\d{3})?$/']
        ]);
        // $validator->setAttributeNames($attributes);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ])->getData();
        }

        $document = trim($request->input('document'));
        $autosaved = filter_var($request->input('autosaved'), FILTER_VALIDATE_BOOLEAN) ?? false;
        $obtenerlista = filter_var($request->input('obtenerlista'), FILTER_VALIDATE_BOOLEAN) ?? false;
        $searchbd = filter_var($request->input('searchbd'), FILTER_VALIDATE_BOOLEAN) ?? false;
        $savedireccions = filter_var($request->input('savedireccions'), FILTER_VALIDATE_BOOLEAN) ?? false;

        if ($searchbd) {
            $cliente = self::consulta_cliente_bd($document, $obtenerlista, $autosaved);
            if (!empty($cliente)) {
                return $cliente;
            }
        }

        if (strlen(trim($document)) == 8) {
            return self::consulta_dni($document, $obtenerlista, $autosaved);
        }

        try {
            if (strlen(trim($document)) == 11) {
                $cliente = self::consulta_solo_ruc($document, $obtenerlista,  $autosaved, $savedireccions);
                if ($cliente->success) {
                    return $cliente;
                }
                return self::consulta_ruc($document, $obtenerlista,  $autosaved);
            }

            return response()->json([
                'success' => false,
                'error' => "Documento inválido",
            ])->getData();
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

    public function consulta_dni($dni, $obtenerlista = false, $autosaved = false)
    {
        try {
            $empresa = view()->shared('empresa');
            $pricetype = null;
            $token = config('services.apisnet.token');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Referer' => 'https://apis.net.pe/api-consulta-dni',
                'User-Agent' => 'laravel/http',
                'Accept' => 'application/json',
            ])->timeout(5)->get('https://api.apis.net.pe/v2/reniec/dni', [
                'numero' => $dni,
            ]);

            if ($response->ok()) {
                $result = $response->json();
                $name = $result['nombres'] . " " . $result['apellidoPaterno'] . " " . $result['apellidoMaterno'];

                if (empty(trim($name))) {
                    return response()->json([
                        'success' => false,
                        'error' => "No se encontraron resultados"
                    ])->getData();
                }

                if ($obtenerlista && $empresa->usarLista()) {
                    $pricetype = Pricetype::activos()->orderByDesc('default')
                        ->orderBy('id', 'asc')->first();
                }

                if ($autosaved) {
                    $cliente = Client::firstOrCreate(['document' => $dni], [
                        'name' => $name,
                        'sexo' => null,
                        'pricetype_id' => $empresa->usarLista() ? $pricetype->id : null
                    ]);
                    $cliente->load('pricetype');

                    return response()->json([
                        'success' => true,
                        'id' => $cliente->id,
                        'name' => $cliente->name,
                        'pricetype' => $cliente->pricetype,
                        'telefono' => null,
                        'direccion' => null,
                        'ubigeo_id' => null,
                        'distrito' => null,
                        'provincia' => null,
                        'region' => null,
                        'birthday' => false
                    ])->getData();
                }

                return response()->json([
                    'success' => true,
                    'name' => $name,
                    'pricetype' => $pricetype,
                    'telefono' => null,
                    'direccion' => null,
                    'ubigeo_id' => null,
                    'distrito' => null,
                    'provincia' => null,
                    'region' => null,
                    'birthday' => false
                ])->getData();
            }

            return response()->json([
                'success' => false,
                'error' => "No se encontraron resultados"
            ])->getData();
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

    public function consulta_ruc($ruc, $obtenerlista = false, $autosaved = false)
    {
        try {
            $empresa = view()->shared('empresa');
            $pricetype = null;
            $token = config('services.apisnet.token');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Referer' => 'https://apis.net.pe/api-consulta-ruc',
                'User-Agent' => 'laravel/http',
                'Accept' => 'application/json',
            ])->timeout(5)->get('https://api.apis.net.pe/v2/sunat/ruc', [
                'numero' => $ruc,
            ]);

            if ($response->ok()) {
                $result = $response->json();
                $name = $result['razonSocial'];

                if (empty(trim($name))) {
                    return response()->json([
                        'success' => false,
                        'error' => "No se encontraron resultados"
                    ])->getData();
                }

                if ($obtenerlista && $empresa->usarLista()) {
                    $pricetype = Pricetype::activos()->orderByDesc('default')
                        ->orderBy('id', 'asc')->first();
                }

                $ubigeo = null;
                if (isset($result['ubigeo']) && !empty($result['ubigeo'])) {
                    $ubigeo = Ubigeo::query()->select('id', 'distrito', 'provincia', 'region', 'ubigeo_inei')
                        ->where('ubigeo_inei', $result['ubigeo'])->take(1)->first();
                }

                if ($autosaved) {
                    $cliente = Client::firstOrCreate(['document' => $ruc], [
                        'name' => $name,
                        'sexo' => Client::EMPRESA,
                        'pricetype_id' => $empresa->usarLista() ? $pricetype->id : null
                    ]);

                    if (isset($result['direccion']) && !empty($result['direccion'])) {
                        $cliente->direccions()->create([
                            'name' => mb_strtoupper($result['direccion'], "UTF-8"),
                            'ubigeo_id' => !empty($ubigeo) ? $ubigeo->id : null,
                            'default' => Direccion::DEFAULT,
                        ]);
                    }
                    $cliente->load(['pricetype', 'direccions.ubigeo']);

                    $direccion = null;
                    if (count($cliente->direccions) > 0) {
                        $direccion = $cliente->direccions->first();
                    }

                    return response()->json([
                        'success' => true,
                        'id' => $cliente->id,
                        'name' => $cliente->name,
                        'pricetype' => $cliente->pricetype,
                        'telefono' => null,
                        'direccion' => empty($direccion) ? $direccion->name : null,
                        'ubigeo_id' => !empty($direccion) ? $direccion->ubigeo_id : null,
                        'distrito' => !empty($direccion) && !is_null($direccion->ubigeo_id) ? $direccion->ubigeo->distrito : null,
                        'provincia' => !empty($direccion) && !is_null($direccion->ubigeo_id) ? $direccion->ubigeo->provincia : null,
                        'region' => !empty($direccion) && !is_null($direccion->ubigeo_id) ? $direccion->ubigeo->region : null,
                        'birthday' => false
                    ])->getData();
                }

                return response()->json([
                    'success' => true,
                    'name' => $name,
                    'pricetype' => $pricetype,
                    'telefono' => null,
                    'direccion' => isset($result['direccion']) && !empty($result['direccion']) ? $result['direccion'] : null,
                    'ubigeo_id' => !empty($ubigeo) ? $ubigeo->id : null,
                    'distrito' => !empty($ubigeo) ? $ubigeo->distrito : null,
                    'provincia' => !empty($ubigeo) ? $ubigeo->provincia : null,
                    'region' => !empty($ubigeo) ? $ubigeo->region : null,
                    'birthday' => false
                ])->getData();
            }

            return response()->json([
                'success' => false,
                'error' => "No se encontraron resultados"
            ])->getData();
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

    public function consulta_cliente_bd($document, $obtenerlista = false, $autosaved = false,)
    {
        try {
            $empresa = view()->shared('empresa');
            $pricetype = null;
            $birthday = false;

            $cliente = Client::withTrashed()->with(['direccions' => function ($query) {
                $query->withTrashed()->with('ubigeo')->orderByDesc('default');
            }])->where('document', $document)->first();

            if ($obtenerlista && $empresa->usarLista()) {
                $pricetype = Pricetype::activos()->orderByDesc('default')
                    ->orderBy('id', 'asc')->first();
            }

            if ($cliente) {
                if ($autosaved && $cliente->trashed()) {
                    $cliente->restore();
                    $cliente->direccions()->restore();
                }

                if ($autosaved && is_null($cliente->pricetype_id)) {
                    $cliente->pricetype_id = $empresa->usarLista() ? $pricetype->id : null;
                    $cliente->save();
                }
                if ($cliente->nacimiento) {
                    $birthday = Carbon::parse($cliente->nacimiento)->format('m-d') == Carbon::now()->format('m-d') ? true : false;
                }

                $direccion = null;
                if (count($cliente->direccions) > 0) {
                    $direccion = $cliente->direccions->first();
                }

                // $cliente->load(['pricetype', 'direccions']);
                return response()->json([
                    'success' => true,
                    'id' => $cliente->id,
                    'name' => $cliente->name,
                    'pricetype' => $cliente->pricetype,
                    'telefono' => count($cliente->telephones) > 0 ? $cliente->telephones->first()->phone : null,
                    'direccion' => !empty($direccion) ? $direccion->name : null,
                    'ubigeo_id' => !empty($direccion) ? $direccion->ubigeo_id : null,
                    'distrito' => !empty($direccion) && !is_null($direccion->ubigeo_id) ? $direccion->ubigeo->distrito : null,
                    'provincia' => !empty($direccion) && !is_null($direccion->ubigeo_id) ? $direccion->ubigeo->provincia : null,
                    'region' => !empty($direccion) && !is_null($direccion->ubigeo_id) ? $direccion->ubigeo->region : null,
                    'birthday' => $birthday
                ])->getData();
            }
            return null;
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

    public function consulta_solo_ruc($ruc, $obtenerlista = false, $autosaved = false, $savedireccions = false)
    {

        $empresa = view()->shared('empresa');
        $pricetype = null;
        $config = [
            'representantes_legales'     => true,
            'cantidad_trabajadores'     => false,
            'establecimientos'             => true,
            'deuda'                     => false,
        ];

        $sunat = new ruc($config);
        $response = $sunat->consulta($ruc);
        // return json_decode($response, true);

        if (isset($response->success) && $response->success) {
            $ubigeo = null;
            if (!empty($response->result->distrito)) {
                $ubigeo = Ubigeo::query()->select('id', 'distrito', 'provincia', 'region')
                    ->whereRaw('LOWER(distrito) = ?', [strtolower(trim($response->result->distrito))])
                    ->whereRaw('LOWER(provincia) = ?', [strtolower(trim($response->result->provincia))])
                    ->take(1)->first();
            }

            if ($obtenerlista && $empresa->usarLista()) {
                $pricetype = Pricetype::activos()->orderByDesc('default')
                    ->orderBy('id', 'asc')->first();
            }

            if ($autosaved) {
                $cliente = Client::firstOrCreate(['document' => $ruc], [
                    'name' => $response->result->razon_social,
                    'sexo' => Client::EMPRESA,
                    'pricetype_id' => $empresa->usarLista() ? $pricetype->id : null
                ]);

                if (array_key_exists('direccion', (array) $response->result) && !empty($response->result->direccion)) {
                    $cliente->direccions()->firstOrCreate([
                        'name' => mb_strtoupper($response->result->direccion, "UTF-8")
                    ], [
                        'default' => $cliente->direccions()->default()->exists() ? 0 : Direccion::DEFAULT,
                        'ubigeo_id' => !empty($ubigeo) ? $ubigeo->id : null,
                    ]);
                }

                if ($savedireccions && array_key_exists('establecimientos', (array) $response->result) && count((array) $response->result->establecimientos) > 0) {
                    foreach ($response->result->establecimientos as $local) {
                        $ubigeolocal = null;
                        if (!empty($local->distrito)) {
                            $ubigeolocal = Ubigeo::query()->select('id', 'distrito', 'provincia')
                                ->whereRaw('LOWER(distrito) = ?', [strtolower(trim($local->distrito))])
                                ->whereRaw('LOWER(provincia) = ?', [strtolower(trim($local->provincia))])
                                ->take(1)->first();
                        }

                        $cliente->direccions()->firstOrCreate([
                            'name' => mb_strtoupper($local->direccion, "UTF-8")
                        ], [
                            'ubigeo_id' => !empty($ubigeolocal) ? $ubigeolocal->id : null,
                        ]);
                    }
                }
                $cliente->load(['pricetype', 'direccions.ubigeo']);

                $direccion = null;
                if (count($cliente->direccions) > 0) {
                    $direccion = $cliente->direccions->first();
                }

                return response()->json([
                    'success' => true,
                    'id' => $cliente->id,
                    'name' => $cliente->name,
                    'pricetype' => $cliente->pricetype,
                    'telefono' => null,
                    'estado' => $response->result->estado ?? null,
                    'condicion' => $response->result->condicion ?? null,
                    'direccion' => !empty($direccion) ? $direccion->name : null,
                    'ubigeo_id' => !empty($direccion) ? $direccion->ubigeo_id : null,
                    'distrito' => !empty($direccion) && !is_null($direccion->ubigeo_id) ? $direccion->ubigeo->distrito : null,
                    'provincia' => !empty($direccion) && !is_null($direccion->ubigeo_id) ? $direccion->ubigeo->provincia : null,
                    'region' => !empty($direccion) && !is_null($direccion->ubigeo_id) ? $direccion->ubigeo->region : null,
                    'birthday' => false,
                    'establecimientos' => $response->result->establecimientos,
                ])->getData();
            }

            return response()->json([
                'success' => true,
                'name' => $response->result->razon_social,
                'pricetype' => $pricetype,
                'telefono' => null,
                'estado' => $response->result->estado ?? null,
                'condicion' => $response->result->condicion ?? null,
                'direccion' => $response->result->direccion ?? null,
                'ubigeo_id' => !empty($ubigeo) ? $ubigeo->id : null,
                'distrito' => !empty($ubigeo) ? $ubigeo->distrito : null,
                'provincia' => !empty($ubigeo) ? $ubigeo->provincia : null,
                'region' => !empty($ubigeo) ? $ubigeo->region : null,
                'birthday' => false,
                'establecimientos' => $response->result->establecimientos,
            ])->getData();

            // return response()->json([
            //     'success' => true,
            //     'result' => [
            //         'ruc' => $response->result->ruc,
            //         'razon_social' => $response->result->razon_social,
            //         'nombre_comercial' => $response->result->nombre_comercial,
            //         'direccion' => $response->result->direccion,
            //         'departamento' => $response->result->departamento,
            //         'provincia' => $response->result->provincia,
            //         'distrito' => $response->result->distrito,
            //         'estado' => $response->result->estado,
            //         'condicion' => $response->result->condicion,
            //         'establecimientos' => $response->result->establecimientos,
            //         'ubigeo_id' => $ubigeo_id,
            //     ]
            // ])->getData();
        } else {
            return response()->json([
                'success' => false,
                'error' => $response->message ?? 'No se encontraron resultados',
            ])->getData();
        }

        return $response;
    }

    public function tipocambio($service = 'sbs')
    {
        if ($service == 'sbs') {
            return Self::tipocambiosbs();
        } else {
            return Self::tipocambiosunat();
        }
    }

    public function tipocambiosbs()
    {
        try {
            $token = config('services.apisnet.token');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Referer' => 'https://apis.net.pe/tipo-de-cambio-sunat-api',
                'User-Agent' => 'laravel/http',
                'Accept' => 'application/json',
            ])->timeout(5)->get('https://api.apis.net.pe/v2/sbs/tipo-cambio');

            if ($response->ok()) {
                $result = $response->json();
                return response()->json([
                    'success' => true,
                    'compra' => $result['precioCompra'],
                    'venta' => $result['precioVenta'],
                    'moneda' => $result['moneda'],
                    'fecha' => $result['fecha'],
                ])->getData();
            }

            return response()->json([
                'success' => false,
                'error' => "No se encontraron resultados"
            ])->getData();
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

    public function tipocambiosunat()
    {
        $tc = new tipo_cambio();
        $result = $tc->ultimo_tc();

        if ($result->success) {
            $json = [
                'success' => true,
                'compra' => $result->result->compra,
                'venta' => $result->result->venta,
                'fecha' => $result->result->fecha,
                'moneda' => null,
            ];
        } else {
            $json = [
                'success' => false,
                'message' => $result->message
            ];
        }
        return response()->json($json);
    }
}
