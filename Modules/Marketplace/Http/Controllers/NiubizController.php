<?php

namespace Modules\Marketplace\Http\Controllers;

use App\Enums\StatusPayWebEnum;
use App\Mail\EnviarResumenOrder;
use App\Models\Almacen;
use App\Models\Client;
use App\Models\Promocion;
use App\Models\Sucursal;
use App\Models\Tvitem;
use App\Models\Typepayment;
use App\Rules\Recaptcha;
use Carbon\Carbon;
use Illuminate\Support\Str;
use CodersFree\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;
use Luecano\NumeroALetras\NumeroALetras;
use Modules\Marketplace\Entities\Order;
use Modules\Marketplace\Entities\Shipmenttype;
use Modules\Marketplace\Entities\Trackingstate;
use Nwidart\Modules\Facades\Module;
use Nwidart\Modules\Routing\Controller;

class NiubizController extends Controller
{
    public function checkout(Request $request)
    {

        $is_disponible = true;
        $msjPrm = '';
        $shoppings = getCartRelations('shopping', true);
        if (count($shoppings) == 0) {
            $mensaje = [
                'title' => 'CARRITO DE COMPRAS ESTÁ VACÍO',
                'text' => null,
                'type' => 'error',
            ];
            Log::info('Registrando venta virtual : ', $mensaje);
            return redirect()->route('carshoop.create')->with('message', response()->json($mensaje)->getData());
        }

        foreach ($shoppings as $item) {
            if (!$item->options->is_disponible || !$item->options->stock_disponible) {
                $is_disponible = false;
                if (!empty($item->options->promocion_id)) {
                    $msjPrm = "PROMOCIÓN NO SE ENCUENTRA DISPONIBLE";
                } else {
                    $msjPrm = "PRODUCTO NO SE ENCUENTRA DISPONIBLE";
                }
                break;
            }
        }

        if (!$is_disponible) {
            $mensaje = [
                'title' => $msjPrm,
                'text' => null,
                'type' => 'error',
            ];
            Log::info('Registrando venta virtual : ', $mensaje);
            return redirect()->route('carshoop.create')->with('message', response()->json($mensaje)->getData());
        }

        $auth = base64_encode(config('services.niubiz.user') . ':' . config('services.niubiz.password'));
        $accessToken = Http::withHeaders([
            'Authorization' => "Basic $auth",
            'Content-Type' => "application/json",
        ])->get(config('services.niubiz.url_api') . 'api.security/v1/security')->body();

        $response = Http::withHeaders([
            'Content-Type' => "application/json",
            'Authorization' => $accessToken,
        ])->post(config('services.niubiz.url_api') . 'api.authorization/v3/authorization/ecommerce/' . config('services.niubiz.merchant_id'), [
            'channel' => 'web',
            'captureType' => 'manual',
            'countable' => true,
            'order' => [
                'tokenId' => $request->transactionToken,
                'purchaseNumber' => $request->purchaseNumber,
                'amount' => decimalOrInteger(getAmountCart($shoppings)->total, 2),
                'currency' => config('services.niubiz.currency'),
            ],
            // 'yape' => [
            //     'phoneNumber' => '969929157',
            //     'otp' => '557454'
            // ]
        ])->json();

        if (isset($response)) {
            if (isset($response['dataMap']) && $response['dataMap']['ACTION_CODE'] == '000') {
                Log::info('Registrando venta virtual : ', $response['dataMap']);
                DB::beginTransaction();
                try {

                    $client = auth()->user()->client()->firstOrCreate(
                        ['document' => auth()->user()->document],
                        [
                            'name' => auth()->user()->name,
                            'email' => auth()->user()->email,
                            'pricetype_id' => getPricetypeAuth()->id ?? null,
                        ]
                    );

                    $default = 0;
                    if ($client->telephones()->count() == 0) {
                        $default = 1;
                    }

                    $client->telephones()->updateOrCreate([
                        'phone' => $request->input('receiver_telefono'),
                    ], [
                        'default' => $default
                    ]);

                    Log::info('Registrando orden de compra web');
                    $empresa = view()->shared('empresa');
                    $order = Order::create([
                        'date' => now('America/Lima'),
                        'seriecompleta' => 'ORDER-',
                        'purchase_number' => $request->input('purchaseNumber'),
                        'exonerado' => decimalOrInteger(getAmountCart($shoppings)->total, 2),
                        'gravado' => 0,
                        'igv' => 0,
                        'subtotal' => decimalOrInteger(getAmountCart($shoppings)->total, 2),
                        'total' => decimalOrInteger(getAmountCart($shoppings)->total, 2),
                        'tipocambio' => number_format($empresa->tipocambio ?? 0, 3, '.', ''),
                        'receiverinfo' => [
                            'document' => $request->input('receiver_document'),
                            'name' => $request->input('receiver_name'),
                            'telefono' => $request->input('receiver_telefono'),
                        ],
                        'direccion_id' => $request->has('direccion_envio') ? $request->input('direccion_envio') : null,
                        'moneda_id' => $request->input('moneda_id'),
                        'client_id' => $client->id,
                        'shipmenttype_id' => $request->input('shipmenttype_id'),
                        'status' => StatusPayWebEnum::PAGO_CONFIRMADO->value,
                        'pasarela' => 'NIUBIZ',
                        'user_id' => auth()->user()->id,
                    ]);
                    Log::info('Orden de compra web registrado correctamente');

                    $eci_description = "";
                    if (isset($response['dataMap']['ECI_DESCRIPTION']) && !empty($response['dataMap']['ECI_DESCRIPTION'])) {
                        $eci_description = $response['dataMap']['ECI_DESCRIPTION'];
                    }
                    if (isset($response['dataMap']['DEPOSIT_STATUS']) && !empty($response['dataMap']['DEPOSIT_STATUS'])) {
                        $eci_description = $response['dataMap']['DEPOSIT_STATUS'];
                    }

                    $order->transaccion()->create([
                        'date' => now()->createFromFormat('ymdHis', $response['dataMap']['TRANSACTION_DATE'])->format('d/m/Y H:i:s'),
                        'amount' => isset($response['dataMap']['AMOUNT']) ? $response['dataMap']['AMOUNT'] : null,
                        'currency' => isset($response['order']['currency']) ? $response['order']['currency'] : null,
                        'eci_description' => $eci_description,
                        'action_description' => $response['dataMap']['ACTION_DESCRIPTION'],
                        'transaction_id' => $response['dataMap']['TRANSACTION_ID'],
                        'card' => $response['dataMap']['CARD'],
                        'card_type' => isset($response['dataMap']['CARD_TYPE']) && !empty($response['dataMap']['CARD_TYPE']) ? $response['dataMap']['CARD_TYPE'] : null,
                        'status' => $response['dataMap']['STATUS'],
                        'action_code' => $response['dataMap']['ACTION_CODE'],
                        'brand' => $response['dataMap']['BRAND'],
                        'signature' => $response['dataMap']['SIGNATURE'],
                        'email' => isset($response['dataMap']['VAULT_BLOCK']) ?  $response['dataMap']['VAULT_BLOCK'] : null,
                        'user_id' => auth()->user()->id
                    ]);
                    Log::info('Transacción de compra web registrado correctamente');

                    $order->seriecompleta = $order->seriecompleta . $order->id;
                    $order->save();

                    if (Trackingstate::default()->exists()) {
                        $order->trackings()->create([
                            'date' => now(),
                            'descripcion' => 'PEDIDO REGISTRADO CORRECTAMENTE',
                            'trackingstate_id' => Trackingstate::default()->first()->id,
                            'user_id' => auth()->user()->id
                        ]);
                    }

                    if ($request->has('local_entrega')) {
                        $order->entrega()->create([
                            'date' => $request->input('daterecojo'),
                            'sucursal_id' => $request->input('local_entrega')
                        ]);
                    }

                    Log::info('Registrando TVITEMS de compra web');
                    foreach ($shoppings as $item) {
                        $pricesale = $item->price;
                        $igvsale = 0;
                        if ($empresa->isAfectacionIGV()) {
                            $priceIGV = getPriceIGV($pricesale, $empresa->igv);
                            $pricesale = $priceIGV->price;
                            $igvsale = $priceIGV->igv;
                        }

                        $tvitem = $order->tvitems()->create([
                            'date' => now('America/Lima'),
                            'cantidad' => decimalOrInteger($item->qty),
                            'pricebuy' => number_format($item->model->pricebuy, 2, '.', ''),
                            'price' => number_format($pricesale, 2, '.', ''),
                            'igv' => number_format($igvsale, 2, '.', ''),
                            'subtotaligv' => number_format($item->qty * $igvsale, 2, '.', ''),
                            'subtotal' => number_format($item->qty * $pricesale, 2, '.', ''),
                            'total' => number_format(($pricesale + $igvsale) * $item->qty, 2, '.', ''),
                            'alterstock' => Almacen::DISMINUIR_STOCK,
                            'gratuito' => Tvitem::NO_GRATUITO,
                            'promocion_id' => $item->options->promocion_id,
                            'producto_id' => $item->id,
                            'user_id' => auth()->user()->id
                        ]);

                        if (count($item->options->carshoopitems) > 0) {
                            foreach ($item->options->carshoopitems as $carshoopitem) {

                                $priceitem = $carshoopitem->price;
                                $igvitem = 0;

                                if ($empresa->isAfectacionIGV()) {
                                    $priceIGV = getPriceIGV($priceitem, $empresa->igv);
                                    $priceitem = $priceIGV->price;
                                    $igvitem = $priceIGV->igv;
                                }

                                $carshoopitem = $tvitem->carshoopitems()->create([
                                    'cantidad' => $item->qty,
                                    'pricebuy' => $carshoopitem->pricebuy,
                                    'price' => $priceitem,
                                    'igv' => $igvitem,
                                    'subtotaligv' => $item->qty * $igvitem,
                                    'subtotal' => $item->qty * $priceitem,
                                    'total' => ($priceitem + $igvitem) * $item->qty,
                                    'itempromo_id' => $carshoopitem->itempromo_id ?? null,
                                    'producto_id' => $carshoopitem->producto_id,
                                ]);
                                // $itemcombo = [
                                //     'date' => now('America/Lima'),
                                //     'cantidad' => $item->qty,
                                //     'pricebuy' => $carshoopitem->pricebuy,
                                //     'price' => $item->price,
                                //     'igv' => 0,
                                //     'subtotaligv' => 0,
                                //     'subtotal' => 0,
                                //     'total' => 0,
                                //     'status' => 0,
                                //     'alterstock' => Almacen::DISMINUIR_STOCK,
                                //     'gratuito' => Tvitem::GRATUITO,
                                //     'increment' => 0,
                                //     'promocion_id' => $item->options->promocion_id,
                                //     'almacen_id' => null,
                                //     'producto_id' => $carshoopitem->producto_id,
                                //     'user_id' => auth()->user()->id
                                // ];
                                // $order->tvitems()->create($itemcombo);
                            }
                        }

                        if (!empty($item->options->promocion_id)) {
                            $promocion = Promocion::find($item->options->promocion_id);
                            $promocion->outs = $promocion->outs + $item->qty;
                            $promocion->save();
                        }
                        Cart::instance('shopping')->remove($item->rowId);
                    }
                    Log::info('TVITEMS de compra web registrado correctamente');

                    $sumatorias = Tvitem::select(
                        DB::raw("COALESCE(SUM(total),0) as total"),
                        DB::raw("COALESCE(SUM(CASE WHEN igv > 0 AND gratuito = '0' THEN igv * cantidad ELSE 0 END),0) as igv"),
                        DB::raw("COALESCE(SUM(CASE WHEN igv > 0 AND gratuito = '1' THEN igv * cantidad ELSE 0 END), 0) as igvgratuito"),
                        DB::raw("COALESCE(SUM(CASE WHEN igv > 0 AND gratuito = '0' THEN price * cantidad ELSE 0 END), 0) as gravado"),
                        DB::raw("COALESCE(SUM(CASE WHEN igv = 0 AND gratuito = '0' THEN price * cantidad ELSE 0 END), 0) as exonerado"),
                        DB::raw("COALESCE(SUM(CASE WHEN gratuito = '1' THEN price * cantidad ELSE 0 END), 0) as gratuito")
                    )->where('tvitemable_id', $order->id)->where('tvitemable_type', Order::class)->first();

                    $order->exonerado = $sumatorias->exonerado;
                    $order->gravado = $sumatorias->gravado;
                    $order->igv = $sumatorias->igv;
                    if ($sumatorias->igv > 0) {
                        $order->subtotal = $sumatorias->gravado;
                        $order->total = $sumatorias->gravado +  $sumatorias->igv;
                    } else {
                        $order->subtotal = $sumatorias->exonerado;
                        $order->total = $sumatorias->exonerado;
                    }
                    $order->save();
                    Log::info('Totales de compra web actualizados correctamente');

                    if (Module::isEnabled('Facturacion')) {
                        Log::info('Generando comprobante electrónico');
                        $codecpe = $request->input('typecomprobante');
                        $sucursal = Sucursal::withWhereHas('seriecomprobantes', function ($query) use ($codecpe) {
                            $query->withWhereHas('typecomprobante', function ($subq) use ($codecpe) {
                                $subq->where('code', $codecpe);
                            });
                        })->orderByDesc('default')->first();

                        if (count($sucursal->seriecomprobantes) > 0) {
                            $typepayment = Typepayment::activos()->where('paycuotas', Typepayment::CONTADO)->first();
                            $clientecpe = Client::firstOrCreate([
                                'document' => $request->input('document_comprobante')
                            ], [
                                'name' => $request->input('name_comprobante')
                            ]);
                            $counter = 1;
                            $seriecomprobante = $sucursal->seriecomprobantes->first();
                            $numeracion = $empresa->isProduccion() ? $seriecomprobante->contador + 1 : $seriecomprobante->contadorprueba + 1;
                            $seriecompleta = $seriecomprobante->serie . '-' . $numeracion;
                            $leyenda = new NumeroALetras();
                            $currency_leyenda = 'NUEVOS SOLES';
                            if ($order->moneda->isDolar()) {
                                $currency_leyenda = 'DÓLARES';
                            }

                            $comprobante = $order->comprobante()->create([
                                'date' => now('America/Lima'),
                                'expire' => Carbon::parse(now('America/Lima'))->format('Y-m-d'),  //NO BIENE DE VENTA
                                'seriecompleta' => $seriecompleta,
                                'direccion' => null,
                                'observaciones' => 'REGISTRADO MEDIANTE TIENDA VIRTUAL',
                                'exonerado' => number_format($order->exonerado, 3, '.', ''),
                                'gravado' => number_format($order->gravado, 3, '.', ''),
                                'gratuito' => number_format(0, 3, '.', ''),
                                'inafecto' => number_format(0, 3, '.', ''),
                                'descuento' => number_format(0, 3, '.', ''),
                                'otros' => number_format(0, 3, '.', ''),
                                'igv' => number_format($order->igv, 3, '.', ''),
                                'igvgratuito' => number_format(0, 3, '.', ''),
                                'subtotal' => number_format($order->subtotal, 3, '.', ''),
                                'total' => number_format($order->total, 3, '.', ''),
                                'paymentactual' => number_format($order->total, 3, '.', ''),
                                'percent' => $empresa->igv,  //NO BIENE DE VENTA
                                'referencia' => 'ORDER #' . $order->purchase_number,
                                'leyenda' => $leyenda->toInvoice($order->total, 2, $currency_leyenda),  //NO BIENE DE VENTA
                                'sendmode' => $empresa->sendmode, //NO BIENE DE VENTA
                                'client_id' => $clientecpe->id,
                                'typepayment_id' => $typepayment->id,
                                'seriecomprobante_id' => $seriecomprobante->id,
                                'moneda_id' => $order->moneda_id,
                                'sucursal_id' => $sucursal->id,
                                'user_id' => auth()->user()->id,
                            ]);

                            Log::info('Registrando TVITEMS de comprobante electrónico');
                            foreach ($order->tvitems as $item) {
                                if ($item->gratuito) {
                                    $afectacion = $item->igv > 0 ? '15' : '21';
                                } else {
                                    $afectacion = $item->igv > 0 ? '10' : '20';
                                }

                                $codeafectacion = $item->igv > 0 ? '1000' : '9997';
                                $nameafectacion = $item->igv > 0 ? 'IGV' : 'EXO';
                                $typeafectacion = $item->igv > 0 ? 'VAT' : 'VAT';
                                $abreviatureafectacion = $item->igv > 0 ? 'S' : 'E';

                                $descripcion = $item->producto->name;
                                if ($item->promocion && $item->promocion->isCombo()) {
                                    $descripcion = $item->promocion->titulo;
                                }

                                $comprobante->facturableitems()->create([
                                    'item' => $counter,
                                    'descripcion' => $descripcion,
                                    'code' => $item->producto->code,
                                    'cantidad' => $item->cantidad,
                                    'price' => number_format($item->price, 3, '.', ''),
                                    'igv' => number_format($item->igv, 3, '.', ''),
                                    'subtotaligv' => number_format($item->subtotaligv, 3, '.', ''),
                                    'subtotal' => number_format($item->subtotal, 3, '.', ''),
                                    'total' => number_format($item->total, 3, '.', ''),
                                    'unit' => $item->producto->unit->code,
                                    'codetypeprice' => $item->gratuito ? '02' : '01', //01: Precio unitario (incluye el IGV) 02: Valor referencial unitario en operaciones no onerosas
                                    'afectacion' => $afectacion,
                                    'codeafectacion' => $item->gratuito ? '9996' : $codeafectacion,
                                    'nameafectacion' => $item->gratuito ? 'GRA' : $nameafectacion,
                                    'typeafectacion' => $item->gratuito ? 'FRE' : $typeafectacion,
                                    'abreviatureafectacion' => $item->gratuito ? 'Z' : $abreviatureafectacion,
                                    'percent' => $item->igv > 0 ? $empresa->igv : 0,
                                ]);
                                $counter++;
                            }
                            Log::info('Comprobante electrónico generado correctamente');
                        }
                    }

                    DB::commit();
                    // Cart::instance('shopping')->destroy();
                    if (auth()->check()) {
                        Cart::instance('shopping')->store(auth()->id());
                    }
                    $mensaje = [
                        'title' => $response['dataMap']['ACTION_DESCRIPTION'],
                        'text' => null,
                        'type' => 'success',
                    ];
                    Log::info('Respuesta del pago: ', $mensaje);
                    $mail = Mail::to($order->user->email)->send(new EnviarResumenOrder($order));
                    Log::info('Resumen de compra #' . $order->purchase_number . ' enviado al email ' . $order->user->email);
                    return redirect()->route('orders.payment', $order)->with('message', response()->json($mensaje)->getData());
                } catch (\ErrorException $e) {
                    $mensaje = [
                        'title' => 'ERROR AL OBTENER DATOS DEL PAGO, ' . $e->getMessage(),
                        'text' => null,
                        'type' => 'error',
                    ];
                    Log::info('Error de excepcion del pago: ', $mensaje);
                    DB::rollBack();
                    // throw $e;
                    return redirect()->route('carshoop.create')->with('message', response()->json($mensaje)->getData());
                } catch (\Exception  $e) {
                    $mensaje = [
                        'title' => $e->getMessage(),
                        'text' => null,
                        'type' => 'error',
                    ];
                    Log::info('Error de excepcion del pago: ', $mensaje);
                    DB::rollBack();
                    // throw $e;
                    return redirect()->route('carshoop.create')->with('message', response()->json($mensaje)->getData());
                }
            } else {
                if (isset($response['data']) && isset($response['data']['ACTION_DESCRIPTION'])) {
                    $mensaje = [
                        'title' => $response['data']['ACTION_DESCRIPTION'],
                        'text' => null,
                        'type' => 'warning',
                        'timer' => 3000,
                    ];
                    Log::info('Respuesta del pago: ', $mensaje);
                    return redirect()->route('carshoop.create')->with('message', response()->json($mensaje)->getData());
                } else if (isset($response['errorCode']) && isset($response['errorMessage'])) {
                    $mensaje = [
                        'title' => $response['errorMessage'],
                        'text' => null,
                        'type' => 'warning',
                        'timer' => 3000,
                    ];
                    Log::info('Respuesta del pago: ', $mensaje);
                    return redirect()->route('carshoop.create')->with('message', response()->json($mensaje)->getData());
                } else {
                    // dd($response);
                    $mensaje = [
                        'title' => 'ERROR DESCONOCIDO AL PROCESAR PAGO',
                        'text' => null,
                        'type' => 'warning',
                        'timer' => 3000,
                    ];
                    Log::info('Respuesta del pago: ', $mensaje);
                    return redirect()->route('carshoop.create')->with('message', response()->json($mensaje)->getData());
                }
            }
        } else {
            $mensaje = [
                'title' => 'NO SE PUDO PROCESAR EL PAGO',
                'text' => null,
                'type' => 'error',
                'timer' => 3000,
            ];
            Log::info('Respuesta del pago: ', $mensaje);
            return redirect()->route('carshoop.create')->with('message', response()->json($mensaje)->getData());
        }
    }

