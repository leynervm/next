<?php

namespace App\Traits;

use App\Helpers\Facturacion\createXML;
use App\Helpers\Facturacion\SendXML;
use Illuminate\Support\Facades\Storage;

trait EnviarComprobanteSunat
{

    public function enviarComprobante()
    {

        if ($this->sucursal->empresa->isProduccion()) {
            if ($this->sucursal->empresa->cert) {
                $filename = 'company/cert/' . $this->sucursal->empresa->cert;
                if (!Storage::disk('local')->exists($filename)) {
                    $mensaje = response()->json([
                        'success' => false,
                        'title' => 'NO SE HA ENCONTRADO EL CERTIFICADO DIGITAL SUNAT !',
                        'mensaje' => null, //'No se pudo encontrar el certificado digital para la firma de comprobantes electrónicos.',
                    ])->getData();
                    return $mensaje;
                }
            } else {
                $mensaje = response()->json([
                    'success' => false,
                    'title' => 'NO SE HA CARGADO EL CERTIFICADO DIGITAL SUNAT !',
                    'mensaje' => null, //'No se pudo encontrar el certificado digital para la firma de comprobantes electrónicos.',
                ])->getData();
                return $mensaje;
            }
        } else {
            $filename = 'company/cert/demo.pfx';
            if (!Storage::disk('local')->exists($filename)) {
                $mensaje = response()->json([
                    'success' => false,
                    'title' => 'CERTIFICADO DIGITAL SUNAT DE PRUEBA NO ENCONTRADO !',
                    'mensaje' => null, //'No se pudo encontrar el certificado digital de prueba para la firma de comprobantes electrónicos.',
                ])->getData();
                return $mensaje;
            }
        }

        if (!$this->sucursal->empresa->usuariosol || !$this->sucursal->empresa->usuariosol) {
            $mensaje = response()->json([
                'success' => false,
                'title' => 'CONFIGURAR USUARIO Y CLAVE SOL PARA LA EMISIÓN DE COMPROBANTES ELECTRÓNICOS !',
                'mensaje' => null, //'No se pudo encontrar los datos de usuario y clave SOL para emitir guías de remisión a SUNAT.',
            ])->getData();
            return $mensaje;
        }

        $codetypecomprobante = $this->seriecomprobante->typecomprobante->code;
        $nombreXML = $this->sucursal->empresa->document . '-' . $codetypecomprobante . '-' . $this->seriecompleta;
        $ruta = 'xml/' . $codetypecomprobante . '/';

        try {
            verificarCarpeta($ruta, 'local');
            $xml = new createXML();

            if ($codetypecomprobante == '07') {
                $xml->notaCreditoXML($ruta . $nombreXML, $this->sucursal->empresa, $this->client, $this);
            } else {
                $xml->comprobanteVentaXML($ruta . $nombreXML, $this->sucursal->empresa, $this->client, $this);
            }

            $objApi = new SendXML();
            $pass_firma = $this->sucursal->empresa->passwordcert;

            $host = request()->getHost();
            $subdomain = explode('.', $host)[0];
            $response = $objApi->enviarComprobante($subdomain === 'demo' ? false : $this->isProduccion(), $this->sucursal->empresa, $nombreXML,  $this->sucursal->empresa->isProduccion() ? storage_path('app/company/cert/' . $this->sucursal->empresa->cert) : storage_path('app/company/cert/demo.pfx'), $pass_firma, storage_path('app/' . $ruta), storage_path('app/' . $ruta));

            if ($response->code == '0') {
                $this->descripcion = $response->descripcion;
                $this->codesunat = $response->code;
                if ($response->hash) {
                    $this->hash = $response->hash;
                }

                if ($response->notes !== '') {
                    $this->notasunat = $response->notes;
                    $this->save();
                    $mensaje = response()->json([
                        'success' => true,
                        'title' => $response->descripcion,
                        'mensaje' => $response->notes,
                    ])->getData();
                    return $mensaje;
                } else {
                    $this->save();
                    $mensaje = response()->json([
                        'success' => true,
                        'title' => $response->descripcion,
                        'mensaje' => null,
                    ])->getData();
                    return $mensaje;
                }
            } else {
                $this->descripcion = $response->descripcion;
                $this->save();
                $mensaje = response()->json([
                    'success' => false,
                    'title' => $response->descripcion,
                    'mensaje' => 'Código de respuesta : ' . $response->code,
                ])->getData();
                return $mensaje;
            }
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function anularComprobante($seriecomprobante) {}
}
