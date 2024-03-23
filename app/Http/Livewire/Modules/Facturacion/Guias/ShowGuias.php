<?php

namespace App\Http\Livewire\Modules\Facturacion\Guias;

use App\Helpers\Facturacion\createXML;
use App\Helpers\Facturacion\SendXML;
use App\Models\Guia;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class ShowGuias extends Component
{

    use WithPagination;

    public $search = '';
    public $serie = '';
    public $date = '';
    public $dateto = '';
    public $searchuser = '';
    public $searchsucursal = '';

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'destinatario'],
        'serie' => ['except' => '', 'as' => 'serie-guia'],
        'date' => ['except' => '', 'as' => 'fecha'],
        'dateto' => ['except' => '', 'as' => 'hasta'],
        'searchsucursal' => ['except' => '', 'as' => 'sucursal'],
        // 'searchuser' => ['except' => '', 'as' => 'usuario'],
    ];

    public function render()
    {

        $sucursals = Sucursal::withTrashed()->whereHas('guias')->orderBy('name', 'asc')->get();
        $guias = Guia::with(['guiable', 'sucursal'])->withWhereHas('sucursal', function ($query) {
            // $query->withTrashed();
            if ($this->searchsucursal !== '') {
                $query->where('id', $this->searchsucursal);
            } else {
                $query->where('id', auth()->user()->sucursal_id);
            }
        });

        if ($this->serie !== '') {
            $guias->where('seriecompleta', 'ilike', '%' . $this->serie . '%');
        }

        if ($this->date) {
            if ($this->dateto) {
                $guias->whereDateBetween('date', $this->date, $this->dateto);
            } else {
                $guias->whereDate('date', $this->date);
            }
        }

        if ($this->search !== '') {
            $guias->where('documentdestinatario', 'ilike', '%' . $this->search . '%')
                ->orWhere('namedestinatario', 'ilike', '%' . $this->search . '%');
        }

        $guias = $guias->orderBy('id', 'desc')->paginate();

        return view('livewire.modules.facturacion.guias.show-guias', compact('guias', 'sucursals'));
    }

    public function enviarsunat($id)
    {

        $guia =  Guia::with(['sucursal' => function ($query) {
            $query->withTrashed();
        }])->with('tvitems')->find($id);

        if ($guia->sucursal->empresa->cert) {
            $filename = 'company/cert/' . $guia->sucursal->empresa->cert;
            if (!Storage::disk('local')->exists($filename)) {
                $mensaje = response()->json([
                    'title' => 'Certificado digital SUNAT no encontrado !',
                    'text' => 'No se pudo encontrar el certificado digital para la firma de comprobantes electrónicos.',
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        } else {
            $mensaje = response()->json([
                'title' => 'No se ha configurado el certificado digital SUNAT !',
                'text' => 'No se pudo encontrar el certificado digital para la firma de comprobantes electrónicos.',
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        if (!$guia->sucursal->empresa->usuariosol || !$guia->sucursal->empresa->usuariosol) {
            $mensaje = response()->json([
                'title' => 'Configurar usuario y clave SOL para la emisión de comprobantes electrónicos !',
                'text' => 'No se pudo encontrar los datos de usuario y clave SOL para emitir guías de remisión a SUNAT.',
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        if (!$guia->sucursal->empresa->clientid) {
            $mensaje = response()->json([
                'title' => 'Configurar Client Id para emitir guías de remisión !',
                'text' => 'No se pudo encontrar el Client Id para emitir guías de remisión a SUNAT.',
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        if (!$guia->sucursal->empresa->clientsecret) {
            $mensaje = response()->json([
                'title' => 'Configurar Client Secret para emitir guías de remisión !',
                'text' => 'No se pudo encontrar el Client Secret para emitir guías de remisión a SUNAT.',
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        if ($guia->indicadorvehiculosml == '0') {
            if ($guia->modalidadtransporte->code == '02') {
                if (count($guia->transportvehiculos) == 0) {
                    $mensaje = response()->json([
                        'title' => 'No se encontraron vehículos de transporte en la guia ' . $guia->seriecompleta,
                        'text' => 'Las GRE con modalidad de tranporte privado, requieren registrar los datos del vehículo',
                    ]);
                    $this->dispatchBrowserEvent('validation', $mensaje->getData());
                    return false;
                }

                if (count($guia->transportdrivers) == 0) {
                    $mensaje = response()->json([
                        'title' => 'No se encontraron datos de del conductor en la guia ' . $guia->seriecompleta,
                        'text' => 'Las GRE con modalidad de tranporte privado, requieren registrar los datos del conductor del vehículo',
                    ]);
                    $this->dispatchBrowserEvent('validation', $mensaje->getData());
                    return false;
                }
            }
        }

        if (count($guia->tvitems) == 0) {
            $mensaje = response()->json([
                'title' => 'No se encontraron items en la guia ' . $guia->seriecompleta,
                'text' => 'La GRE no tiene registros de items para ser emitido a SUNAT.',
            ]);
            $this->dispatchBrowserEvent('validation', $mensaje->getData());
            return false;
        }

        $nombreXML = $guia->sucursal->empresa->document . '-' . $guia->seriecomprobante->typecomprobante->code . '-' . $guia->seriecompleta;
        $ruta = 'xml/' . $guia->seriecomprobante->typecomprobante->code . '/';

        try {
            verificarCarpeta($ruta, 'local');
            $xml = new createXML();
            $xml->guiaRemisionXML($ruta . $nombreXML, $guia->sucursal->empresa, $guia->client, $guia);

            $objApi = new SendXML();
            $response = $objApi->enviarGuia($guia->sucursal->empresa, $nombreXML, storage_path('app/company/cert/' . $guia->sucursal->empresa->cert), storage_path('app/' . $ruta), storage_path('app/' . $ruta));

            if ($response->codRespuesta == '0') {
                $guia->codesunat = $response->code;
                if ($response->notes !== '') {
                    $mensaje = response()->json([
                        'title' => $response->descripcion,
                        'text' => $response->notes,
                    ]);
                    $this->dispatchBrowserEvent('validation', $mensaje->getData());
                    $guia->notasunat = $response->notes;
                } else {
                    $mensaje = response()->json([
                        'title' => $response->descripcion,
                        'icon' => 'success'
                    ]);
                    $this->dispatchBrowserEvent('toast', $mensaje->getData());
                }
            } else {
                $mensaje = response()->json([
                    'title' => $response->descripcion,
                    'text' => 'Código de respuesta : ' . $response->code,
                ]);
                $this->dispatchBrowserEvent('validation', $mensaje->getData());
            }

            $guia->descripcion = $response->descripcion;
            $guia->save();
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
