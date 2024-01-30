<?php

namespace App\Helpers;

use App\Models\Client;
use App\Models\Empresa;
use App\Models\Pricetype;
use Carbon\Carbon;
// use GuzzleHttp\Client as Http;
use Illuminate\Support\Facades\Http;

class GetClient
{

    protected $token;

    public function __construct()
    {
        $this->token = 'apis-token-5457.W7D25zG9Y8YoT2TNITsVSpuESdmQJCGW';
    }

    public function getClient($document)
    {

        $uselistprice = mi_empresa()->uselistprice ?? 0;
        $bithday = false;
        $cliente = Client::withTrashed()->where('document', $document)->first();
        $response = array();

        if ($uselistprice == 1) {
            $pricetypes = Pricetype::default();
            if (count($pricetypes->get()) > 0) {
                $pricetypeDefault = $pricetypes->first();
            } else {
                $pricetypeDefault = Pricetype::first();
            }

            $pricetypeclient_id = $pricetypeDefault->id ?? null;
            $pricetypename = $pricetypeDefault->name ?? '';
        }

        if ($cliente) {
            if ($cliente->trashed()) {
                $cliente->restore();
                $cliente->pricetype_id = $uselistprice == 1 ?  $pricetypeDefault->id ?? null : null;
                $cliente->direccions()->restore();
                $cliente->telephones()->restore();
            }

            $direccion = $cliente->direccions()->first()->name ?? '';

            if ($uselistprice == 1) {
                if ($cliente->pricetype_id) {
                    $pricetypeclient_id = $cliente->pricetype_id;
                    $pricetypename = $cliente->pricetype->name;
                }
            }

            if ($cliente->nacimiento) {
                $bithday = Carbon::parse($cliente->nacimiento)->format('m-d') == Carbon::now()->format('m-d') ? true : false;
            }

            $response = [
                'success' => true,
                'name' => $cliente->name,
                'pricetype_id' => $pricetypeclient_id ?? null,
                'pricetypeasigned' => $pricetypename ?? '',
                'direccion' => strlen(trim($direccion)) < 3 ? '' : $direccion,
                'ubigeo' => $cliente->direccions()->first()->ubigeo->ubigeo_reniec ?? null,
                'telefono' => $cliente->telephones()->first()->phone ?? null,
                'birthday' => $bithday
            ];
        } else {

            // Para usar la versión 1 de la api, cambiar a /v1/ruc
            if (strlen(trim($document)) == 8) {
                $url = 'https://api.apis.net.pe/v2/reniec/dni';
            } else {
                $url = 'https://api.apis.net.pe/v2/sunat/ruc';
            }

            $http = Http::withToken($this->token)->get($url, [
                'numero' => $document,
            ]);

            if ($http->getBody()) {
                $result = json_decode($http->getBody());

                if (isset($result->message)) {
                    $response = [
                        'success' => false,
                        'message' => $result->message,
                    ];
                } else {

                    if (strlen(trim($document)) == 8) {
                        $name = $result->nombres . ' ' . $result->apellidoPaterno . ' ' . $result->apellidoMaterno;
                    } else {
                        $name = $result->razonSocial;
                    }

                    $direccion = isset($result->direccion) ? $result->direccion : '';

                    $response = [
                        'success' => true,
                        'name' => $name,
                        'pricetype_id' => $pricetypeclient_id ?? null,
                        'pricetypeasigned' => $pricetypename ?? null,
                        'direccion' => strlen(trim($direccion)) < 3 ? null : $direccion,
                        'ubigeo' => isset($result->ubigeo) ? $result->ubigeo : null,
                        'telefono' => null,
                        'estado' => isset($result->estado) ? $result->estado : null,
                        'condicion' => isset($result->condicion) ? $result->condicion : null,
                        'birthday' => $bithday
                    ];
                    // $response = $result;
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => 'No se pudo obtener cliente.',
                ];
            }
        }

        // dd(response()->json($response));
        return response()->json($response);
    }


    public function getTipoCambio()
    {
        $http = Http::withToken($this->token)->get('https://api.apis.net.pe/v2/sunat/tipo-cambio', [
            'date' => now('America/Lima')->format('Y-m-d'),
        ]);

        return json_decode($http->getBody());
    }
}
