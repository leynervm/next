<?php

namespace App\Helpers\Facturacion;

use App\Models\Empresa;
use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use ZipArchive;

class SendXML
{
    public function enviarComprobante($emisor, $nombre, $rutacertificado = "", $pass_firma, $ruta_archivo_xml = "xml/", $ruta_archivo_cdr = "cdr/")
    {
        $objFirma = new Signature();
        $flg_firma = 0;
        $ruta = $ruta_archivo_xml . $nombre . '.xml';
        // $ruta_firma = $rutacertificado . 'certificado_prueba_sunat.pfx';
        $result = $objFirma->signatureXML($flg_firma, $ruta, $rutacertificado, $pass_firma);

        if ($result->respuesta) {
            $zip = new ZipArchive();
            $nombrezip = $nombre . '.zip';
            $rutazip = $ruta_archivo_xml . $nombrezip;

            if ($zip->open($rutazip, ZipArchive::CREATE) == TRUE) {
                $zip->addFile($ruta, $nombre . '.xml');
                $zip->close();
            }

            if ($emisor->isProduccion()) {
                $ws = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService";
            } else {
                $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
            }

            $xml_envio = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasisopen.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                <soapenv:Header>
                    <wsse:Security>
                        <wsse:UsernameToken>
                            <wsse:Username>' . $emisor->document . $emisor->usuariosol . '</wsse:Username>
                            <wsse:Password>' . $emisor->clavesol . '</wsse:Password>
                        </wsse:UsernameToken>
                    </wsse:Security>
                </soapenv:Header>
                <soapenv:Body>
                    <ser:sendBill>
                        <fileName>' . $nombrezip . '</fileName>
                        <contentFile>' . base64_encode(file_get_contents($rutazip)) . '</contentFile>
                    </ser:sendBill>
                </soapenv:Body>
            </soapenv:Envelope>';

            $header = array(
                "Content-type: text/xml; charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "SOAPAction: ",
                "Content-lenght: " . strlen($xml_envio)
            );

            $response = Http::withOptions([
                'verify' => true,
                'timeout' => 30,
                'curl' => [
                    // CURLOPT_CAINFO => storage_path('app/cert/cacert.pem'),
                ],
            ])->withHeaders($header)->withBody($xml_envio, 'application/xml')
                ->post($ws);

            if ($response->status() == 200) {

                $doc = new DOMDocument();
                $doc->loadXML($response->body());

                if (isset($doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue)) {
                    $cdr = base64_decode($doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue);
                    file_put_contents($ruta_archivo_cdr . "R-" . $nombrezip, $cdr);
                    $zip = new ZipArchive();
                    if ($zip->open($ruta_archivo_cdr . 'R-' . $nombrezip) == TRUE) {
                        $zip->extractTo($ruta_archivo_cdr, 'R-' . $nombre . '.xml');
                        $zip->close();
                    }

                    $codeResponse = getValueNode($ruta_archivo_cdr . 'R-' . $nombre . '.xml', 'ResponseCode');
                    $descripcion = getValueNode($ruta_archivo_cdr . 'R-' . $nombre . '.xml', 'Description');
                    $notes = getNotesNode($ruta_archivo_cdr . 'R-' . $nombre . '.xml');

                    $mensaje = response()->json([
                        'codRespuesta' => $response->status(),
                        'code' => $codeResponse,
                        'descripcion' => $descripcion,
                        'notes' => $notes
                    ]);
                } else {
                    // dd($codigo, $mensaje);
                    $mensaje = response()->json([
                        'codRespuesta' => $response->status(),
                        'code' => $doc->getElementsByTagName('faultcode')->item(0)->nodeValue,
                        'descripcion' => $doc->getElementsByTagName('faultstring')->item(0)->nodeValue
                    ]);
                }
            } else {
                $mensaje = response()->json([
                    'codRespuesta' => $response->status(),
                    'code' => $response->status(),
                    'descripcion' => $response->body()
                ]);
                // dd(curl_error($ch), "</br> Problema de conexión");
            }
        } else {
            $mensaje = response()->json([
                'codRespuesta' => 'Error',
                'code' => $result->code,
                'descripcion' => $result->mensaje
            ]);
        }

        return $mensaje->getData();
    }



