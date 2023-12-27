<?php

namespace App\Http\Livewire\Modules\Facturacion\Guias;

use App\Helpers\Facturacion\createXML;
use App\Helpers\Facturacion\SendXML;
use App\Models\Guia;
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

        $sucursals = auth()->user()->sucursals()->orderBy('name', 'asc')->get();
        $guias = Guia::with(['comprobante' => function ($query) {
            $query->withTrashed();
        }])->whereIn('sucursal_id', $sucursals->pluck('id')->toArray());

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

        if ($this->searchsucursal !== '') {
            $guias->where('sucursal_id', $this->searchsucursal);
        }

        $guias = $guias->orderBy("id", "desc")->paginate();

        return view('livewire.modules.facturacion.guias.show-guias', compact('guias', 'sucursals'));
    }

    public function enviarsunat(Guia $guia)
    {

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


        $nombreXML = $guia->sucursal->empresa->document . '-' . $guia->seriecomprobante->typecomprobante->code . '-' . $guia->seriecompleta;
        $ruta = 'xml/' . $guia->seriecomprobante->typecomprobante->code . '/';

        verificarCarpeta($ruta, 'local');
        $xml = new createXML();
        $xml->guiaRemisionXML($ruta . $nombreXML, $guia->sucursal->empresa, $guia->client, $guia);

        $objApi = new SendXML();
        $response = $objApi->enviarGuia($guia->sucursal->empresa, $nombreXML, storage_path('app/cert/'), storage_path('app/' . $ruta), storage_path('app/' . $ruta));

        if ($response->codRespuesta == '0') {
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

        // $guia->codesunat = $response->code;
        $guia->descripcion = $response->descripcion;
        $guia->save();

    }
}