    public function token()
    {

        $shoppings = getCartRelations('shopping', true);
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
            'amount' => decimalOrInteger(getAmountCart($shoppings)->total, 2),
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

    public function config_checkout(Request $request)
    {

        $shipmenttype = Shipmenttype::find($request->input('shipmenttype.id'));

        if (empty($shipmenttype)) {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'shipmenttype' => ['El campo tipo de envío es obligatorio.'],
                ],
            ]);
        }

        $validator = Validator::make($request->all(), [
            'shipmenttype.id' => ['required', 'integer', 'min:1', 'exists:shipmenttypes,id'],
            'local_entrega.id' => [
                'nullable',
                Rule::requiredIf($shipmenttype->isRecojotienda()),
                'integer',
                'min:1',
                'exists:sucursals,id'
            ],
            'daterecojo' => [
                'nullable',
                Rule::requiredIf($shipmenttype->isRecojotienda()),
                'date',
                'after_or_equal:today'
            ],
            'direccion_envio.id' => [
                'nullable',
                Rule::requiredIf($shipmenttype->isEnviodomicilio()),
                'integer',
                'min:1',
                'exists:direccions,id'
            ],
            'receiver' => ['required', 'integer', Rule::in([Order::EQUAL_RECEIVER, Order::OTHER_RECEIVER])],
            'receiver_info.document' => ['required', 'string', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/'],
            'receiver_info.name' => ['required', 'string', 'min:8',],
            'receiver_info.telefono' => ['required', 'numeric', 'digits:9', 'regex:/^\d{9}$/'],
            'g_recaptcha_response' => ['required', new Recaptcha()],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [], [
            'g_recaptcha_response' => 'verificación de seguridad'
        ]);

        // ->stopOnFirstFailure()
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ]); // 422 Unprocessable Entity
        }

        $datos_url_get = "shipmenttype_id=" . $shipmenttype->id . "&receiver=" . $request->input('receiver');
        $datos_url_get .= "&receiver_document=" . $request->input('receiver_info.document') . "&receiver_name=" . $request->input('receiver_info.name');
        $datos_url_get .= "&receiver_telefono=" . $request->input('receiver_info.telefono');
        $datos_url_get .= "&moneda_id=" . $request->input('moneda_id');

        if ($shipmenttype->isRecojotienda()) {
            $datos_url_get .= "&local_entrega=" . $request->input('local_entrega.id');
            $datos_url_get .= "&daterecojo=" . $request->input('daterecojo');
        } else {
            $datos_url_get .= "&direccion_envio=" . $request->input('direccion_envio.id');
        }

        $datos_url_get .= "&typecomprobante=" . $request->input('typecomprobante');
        $datos_url_get .= "&document_comprobante=" . $request->input('document_comprobante');
        $datos_url_get .= "&name_comprobante=" . $request->input('name_comprobante');

        do {
            $purchasenumber = mt_rand(100000000, 999999999);
        } while (DB::table('orders')->where('purchase_number', $purchasenumber)->exists());

        $empresa =  view()->shared('empresa');
        $shoppings = getCartRelations('shopping', true);
        $config = [
            'sessiontoken' => $this->token(),
            'channel' => 'web',
            'merchantid' => config('services.niubiz.merchant_id'),
            'purchasenumber' => $purchasenumber,
            'amount' => decimalOrInteger(getAmountCart($shoppings)->total, 2),
            'expirationminutes' => 5,
            'timeouturl' =>  route('carshoop.create'),
            'merchantlogo' => !empty($empresa->logo) ? getLogoEmpresa($empresa->logo, request()->isSecure() ? true : false) : null,
            'merchantname' => $empresa->name,
            'buttoncolor' => '#ffffff',
            'formbuttoncolor' => '#0e7e7e',
            'usertoken' =>  Str::uuid(),
            'hidexbutton' =>  true,
            'action' => route('orders.niubiz.checkout') . "?purchaseNumber=$purchasenumber&$datos_url_get",
        ];

        return response()->json($config);
    }

    function generatePurchaseNumber($length = 10)
    {
        do {
            $purchasenumber = '';
            for ($i = 0; $i < $length; $i++) {
                $purchasenumber .= random_int(0, 9);
            }

            $exists = Order::where('purchase_number', $purchasenumber)->exists();
        } while ($exists);

        return $purchasenumber;
    }
}