    public function enviarGuia($emisor, $nombre, $rutacertificado = "", $ruta_archivo_xml = "xml/", $ruta_archivo_cdr = "cdr/")
    {

        $objFirma = new Signature();
        $flg_firma = 0; //posicion donde se firma en el XML
        $ruta = $ruta_archivo_xml . $nombre . '.xml';
        // $pass_firma = '12345678';

        $result =  $objFirma->signatureXML($flg_firma, $ruta, $rutacertificado, $emisor->passwordcert);

        if ($result->respuesta) {
            $zip = new ZipArchive();
            $nombrezip = $nombre . '.zip';
            $rutazip = $ruta_archivo_xml . $nombrezip;

            if ($zip->open($rutazip, ZipArchive::CREATE) == TRUE) {
                $zip->addFile($ruta, $nombre . '.xml');
                $zip->close();
            }

            if ($emisor->isProduccion()) {
                // $client_id = "d5829b1b-d28d-4ae4-b746-bdb8ccd909cb";
                // $client_secret = "1MUzl7c18miMqhTyiZAD8g==";

                //GENERAR TOKEN DE SUNAT
                $ws = "https://api-seguridad.sunat.gob.pe/v1/clientessol/" . $emisor->clientid . "/oauth2/token/";
                //ENVIO GRE DE SUNAT
                $ws1 = "https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/" . $nombre;
                // CONSULTA TICKET GRE SUNAT
                $ws2 = "https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/envios/";
            } else {
                // $client_id = "test-85e5b0ae-255c-4891-a595-0b98c65c9854";
                // $client_secret = "test-Hty/M6QshYvPgItX2P0+Kw==";

                //GENERAR TOKEN DE SUNAT
                $ws = "https://gre-test.nubefact.com/v1/clientessol/" . $emisor->clientid . "/oauth2/token";
                //ENVIO GRE DE SUNAT
                $ws1 = "https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/" . $nombre;
                // CONSULTA TICKET GRE SUNAT
                //$ws = https://e-guiaremision.sunat.gob.pe/ol-ti-itemision-guia-gem/billService //ws DE SUNAT PRODUCCION
                $ws2 = "https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/envios/";
            }

            // $wsconsult_token = "https://api-seguridad.sunat.gob.pe/v1/clientesextranet/e12bb62c-efc4-42cd-9137-31be9c5e15c5/oauth2/token/";
            // $wsconsult = "https://api.sunat.gob.pe/v1/contribuyente/contribuyentes/20538954099/validarcomprobante";
            // $wsCDR = "https://api-cpe.sunat.gob.pe/v1/contribuyente/enviossp/20538954099-03-B002-00000543/estados";

            $response = Http::asForm()->acceptJson()->post($ws, [
                'grant_type' => 'password',
                'scope' => 'https://api-cpe.sunat.gob.pe',
                'client_id' => $emisor->clientid,
                'client_secret' => $emisor->clientsecret,
                'username' => $emisor->document . $emisor->usuariosol,
                'password' => $emisor->clavesol
            ]);

            if ($response->status() == 200) {
                $token = json_decode($response->body())->access_token ?? null;
                // $token_type = json_decode($response->body())->token_type;
                // $expires_in = json_decode($response->body())->expires_in;
                if ($token) {
                    $json = response()->json([
                        'archivo' => [
                            'nomArchivo' => $nombrezip,
                            'arcGreZip' => base64_encode(file_get_contents($rutazip)),
                            'hashZip' => hash('sha256', file_get_contents($rutazip))
                        ]
                    ]);

                    $http = Http::withOptions([
                        'verify' => true,
                        'timeout' => 30,
                        'curl' => [
                            // CURLOPT_CAINFO => storage_path('app/company/cert/cacert.pem'),
                        ],
                    ])->withToken($token)->acceptJson()->post($ws1, $json->getData());
                    if ($http->status() == 200) {
                        $ticket = json_decode($http->body())->numTicket ?? null;
                        // $fecRecepcion = json_decode($http->body())->fecRecepcion;

                        $consulta = Http::withToken($token)->acceptJson()->get($ws2 . $ticket);

                        if ($consulta->status() == 200) {
                            // dd($consulta->body());
                            $codRespuesta = json_decode($consulta->body())->codRespuesta;
                            $indCdrGenerado = json_decode($consulta->body())->indCdrGenerado;

                            if ($indCdrGenerado) {

                                $cdr = base64_decode(json_decode($consulta->body())->arcCdr);
                                file_put_contents($ruta_archivo_cdr . "R-" . $nombrezip, $cdr); //ZIP DE MEMORIA A DISCO LOCAL
                                $zip = new ZipArchive();
                                if ($zip->open($ruta_archivo_cdr . 'R-' . $nombrezip) == TRUE) {
                                    $zip->extractTo($ruta_archivo_cdr, 'R-' . $nombre . '.xml');
                                    $zip->close();
                                }

                                $codeResponse = getValueNode($ruta_archivo_cdr . 'R-' . $nombre . '.xml', 'ResponseCode');
                                $descripcion = getValueNode($ruta_archivo_cdr . 'R-' . $nombre . '.xml', 'Description');
                                $notes = getNotesNode($ruta_archivo_cdr . 'R-' . $nombre . '.xml');

                                $mensaje = response()->json([
                                    'codRespuesta' => $codRespuesta,
                                    'code' => $codeResponse,
                                    'descripcion' => $descripcion,
                                    'notes' => $notes
                                ]);
                            } else {
                                $mensaje = response()->json([
                                    'codRespuesta' => $codRespuesta,
                                    'code' => json_decode($consulta->body())->error->numError,
                                    'descripcion' => json_decode($consulta->body())->error->desError
                                ]);
                            }
                        } else {
                            // dd($consulta->body());
                            $mensaje = response()->json([
                                'codRespuesta' => $consulta->status(),
                                'descripcion' => $consulta->body()
                            ]);
                        }
                    } else {
                        // dd($http->body());
                        $mensaje = response()->json([
                            'codRespuesta' => $http->status(),
                            'code' => json_decode($http->body())->cod,
                            'descripcion' => json_decode($http->body())->msg
                        ]);
                    }
                } else {
                    // dd($response->body());
                    $mensaje = response()->json([
                        'codRespuesta' => $response->status(),
                        'code' => json_decode($response->body())->cod,
                        'descripcion' => json_decode($response->body())->msg
                    ]);
                }
            } else {
                // dd($response->body(), $response->status());
                $mensaje = response()->json([
                    'codRespuesta' => $response->status(),
                    'code' => $response->status(),
                    'descripcion' => $response->body()
                ]);
            }
        } else {
            $mensaje = response()->json([
                'codRespuesta' => 'Error',
                'code' => $result->code,
                'descripcion' => $result->mensaje
            ]);
        }

        return $mensaje->getData();
    }

