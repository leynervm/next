<?php

namespace App\Http\Livewire\Modules\Facturacion\Comprobantes;

use App\Helpers\Facturacion\createXML;
use App\Helpers\Facturacion\SendXML;
use App\Models\Typecomprobante;
use App\Models\Typepayment;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Facturacion\Entities\Comprobante;

class ShowComprobantes extends Component
{

    use WithPagination;

    public $search = '';
    public $serie = '';
    public $date = '';
    public $dateto = '';
    public $searchtypepayment = '';
    public $searchtypecomprobante = '';
    public $searchuser = '';
    public $searchsucursal = '';


    protected $queryString = [
        'search' => [
            'except' => '',
            'as' => 'buscar'
        ],
        'serie' => [
            'except' => '',
            'as' => 'serie-comprobante'
        ],
        'date' => [
            'except' => '',
            'as' => 'fecha'
        ],
        'dateto' => [
            'except' => '',
            'as' => 'hasta'
        ],
        'searchtypepayment' => [
            'except' => '',
            'as' => 'tipo-pago'
        ],
        'searchtypecomprobante' => [
            'except' => '',
            'as' => 'tio-comprobante'
        ],
        'searchuser' => [
            'except' => '',
            'as' => 'usuario'
        ],
        'searchsucursal' => [
            'except' => '',
            'as' => 'sucursal'
        ],
    ];

    public function render()
    {

        $sucursals =auth()->user()->sucursals()->orderBy('name', 'asc')->get();
        $typepayments = Typepayment::whereHas('comprobantes', function ($query) use ($sucursals) {
            $query->whereIn('sucursal_id', $sucursals->pluck('id')->toArray());
        })->orderBy('name', 'asc')->get();
        $users = User::whereHas('comprobantes')->orderBy('name', 'asc')->get();
        $typecomprobantes = Typecomprobante::whereHas('seriecomprobantes.sucursals', function ($query) use ($sucursals) {
            $query->whereIn('sucursal_id', $sucursals->pluck('id')->toArray());
        })->orderBy('code', 'asc')->get();
        $comprobantes = Comprobante::with('facturableitems')->withTrashed()
            ->whereIn('sucursal_id', $sucursals->pluck('id')->toArray());


        if ($this->search !== '') {
            $comprobantes->whereHas('client', function ($query) {
                $query->where('name', 'ilike', '%' . $this->search . '%')
                    ->orWhere('document', 'ilike', $this->search . '%');
            });
        }

        if ($this->date) {
            if ($this->dateto) {
                $comprobantes->whereDateBetween('date', $this->date, $this->dateto);
            } else {
                $comprobantes->whereDate('date', $this->date);
            }
        }

        if ($this->searchtypepayment !== '') {
            $comprobantes->whereHas('typepayment', function ($query) {
                $query->where('typepayments.name', $this->searchtypepayment);
            });
        }

        if ($this->searchtypecomprobante !== '') {
            $comprobantes->whereHas('seriecomprobante.typecomprobante', function ($query) {
                $query->where('typecomprobantes.code', $this->searchtypecomprobante);
            });
        }

        if ($this->searchuser !== '') {
            $comprobantes->where('user_id', $this->searchuser);
        }

        if ($this->searchsucursal !== '') {
            $comprobantes->where('sucursal_id', $this->searchsucursal);
        }

        if ($this->serie !== '') {
            $comprobantes->where('seriecompleta', 'ilike', trim($this->serie) . '%');
        }

        $comprobantes = $comprobantes->orderBy("id", "desc")->paginate();

        return view('livewire.modules.facturacion.comprobantes.show-comprobantes', compact('comprobantes', 'typepayments', 'typecomprobantes', 'sucursals', 'users'));
    }

    public function enviarsunat(Comprobante $comprobante)
    {

        $codetypecomprobante = $comprobante->seriecomprobante->typecomprobante->code;
        $nombreXML = $comprobante->sucursal->empresa->document . '-' . $codetypecomprobante . '-' . $comprobante->seriecompleta;
        $ruta = 'xml/' . $codetypecomprobante . '/';

        verificarCarpeta($ruta, 'local');
        $xml = new createXML();

        if ($codetypecomprobante == '07') {
            $xml->notaCreditoXML($ruta . $nombreXML, $comprobante->sucursal->empresa, $comprobante->client, $comprobante);
        } else {
            $xml->comprobanteVentaXML($ruta . $nombreXML, $comprobante->sucursal->empresa, $comprobante->client, $comprobante);
        }

        $objApi = new SendXML();
        $response = $objApi->enviarComprobante($comprobante->sucursal->empresa, $nombreXML, storage_path('app/cert/'), storage_path('app/' . $ruta), storage_path('app/' . $ruta));

        if ($response->code == '0') {
            if ($response->notes !== '') {
                $mensaje = response()->json([
                    'title' => $response->descripcion,
                    'text' => $response->notes,
                ]);
                $this->dispatchBrowserEvent('validation', $mensaje->getData());
                $comprobante->notasunat = $response->notes;
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

        $comprobante->codesunat = $response->code;
        $comprobante->descripcion = $response->descripcion;
        $comprobante->save();
    }
}
