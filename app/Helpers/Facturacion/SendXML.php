<?php

namespace App\Helpers\Facturacion;

use DOMDocument;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use ZipArchive;

class SendXML
{
    public function enviarComprobante($isProduccion = false, $emisor, $nombre, $rutacertificado = "", $pass_firma, $ruta_archivo_xml = "xml/", $ruta_archivo_cdr = "cdr/")
    {
        $objFirma = new Signature();
        $flg_firma = 0;
        $ruta = $ruta_archivo_xml . $nombre . '.xml';
        // $ruta_firma = $rutacertificado . 'certificado_prueba_sunat.pfx';
        $result = $objFirma->signatureXML($flg_firma, $ruta, $rutacertificado, $pass_firma);
        try {
            if ($result->respuesta) {
                $zip = new ZipArchive();
                $nombrezip = $nombre . '.zip';
                $rutazip = $ruta_archivo_xml . $nombrezip;

                if ($zip->open($rutazip, ZipArchive::CREATE) == TRUE) {
                    $zip->addFile($ruta, $nombre . '.xml');
                    $zip->close();
                }

                if ($isProduccion) {
                    $ws = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService";
                } else {
                    $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
                }

                $xml_envio = '<?xml version="1.0" encoding="UTF-8"?>
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                <soapenv:Header>
                    <wsse:Security>
                        <wsse:UsernameToken Id="ABC-123">
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
                    "Content-Length: " . strlen($xml_envio)
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
                            'notes' => $notes,
                            'hash' => $result->hash_cpe,
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
        } catch (Exception $error) {
            $mensaje = response()->json([
                'codRespuesta' => 'Error',
                'code' => '0***',
                'descripcion' => $error->getMessage()
            ]);
        }

        return $mensaje->getData();
    }

    public function enviarGuia($isProduccion = false, $emisor, $nombre, $rutacertificado = "", $ruta_archivo_xml = "xml/", $ruta_archivo_cdr = "cdr/")
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

            if ($isProduccion) {
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
                    $json = [
                        'archivo' => [
                            'nomArchivo' => $nombrezip,
                            'arcGreZip' => base64_encode(file_get_contents($rutazip)),
                            'hashZip' => hash_file('sha256', $rutazip)
                        ]
                    ];

                    $http = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ])->post($ws1, $json);

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
                        $responseArray = json_decode($http->body(), true);
                        $cod = isset($responseArray['cod']) ? $responseArray['cod'] : $http->status();
                        $msg = isset($responseArray['msg']) ? $responseArray['msg'] : json_decode($http->body())->message;
                        $mensaje = response()->json([
                            'codRespuesta' => $http->status(),
                            'code' => $cod,
                            'descripcion' => $msg
                        ]);
                    }
                    //  else {
                    // dd($http->body());
                    //     $mensaje = response()->json([
                    //         'codRespuesta' => $http->status(),
                    //         'code' => json_decode($http->body())->cod,
                    //         'descripcion' => json_decode($http->body())->msg
                    //     ]);
                    // }
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
                $responseArray = json_decode($response->body(), true);
                $cod = isset($responseArray['error']) ? $responseArray['error'] : $response->status();
                $msg = isset($responseArray['error_description']) ? $responseArray['error_description'] : json_decode($response->body());
                $mensaje = response()->json([
                    'codRespuesta' => $response->status(),
                    'code' => $cod,
                    'descripcion' => $msg
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

    function getStatus($empresa, $tipo, $serie, $correlativo, $nombre = null, $ruta_archivo_cdr = null)
    {

        // $wsApiCDR = "https://api-cpe.sunat.gob.pe/v1/contribuyente/enviossp/20538954099-03-B002-00000543/estados";
        // $wsCV = "https://e-factura.sunat.gob.pe/ol-it-wsconsvalidcpe/billValidService";
        $ws = "https://e-factura.sunat.gob.pe/ol-it-wsconscpegem/billConsultService";

        $xml_envio = '<?xml version="1.0" encoding="UTF-8"?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <soapenv:Header>
                <wsse:Security>
                    <wsse:UsernameToken Id="ABC-123">
                        <wsse:Username>' . $empresa->document . $empresa->usuariosol . '</wsse:Username>
                        <wsse:Password>' . $empresa->clavesol . '</wsse:Password>
                    </wsse:UsernameToken>
                </wsse:Security>
            </soapenv:Header>
            <soapenv:Body>
                <ser:getStatusCdr>
                    <rucComprobante>' . $empresa->document . '</rucComprobante>
                    <tipoComprobante>' . $tipo . '</tipoComprobante>
                    <serieComprobante>' . $serie . '</serieComprobante>
                    <numeroComprobante>' . $correlativo . '</numeroComprobante>
                </ser:getStatusCdr>
            </soapenv:Body>
        </soapenv:Envelope>';

        $header = array(
            "Content-Type: text/xml; charset=utf-8",
            "SOAPAction: getStatusCdr",
            "Content-Length: " . strlen($xml_envio)
        );

        try {
            $response = Http::withOptions(['verify' => true, 'timeout' => 30])
                ->withHeaders($header)->withBody($xml_envio, 'text/xml')->post($ws);

            if ($response->status() == 200) {
                $doc = new DOMDocument();
                $doc->loadXML($response->body());

                $statusCodeNode = $doc->getElementsByTagName('statusCode')->item(0);
                $statusCode = $statusCodeNode ? $statusCodeNode->nodeValue : null;

                $statusMessageNode = $doc->getElementsByTagName('statusMessage')->item(0);
                $statusMessage = $statusMessageNode ? $statusMessageNode->nodeValue : null;

                if (!empty($ruta_archivo_cdr)) {
                    $contentNode = $doc->getElementsByTagName('content')->item(0);
                    if ($contentNode && $contentNode->nodeValue) {

                        $cdrBase64 = $contentNode->nodeValue;
                        // Guarda el CDR como un archivo ZIP
                        $nombreCdrZip = "R-" . $nombre . ".zip";
                        $rutaArchivoCdr = $ruta_archivo_cdr . $nombreCdrZip;
                        file_put_contents($rutaArchivoCdr, base64_decode($cdrBase64));

                        // Extrae el archivo XML del CDR (opcional: para analizar su contenido)
                        $zip = new ZipArchive();
                        if ($zip->open($rutaArchivoCdr) === TRUE) {
                            $fileExtract = $zip->getNameIndex(0);
                            if ($fileExtract != "R-" . $nombre . ".xml" || $fileExtract != "R-" . $nombre . ".XML") {
                                $zip->extractTo($ruta_archivo_cdr, $fileExtract);
                                $tempPath = $ruta_archivo_cdr . $fileExtract;
                                $renameFile = $ruta_archivo_cdr . "/R-" . $nombre . '.xml';
                                rename($tempPath, $renameFile);
                            } else {
                                $zip->extractTo($ruta_archivo_cdr, "R-" . $nombre . ".xml");
                            }
                            $zip->close();

                            // Si deseas procesar el contenido del XML extraído
                            $rutaCdrXml = $ruta_archivo_cdr . "R-" . $nombre . ".xml";
                            $codeResponse = getValueNode($rutaCdrXml, 'ResponseCode');
                            $descripcion = getValueNode($rutaCdrXml, 'Description');
                            $notes = getNotesNode($rutaCdrXml);

                            $mensaje = response()->json([
                                'codRespuesta' => $response->status(),
                                'code' => $codeResponse,
                                'descripcion' => $descripcion,
                                'notes' => $notes,
                            ]);
                        } else {
                            $mensaje = response()->json([
                                'codRespuesta' => $response->status(),
                                'code' => $statusCode,
                                'descripcion' => "$statusMessage <br> No se pudo extraer el archivo ZIP del CDR"
                            ]);
                        }
                    } else {
                        $mensaje = response()->json([
                            'codRespuesta' => $response->status(),
                            'code' => $statusCode,
                            'descripcion' => "$statusMessage <br> No se pudo obtener el archivo ZIP del CDR"
                        ]);
                    }
                } else {
                    $mensaje = response()->json([
                        'codRespuesta' => $response->status(),
                        'code' => $statusCode,
                        'descripcion' => $statusMessage
                    ]);
                }
            } else {
                $mensaje = response()->json([
                    'codRespuesta' => $response->status(),
                    'code' => $response->status(),
                    'descripcion' => $response->body()
                ]);
            }
        } catch (Exception $error) {
            $mensaje = response()->json([
                'codRespuesta' => 'Error',
                'code' => '0***',
                'descripcion' => $error->getMessage()
            ]);
        }

        return $mensaje->getData();


        // $tokenCDR = Http::asForm()->acceptJson()->post($wsCDR, [
        //     'grant_type' => 'password',
        //     'scope' => 'https://api-cpe.sunat.gob.pe',
        //     'client_id' => $empresa->clientid,
        //     'client_secret' => $empresa->clientsecret,
        //     // 'username' => $empresa->document . $empresa->usuariosol,
        //     // 'password' => $empresa->clavesol,
        //     'username' => '20538954099IONSOCAT',
        //     'password' => 'nextjoel',
        // ]);

        // dd($tokenCDR->body());

        // //CONSULTAR ESTADO COMPROBANTE USA TOKEN DE CPE
        // $responseToken = Http::asForm()->acceptJson()->post($wsconsult_token, [
        //     'grant_type' => 'client_credentials',
        //     'scope' => 'https://api.sunat.gob.pe/v1/contribuyente/contribuyentes',
        //     'client_id' => 'e12bb62c-efc4-42cd-9137-31be9c5e15c5',
        //     'client_secret' => 'etlfBUMvDy+UGHP12xFOIw==',
        //     // 'client_id' => 'e12bb62c-efc4-42cd-9137-31be9c5e15c5',
        //     // 'client_secret' => 'etlfBUMvDy+UGHP12xFOIw==',
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
