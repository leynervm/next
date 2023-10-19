<?php

namespace App\Helpers;

use App\Models\Client;
use App\Models\Pricetype;
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

        $cliente = Client::where('document', $document)->first();
        $response = array();

        if ($cliente) {
            $response = [
                'success' => true,
                'name' => $cliente->name,
                'pricetype_id' => $cliente->pricetype_id,
                'pricetypeasigned' => $cliente->pricetype->name ?? '',
                'direccion' => $cliente->direccions()->first()->name ?? '',
                'ubigeo' => $cliente->direccions()->first()->ubigeo->ubigeo_reniec ?? null,
                'telefono' => $cliente->telephones()->first()->phone ?? null
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

                    $pricetypeDefault = Pricetype::DefaultPricetype()->first();

                    if (strlen(trim($document)) == 8) {
                        $name = $result->nombres . ' ' . $result->apellidoPaterno . ' ' . $result->apellidoMaterno;
                    } else {
                        $name = $result->razonSocial;
                    }

                    $response = [
                        'success' => true,
                        'name' => $name,
                        'pricetype_id' => $pricetypeDefault->id ?? null,
                        'pricetypeasigned' => $pricetypeDefault->name ?? '',
                        'direccion' => isset($result->direccion) ? $result->direccion : null,
                        'ubigeo' => isset($result->ubigeo) ? $result->ubigeo : null,
                        'telefono' => null,
                        'estado' => isset($result->estado) ? $result->estado : null,
                        'condicion' => isset($result->condicion) ? $result->condicion : null,
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