    function codeConsult()
    {
        // // CONSULTAR CDR COMPROBANTE USA TOKEN GRE
        // $tokenCDR = Http::asForm()->acceptJson()->post($ws, [
        //     'grant_type' => 'password',
        //     'scope' => 'https://api-cpe.sunat.gob.pe',
        //     'client_id' => $client_id_produccion,
        //     'client_secret' => $client_secret_produccion,
        //     'username' => '20538954099IONSOCAT',
        //     'password' => 'nextjoel'
        // ]);


        // dd($tokenCDR->body());

        // //CONSULTAR ESTADO COMPROBANTE USA TOKEN DE CPE
        // $responseToken = Http::asForm()->acceptJson()->post($wsconsult_token, [
        //     'grant_type' => 'client_credentials',
        //     'scope' => 'https://api.sunat.gob.pe/v1/contribuyente/contribuyentes',
        //     'client_id' => 'e12bb62c-efc4-42cd-9137-31be9c5e15c5',
        //     'client_secret' => 'etlfBUMvDy+UGHP12xFOIw==',
        // ]);

        // if ($responseToken->status() == 200) {

        //     $token = json_decode($responseToken->body())->access_token;
        //     $token_type = json_decode($responseToken->body())->token_type;
        //     $expires_in = json_decode($responseToken->body())->expires_in;

        //     $consultResponse = Http::withToken($token)->acceptJson()->post($wsconsult, [
        //         'numRuc' => '20538954099',
        //         'codComp' => '03',
        //         'numeroSerie' => 'B002',
        //         'numero' => '00000544',
        //         'fechaEmision' => '24/11/2023',
        //         'monto' => '140',
        //     ]);

        //     dd($consultResponse->body(), $consultResponse->status());
        // }

        // dd($responseToken->body());


        // dd("TRABAJANDO CON CREDENCIALES PRODUCCION");
    }
}
