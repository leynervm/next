<?php

namespace App\Helpers;

use App\Models\Client;
use App\Models\Empresa;
use App\Models\Pricetype;
use App\Models\Ubigeo;
use Carbon\Carbon;
// use GuzzleHttp\Client as Http;
use Illuminate\Support\Facades\Http;

class GetClient
{

    protected $token, $urldni, $urlruc;

    public function __construct()
    {
        $this->token = 'apis-token-5457.W7D25zG9Y8YoT2TNITsVSpuESdmQJCGW';
        $this->urldni = 'https://api.apis.net.pe/v2/reniec/dni';
        $this->urlruc = 'https://api.apis.net.pe/v2/sunat/ruc';
    }

    public function getClient($document, $autosaved = true)
    {

        $usarlistaprecios = mi_empresa()->usarlista() ?? false;
        $bithday = false;
        $pricetype_id = null;
        $pricetypename = '';
        $ubigeo_id = null;
        $client_id = null;
        $response = array();

        if ($usarlistaprecios) {
            $listaprecios = Pricetype::orderBy('id', 'asc');
            if ($listaprecios->default()->exists()) {
                $pricetypeDefault = $listaprecios->first();
                $pricetype_id = $pricetypeDefault->id;
                $pricetypename = $pricetypeDefault->name;
            } else {
                if ($listaprecios->exists()) {
                    $pricetypeDefault = Pricetype::orderBy('id', 'asc')->first();
                    $pricetype_id = $pricetypeDefault->id;
                    $pricetypename = $pricetypeDefault->name;
                }
            }
        }

        $cliente = Client::with(['pricetype', 'direccions', 'telephones'])
            ->withTrashed()->where('document', $document)->first();

        if ($cliente) {
            if ($cliente->trashed()) {
                $cliente->restore();
                $cliente->pricetype_id = $pricetype_id;
                $cliente->save();
                $cliente->direccions()->restore();
                $cliente->telephones()->restore();
            } else {
                if ($usarlistaprecios) {
                    if ($cliente->pricetype_id) {
                        $pricetype_id = $cliente->pricetype_id;
                        $pricetypename = $cliente->pricetype->name;
                    }
                }
            }

            $direccion = $cliente->direccions()->first()->name ?? '';
            $ubigeo_id = $cliente->direccions()->first()->ubigeo_id ?? null;
            $telefono = $cliente->telephones()->first()->phone ?? null;

            if ($cliente->nacimiento) {
                $bithday = Carbon::parse($cliente->nacimiento)->format('m-d') == Carbon::now()->format('m-d') ? true : false;
            }

            $response = [
                'success' => true,
                'name' => $cliente->name,
                'pricetype_id' => $pricetype_id,
                'pricetypeasigned' => $pricetypename,
                'telefono' => $telefono,
                'birthday' => $bithday,
                'direccion' => strlen(trim($direccion)) < 3 ? '' : $direccion,
                'ubigeo_id' => $ubigeo_id,
                'client_id' => $cliente->id,
            ];
        } else {

            // Para usar la versión 1 de la api, cambiar a /v1/ruc
            $url = strlen(trim($document)) == 8 ? $this->urldni : $this->urlruc;
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
                    $ubigeo = isset($result->ubigeo) ? $result->ubigeo : null;

                    if (!empty($ubigeo)) {
                        $ubigeo_id = Ubigeo::where('ubigeo_inei', trim($ubigeo))
                            ->first()->id ?? null;
                    }

                    if ($autosaved) {
                        $client = Client::create([
                            'document' => $document,
                            'name' => $name,
                            'sexo' => strlen(trim($document)) == 11 ? 'E' : null,
                            'pricetype_id' => $pricetype_id,
                        ]);

                        if (!empty($direccion)) {
                            $client->direccions()->create([
                                'name' => $direccion,
                                'ubigeo_id' => $ubigeo_id,
                            ]);
                        }
                        $client_id = $client->id;
                    }

                    $response = [
                        'success' => true,
                        'name' => $name,
                        'pricetype_id' => $pricetype_id,
                        'pricetypeasigned' => $pricetypename,
                        'telefono' => null,
                        'estado' => isset($result->estado) ? $result->estado : null,
                        'condicion' => isset($result->condicion) ? $result->condicion : null,
                        'birthday' => $bithday,
                        'direccion' => strlen(trim($direccion)) < 3 ? null : $direccion,
                        'ubigeo_id' => $ubigeo_id,
                        'client_id' => $client_id,
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => 'No se pudo obtener datos del cliente.',
                ];
            }
        }

        // dd(response()->json($response));
        return response()->json($response);
    }

    public function getSunat($document)
    {
        $url = strlen(trim($document)) == 8 ? $this->urldni : $this->urlruc;
        $http = Http::withToken($this->token)->get($url, [
            'numero' => $document,
        ]);

        if ($http->getBody()) {
            $result = json_decode($http->getBody());

            if (isset($result->message)) {
                return response()->json([
                    'success' => false,
                    'message' => $result->message,
                ]);
            } else {

                $ubigeo_id = null;
                $ubigeo = isset($result->ubigeo) ? $result->ubigeo : null;
                $direccion = isset($result->direccion) ? $result->direccion : '';

                if (strlen(trim($document)) == 8) {
                    $name = $result->nombres . ' ' . $result->apellidoPaterno . ' ' . $result->apellidoMaterno;
                } else {
                    $name = $result->razonSocial;
                }

                if (!empty($ubigeo)) {
                    $ubigeo_id = Ubigeo::where('ubigeo_inei', trim($ubigeo))
                        ->first()->id ?? null;
                }

                return response()->json([
                    'success' => true,
                    'name' => $name,
                    'direccion' => strlen(trim($direccion)) < 3 ? null : $direccion,
                    'ubigeo_id' => $ubigeo_id,
                    'estado' => isset($result->estado) ? $result->estado : null,
                    'condicion' => isset($result->condicion) ? $result->condicion : null,
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener cliente.',
            ]);
        }
    }


    public function getTipoCambio()
    {
        $http = Http::withToken($this->token)->get('https://api.apis.net.pe/v2/sunat/tipo-cambio', [
            'date' => now('America/Lima')->format('Y-m-d'),
        ]);

        return json_decode($http->getBody());
    }
}
