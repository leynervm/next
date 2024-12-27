<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Rules\ValidateDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
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
                ->orderBy('default', 'desc')->limit(1);
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

        $rules = [
            'document'  => [
                'required',
                'numeric',
                new ValidateDocument,
                'regex:/^\d{8}(?:\d{3})?$/',
            ]
        ];

        $validator = Validator::make($request->all(), $rules);
        // $validator->setAttributeNames($attributes);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ])->getData();
        }

        $document = trim($request->input('document'));
        $autosaved = filter_var($request->input('autosaved'), FILTER_VALIDATE_BOOLEAN);
        $searchbd = filter_var($request->input('searchbd'), FILTER_VALIDATE_BOOLEAN);

        if ($searchbd) {
            $cliente = self::consulta_cliente_bd($document);
            if (!empty($cliente)) {
                return $cliente;
            }
        }

        if (strlen(trim($document)) == 8) {
            return self::consulta_dni($document, $autosaved);
        }

        if (strlen(trim($document)) == 11) {
            return self::consulta_ruc($document, $autosaved);
        }

        return response()->json([
            'success' => false,
            'error' => "Documento inválido",
        ])->getData();
    }

    public function consulta_dni($dni, $autosaved = false)
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

                if ($empresa->usarLista()) {
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
                    ])->getData();
                }

                return response()->json([
                    'success' => true,
                    'name' => $name,
                    'pricetype' => $pricetype,
                    'telefono' => null,
                    'direccion' => null,
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

    public function consulta_ruc($ruc, $autosaved = false)
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

                if ($empresa->usarLista()) {
                    $pricetype = Pricetype::activos()->orderByDesc('default')
                        ->orderBy('id', 'asc')->first();
                }

                if ($autosaved) {
                    $cliente = Client::firstOrCreate(['document' => $ruc], [
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
                    ])->getData();
                }

                return response()->json([
                    'success' => true,
                    'name' => $name,
                    'pricetype' => $pricetype,
                    'telefono' => null,
                    'direccion' => null,
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

    public function consulta_cliente_bd($document)
    {
        try {
            $empresa = view()->shared('empresa');
            $pricetype = null;

            $cliente = Client::withTrashed()->with(['pricetype' => function ($query) {
                $query->select('id', 'name', 'decimals', 'rounded');
            }, 'direccions' => function ($query) {
                $query->withTrashed()->orderByDesc('default');
            }])->where('document', $document)->first();

            if ($empresa->usarLista()) {
                $pricetype = Pricetype::activos()->orderByDesc('default')
                    ->orderBy('id', 'asc')->first();
            }

            if ($cliente) {
                if ($cliente->trashed()) {
                    $cliente->pricetype_id = $empresa->usarLista() ? $pricetype->id : null;
                    $cliente->save();
                    $cliente->direccions()->restore();
                    $cliente->telephones()->restore();
                }

                return response()->json([
                    'success' => true,
                    'id' => $cliente->id,
                    'name' => $cliente->name,
                    'pricetype' => $cliente->pricetype,
                    'telefono' => count($cliente->telephones) > 0 ? $cliente->telephones->first()->name : null,
                    'direccion' => count($cliente->direccions) > 0 ? $cliente->direccions->first()->name : null,
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
}
