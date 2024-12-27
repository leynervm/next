<?php

namespace Modules\Marketplace\Http\Controllers;

use App\Enums\StatusPayWebEnum;
use App\Models\Almacen;
use App\Models\Promocion;
use App\Models\Tvitem;
use App\Rules\Recaptcha;
use Illuminate\Support\Str;
use CodersFree\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;
use Modules\Marketplace\Entities\Order;
use Modules\Marketplace\Entities\Shipmenttype;
use Modules\Marketplace\Entities\Trackingstate;
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
            Log::info('Mensaje de validación al registar venta virtual: ', $mensaje);
            return redirect()->route('carshoop.create')->with('message', response()->json($mensaje)->getData());
        }

        foreach ($shoppings as $item) {
            if (!$item->options->is_disponible) {
                $is_disponible = false;
                if (!empty($item->options->promocion_id)) {
                    $msjPrm = "PROMOCIÓN NO SE ENCUENTRA DISPONIBLE";
                } else {
                    $msjPrm = "PRODUCTO NO SE ENCUENTRA DISPONIBLE";
                }
            }
        }

        if (!$is_disponible) {
            $mensaje = [
                'title' => $msjPrm,
                'text' => null,
                'type' => 'error',
            ];
            Log::info('Mensaje de validación al registar venta virtual: ', $mensaje);
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
            'yape' => [
                'phoneNumber' => '969929157',
                'otp' => '557454'
            ]
        ])->json();

        if (isset($response)) {
            if (isset($response['dataMap']) && $response['dataMap']['ACTION_CODE'] == '000') {
                Log::info('Mensaje de validación al registar venta virtual: ', $response);
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

                    $order = Order::create([
                        'date' => now('America/Lima'),
                        'seriecompleta' => 'ORDER-',
                        'purchase_number' => $request->input('purchaseNumber'),
                        'exonerado' => decimalOrInteger(getAmountCart($shoppings)->total, 2),
                        'gravado' => 0,
                        'igv' => 0,
                        'subtotal' => decimalOrInteger(getAmountCart($shoppings)->total, 2),
                        'total' => decimalOrInteger(getAmountCart($shoppings)->total, 2),
                        'tipocambio' => number_format(view()->shared('empresa')->tipocambio ?? 0, 3, '.', ''),
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

                    foreach ($shoppings as $item) {
                        $order->tvitems()->create([
                            'date' => now('America/Lima'),
                            'cantidad' => decimalOrInteger($item->qty),
                            'pricebuy' => number_format($item->model->pricebuy, 2, '.', ''),
                            'price' => number_format($item->price, 2, '.', ''),
                            'igv' => number_format($item->options->igv, 2, '.', ''),
                            'subtotaligv' => number_format($item->options->subtotaligv, 2, '.', ''),
                            'subtotal' => number_format($item->subtotal, 2, '.', ''),
                            'total' => number_format($item->subtotal, 2, '.', ''),
                            'status' => 0,
                            'alterstock' => Almacen::NO_ALTERAR_STOCK,
                            'gratuito' => 0,
                            'promocion_id' => $item->options->promocion_id,
                            'almacen_id' => null,
                            'producto_id' => $item->id,
                            'user_id' => auth()->user()->id
                        ]);

                        if (count($item->options->carshoopitems) > 0) {
                            foreach ($item->options->carshoopitems as $carshoopitem) {
                                $itemcombo = [
                                    'date' => now('America/Lima'),
                                    'cantidad' => $item->qty,
                                    'pricebuy' => $carshoopitem->pricebuy,
                                    // 'price' => number_format($carshoopitem->price, 3, '.', ''),
                                    // 'igv' => number_format($carshoopitem->igv, 3, '.', ''),
                                    // 'subtotaligv' => number_format($subtotalItemIGVCombo, 3, '.', ''),
                                    // 'subtotal' => number_format($subtotalItemCombo, 3, '.', ''),
                                    // 'total' => number_format($totalItemCombo, 3, '.', ''),
                                    'price' => $item->price,
                                    'igv' => 0,
                                    'subtotaligv' => 0,
                                    'subtotal' => 0,
                                    'total' => 0,
                                    'status' => 0,
                                    'alterstock' => Almacen::DISMINUIR_STOCK,
                                    'gratuito' => Tvitem::GRATUITO,
                                    'increment' => 0,
                                    'promocion_id' => $item->options->promocion_id,
                                    'almacen_id' => null,
                                    'producto_id' => $carshoopitem->producto_id,
                                    'user_id' => auth()->user()->id
                                ];
                                $order->tvitems()->create($itemcombo);
                            }
                        }

                        if (!empty($item->options->promocion_id)) {
                            $promocion = Promocion::find($item->options->promocion_id);
                            $promocion->outs = $promocion->outs + $item->qty;
                            $promocion->save();
                        }
                        Cart::instance('shopping')->remove($item->rowId);
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

        $shipmenttype = Shipmenttype::find($request->input('datos.shipmenttype.id'));

        if (empty($shipmenttype)) {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'shipmenttype' => ['El campo tipo de envío es obligatorio.'],
                ],
            ]);
        }

        $validator = Validator::make($request->all(), [
            'datos.shipmenttype.id' => ['required', 'integer', 'min:1', 'exists:shipmenttypes,id'],
            'datos.local_entrega.id' => [
                'nullable',
                Rule::requiredIf($shipmenttype->isRecojotienda()),
                'integer',
                'min:1',
                'exists:sucursals,id'
            ],
            'datos.daterecojo' => [
                'nullable',
                Rule::requiredIf($shipmenttype->isRecojotienda()),
                'date',
                'after_or_equal:today'
            ],
            'datos.direccion_envio.id' => [
                'nullable',
                Rule::requiredIf($shipmenttype->isEnviodomicilio()),
                'integer',
                'min:1',
                'exists:direccions,id'
            ],
            'datos.receiver' => ['required', 'integer', Rule::in([Order::EQUAL_RECEIVER, Order::OTHER_RECEIVER])],
            'datos.receiver_info.document' => ['required', 'string', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/'],
            'datos.receiver_info.name' => ['required', 'string', 'min:8',],
            'datos.receiver_info.telefono' => ['required', 'numeric', 'digits:9', 'regex:/^\d{9}$/'],
            'datos.g_recaptcha_response' => ['required', new Recaptcha()],
            'datos.terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ]);

        // ->stopOnFirstFailure()
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ]); // 422 Unprocessable Entity
        }

        $datos_url_get = "shipmenttype_id=" . $shipmenttype->id . "&receiver=" . $request->input('datos.receiver');
        $datos_url_get .= "&receiver_document=" . $request->input('datos.receiver_info.document') . "&receiver_name=" . $request->input('datos.receiver_info.name');
        $datos_url_get .= "&receiver_telefono=" . $request->input('datos.receiver_info.telefono');
        $datos_url_get .= "&moneda_id=" . $request->input('datos.moneda_id');

        if ($shipmenttype->isRecojotienda()) {
            $datos_url_get .= "&local_entrega=" . $request->input('datos.local_entrega.id');
            $datos_url_get .= "&daterecojo=" . $request->input('datos.daterecojo');
        } else {
            $datos_url_get .= "&direccion_envio=" . $request->input('datos.direccion_envio.id');
        }

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

        // 'complete': function(params) {
        //         console.log(params.status);
        //         alert(JSON.stringify(params));
        //     }

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
