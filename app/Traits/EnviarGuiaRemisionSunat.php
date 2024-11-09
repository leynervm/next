<?php

namespace App\Traits;

use App\Helpers\Facturacion\createXML;
use App\Helpers\Facturacion\SendXML;
use Illuminate\Support\Facades\Storage;

trait EnviarGuiaRemisionSunat
{
    public function enviarGuiaRemision()
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
                    'title' => 'NO SE HA CARGADO EL CERTIFICADO DIGITAL SUNAT !',
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

        if (!$this->sucursal->empresa->clientid) {
            $mensaje = response()->json([
                'success' => false,
                'title' => 'CONFIGURAR CLIENT ID PARA EMISIÓN DE GUÍAS ELECTRÓNICAS !',
                'mensaje' => null, //'No se pudo encontrar el Client Id para emitir guías de remisión a SUNAT.',
            ])->getData();
            return $mensaje;
        }

        if (!$this->sucursal->empresa->clientsecret) {
            $mensaje = response()->json([
                'success' => false,
                'title' => 'CONFIGURAR CLIENT SECRET PARA EMISIÓN DE GUÍAS ELECTRÓNICAS !',
                'mensaje' => null, //'No se pudo encontrar el Client Secret para emitir guías de remisión a SUNAT.',
            ])->getData();
            return $mensaje;
        }

        if ($this->indicadorvehiculosml == '0') {
            if ($this->modalidadtransporte->code == '02') {
                if (count($this->transportvehiculos) == 0) {
                    $mensaje = response()->json([
                        'success' => false,
                        'title' => 'No se encontraron vehículos de transporte en la guia ' . $this->seriecompleta,
                        'mensaje' => 'Las GRE con modalidad de tranporte privado, requieren registrar los datos del vehículo',
                    ]);
                    return $mensaje;
                }

                if (count($this->transportdrivers) == 0) {
                    $mensaje = response()->json([
                        'success' => false,
                        'title' => 'No se encontraron datos de del conductor en la guia ' . $this->seriecompleta,
                        'mensaje' => 'Las GRE con modalidad de tranporte privado, requieren registrar los datos del conductor del vehículo',
                    ]);
                    return $mensaje;
                }
            }
        }

        if (count($this->tvitems) == 0) {
            $mensaje = response()->json([
                'success' => false,
                'title' => 'No se encontraron items en la guia ' . $this->seriecompleta,
                'mensaje' => 'La GRE no tiene registros de items para ser emitido a SUNAT.',
            ]);
            return $mensaje;
        }

        $nombreXML = $this->sucursal->empresa->document . '-' . $this->seriecomprobante->typecomprobante->code . '-' . $this->seriecompleta;
        $ruta = 'xml/' . $this->seriecomprobante->typecomprobante->code . '/';

        try {
            verificarCarpeta($ruta, 'local');
            $xml = new createXML();
            $xml->guiaRemisionXML($ruta . $nombreXML, $this->sucursal->empresa, $this->client, $this);

            $objApi = new SendXML();
            $response = $objApi->enviarGuia($this->isProduccion(), $this->sucursal->empresa, $nombreXML, $this->sucursal->empresa->isProduccion() ? storage_path('app/company/cert/' . $this->sucursal->empresa->cert) : storage_path('app/company/cert/demo.pfx'), storage_path('app/' . $ruta), storage_path('app/' . $ruta));

            if ($response->codRespuesta == '0') {
                $this->descripcion = $response->descripcion;
                $this->codesunat = $response->code;
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
                        'mensaje' => 'success'
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
}
