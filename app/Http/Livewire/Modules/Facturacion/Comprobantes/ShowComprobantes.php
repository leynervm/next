<?php

namespace App\Http\Livewire\Modules\Facturacion\Comprobantes;

use App\Helpers\Facturacion\createXML;
use App\Helpers\Facturacion\SendXML;
use App\Mail\EnviarXMLMailable;
use App\Models\Sucursal;
use App\Models\Typecomprobante;
use App\Models\Typepayment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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
    ];

    public function render()
    {

        $comprobantes = Comprobante::withTrashed()->with(['facturableitems', 'sucursal'])
            ->withWherehas('sucursal', function ($query) {
                $query->where('id', auth()->user()->sucursal_id);
            });

        $typepayments = Typepayment::whereHas('comprobantes', function ($query) {
            $query->withTrashed()->where('sucursal_id', auth()->user()->sucursal_id);
        })->orderBy('name', 'asc')->get();

        $users = User::whereHas('comprobantes', function ($query) {
            $query->where('sucursal_id', auth()->user()->sucursal_id);
        })->orderBy('name', 'asc')->get();

        $typecomprobantes = Typecomprobante::whereHas('seriecomprobantes', function ($query) {
            $query->whereHas('comprobantes')
                ->where('sucursal_id', auth()->user()->sucursal_id);
        })->orderBy('code', 'asc')->get();

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

        if ($this->serie !== '') {
            $comprobantes->where('seriecompleta', 'ilike', trim($this->serie) . '%');
        }

        $comprobantes = $comprobantes->orderBy("id", "desc")->paginate();

        return view('livewire.modules.facturacion.comprobantes.show-comprobantes', compact('comprobantes', 'typepayments', 'typecomprobantes', 'users'));
    }

    public function enviarsunat($id)
    {

        $comprobante =  Comprobante::find($id);

        if ($comprobante && !$comprobante->isSendSunat()) {
            $response = $comprobante->enviarComprobante();

            if ($response->success) {
                if (empty($response->mensaje)) {
                    $mensaje = response()->json([
                        'title' => $response->title,
                        'icon' => 'success'
                    ]);
                    $this->dispatchBrowserEvent('toast', $mensaje->getData());
                } else {
                    $mensaje = response()->json([
                        'title' => $response->title,
                        'text' => $response->mensaje,
                    ]);
                    $this->dispatchBrowserEvent('validation', $mensaje->getData());
                }
            } else {
                $mensaje = response()->json([
                    'title' => $response->title,
                    'text' => $response->mensaje,
                ]);
                $this->dispatchBrowserEvent('validation', $mensaje->getData());
            }
        } else {
            $mensaje = response()->json([
                'title' => 'COMPROBANTE ELECTRÓNICO ' . $comprobante->seriecompleta. ' YA FUÉ EMITIDO A SUNAT.',
                'text' => null,
            ]);
            $this->dispatchBrowserEvent('validation', $mensaje->getData());
        }
    }

    public function enviarxml($id)
    {
        $comprobante =  Comprobante::find($id)->with(['sucursal', 'client'])->find($id);
        if ($comprobante->client) {
            if ($comprobante->client->email) {
                Mail::to($comprobante->client->email)->send(new EnviarXMLMailable($comprobante));
                $mensaje = response()->json([
                    'title' => 'Enviando correo a: ' . $comprobante->client->email,
                    'icon' => 'success',
                ])->getData();
                $this->dispatchBrowserEvent('toast', $mensaje);
            } else {
                $mensaje = response()->json([
                    'title' => 'Correo no enviado !',
                    'text' => 'No se pudo enviar el mensaje, no se encontró el correo del cliente seleccionado.',
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        }
    }
}
