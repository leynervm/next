<?php

namespace Modules\Marketplace\Http\Controllers;

use App\Enums\MethodPaymentOnlineEnum;
use App\Enums\StatusPayWebEnum;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Modules\Marketplace\Entities\Order;
use Modules\Marketplace\Entities\Transaccion;

class NiubizController extends Controller
{
    public function checkout(Request $request)
    {

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
                'amount' => $request->amount,
                'currency' => config('services.niubiz.currency'),
            ],
            'yape' => [
                'phoneNumber' => '928393901',
                'otp' => '654321'
            ]
        ])->json();


        DB::beginTransaction();
        try {

            if (isset($response)) {
                if (isset($response['dataMap']) && $response['dataMap']['ACTION_CODE'] == '000') {
                    $transaccion = Transaccion::create([
                        'date' => now()->createFromFormat('ymdHis', $response['dataMap']['TRANSACTION_DATE'])->format('d/m/Y H:i:s'),
                        'amount' => isset($response['dataMap']['AMOUNT']) ? $response['dataMap']['AMOUNT'] : null,
                        'currency' => isset($response['order']['currency']) ? $response['order']['currency'] : null,
                        'eci_description' => $response['dataMap']['ECI_DESCRIPTION'],
                        'action_description' => $response['dataMap']['ACTION_DESCRIPTION'],
                        'transaction_id' => $response['dataMap']['TRANSACTION_ID'],
                        'card' => $response['dataMap']['CARD'],
                        'card_type' => $response['dataMap']['CARD_TYPE'],
                        'status' => $response['dataMap']['STATUS'],
                        'action_code' => $response['dataMap']['ACTION_CODE'],
                        'brand' => $response['dataMap']['BRAND'],
                        'email' => isset($response['dataMap']['VAULT_BLOCK']) ?  $response['dataMap']['VAULT_BLOCK'] : null,
                        'order_id' => $request->orderId,
                        'user_id' => auth()->user()->id
                    ]);

                    $order = Order::find($request->orderId);
                    if ($order) {
                        $order->status = StatusPayWebEnum::PAGO_CONFIRMADO->value;
                        $methodpay = strtoupper($response['dataMap']['CARD_TYPE']) == "C" ? MethodPaymentOnlineEnum::TARJETA_CREDITO->value : MethodPaymentOnlineEnum::TARJETA_DEBITO->value;
                        $methodpay = (isset($response['dataMap']['YAPE_ID']) && !empty($response['dataMap']['YAPE_ID'])) ? MethodPaymentOnlineEnum::YAPE->value : $methodpay;
                        $order->methodpay = $methodpay;
                        $order->save();
                    }
                } else {
                    $transaccion = Transaccion::create([
                        'date' => isset($response) && $response['data']['TRANSACTION_DATE'] ? now()->createFromFormat('ymdHis', $response['data']['TRANSACTION_DATE'])->format('d/m/Y H:i:s') : now('America/Lima'),
                        'amount' => isset($response) && $response['data']['AMOUNT'] ?  $response['data']['AMOUNT'] : null,
                        'currency' => config('services.niubiz.currency'),
                        'eci_description' => isset($response) && $response['data']['ECI_DESCRIPTION'] ?  $response['data']['ECI_DESCRIPTION'] : null,
                        'action_description' => isset($response) && $response['data']['ACTION_DESCRIPTION'] ?  $response['data']['ACTION_DESCRIPTION'] : null,
                        'transaction_id' => isset($response) && $response['data']['TRANSACTION_ID'] ?  $response['data']['TRANSACTION_ID'] : null,
                        'card' => isset($response) && $response['data']['CARD'] ?  $response['data']['CARD'] : null,
                        'card_type' => isset($response) && $response['data']['CARD_TYPE'] ?  $response['data']['CARD_TYPE'] : null,
                        'status' => isset($response) && $response['data']['STATUS'] ?  $response['data']['STATUS'] : null,
                        'action_code' => isset($response) && $response['data']['ACTION_CODE'] ?  $response['data']['ACTION_CODE'] : null,
                        'brand' => isset($response) && $response['data']['BRAND'] ?  $response['data']['BRAND'] : null,
                        'email' => isset($response['data']['VAULT_BLOCK']) ?  $response['data']['VAULT_BLOCK'] : null,
                        'order_id' => $request->orderId,
                        'user_id' => auth()->user()->id
                    ]);
                }
                DB::commit();
            }
            return redirect()->route('orders.payment', $request->orderId);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
