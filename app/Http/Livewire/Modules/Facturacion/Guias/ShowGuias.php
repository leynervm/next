<?php

namespace App\Http\Livewire\Modules\Facturacion\Guias;

use App\Helpers\Facturacion\createXML;
use App\Helpers\Facturacion\SendXML;
use App\Mail\EnviarXMLGuiaMailable;
use App\Models\Guia;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Mail;
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

    public $checkall = false;
    public $selectedcomprobantes = [];

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'destinatario'],
        'serie' => ['except' => '', 'as' => 'serie-guia'],
        'date' => ['except' => '', 'as' => 'fecha'],
        'dateto' => ['except' => '', 'as' => 'hasta'],
    ];

    public function render()
    {

        $guias = Guia::with(['guiable', 'sucursal', 'tvitems', 'client', 'motivotraslado', 'modalidadtransporte', 'ubigeoorigen', 'ubigeodestino', 'seriecomprobante.typecomprobante', 'sucursal', 'user'])
            ->where('sucursal_id',  auth()->user()->sucursal_id);

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

        if ($this->checkall) {
            $this->allcomprobantes();
        }
        $guias = $guias->orderBy('id', 'desc')->paginate();

        return view('livewire.modules.facturacion.guias.show-guias', compact('guias'));
    }

    public function enviarsunat($id)
    {
        $guia =  Guia::with('tvitems')->find($id);

        if ($guia && !$guia->isSendSunat()) {
            $response = $guia->enviarGuiaRemision();

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
                'title' => 'GUÍA DE REMISIÓN ELECTRÓNICA ' . $guia->seriecompleta . ' YA FUÉ EMITIDO A SUNAT.',
                'text' => null,
            ]);
            $this->dispatchBrowserEvent('validation', $mensaje->getData());
        }
    }


    public function enviarxml($id)
    {
        $guia =  Guia::find($id)->with(['sucursal', 'client'])->find($id);
        if ($guia->client) {
            if ($guia->client->email) {
                Mail::to($guia->client->email)->send(new EnviarXMLGuiaMailable($guia));
                $mensaje = response()->json([
                    'title' => 'Enviando correo a: ' . $guia->client->email,
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

    public function multisend()
    {

        if (count($this->selectedcomprobantes) > 0) {
            $correctos = 0;
            $con_observaciones = 0;

            foreach ($this->selectedcomprobantes as $key => $id) {
                $guia =  Guia::with('tvitems')->find($id);
                if ($guia && !$guia->isSendSunat()) {
                    $response = $guia->enviarGuiaRemision();

                    if ($response->success) {
                        if (empty($response->mensaje)) {
                            $correctos++;
                        } else {
                            $con_observaciones++;
                        }
                        unset($this->selectedcomprobantes[$key]);
                    }
                }
            }

            $text = "<ul>";
            if ($correctos > 0) {
                $text .= "<li>$correctos guías de remisión electrónicas emitidos correctamente.</li>";
            }
            if ($con_observaciones > 0) {
                $text .= "<li>$con_observaciones guías de remisión electrónicas emitidos con observaciones.</li>";
            }
            $text .= "</ul>";

            if ($correctos + $con_observaciones > 0) {
                $mensaje = response()->json([
                    'title' => "GUÍAS DE REMISIÓN ELECTRÓNICAS ENVIADOS CORRECTAMENTE A SUNAT",
                    'text' => $text,
                    'icon' => 'success'
                ]);
            } else {
                $mensaje = response()->json([
                    'title' => "GUÍAS DE REMISIÓN ELECTRÓNICAS NO FUERON EMITIDOS A SUNAT !",
                    'text' => "Intente nuevamente enviar los comprobantes electrónicos seleccionados.",
                ]);
            }

            $this->resetValidation();
            $this->reset(['selectedcomprobantes', 'checkall']);
            $this->dispatchBrowserEvent('validation', $mensaje->getData());
        } else {
            $mensaje = response()->json([
                'title' => "SELECCIONE GUÍAS DE REMISIÓN ELECTRÓNICAS A EMITIR !",
                'text' => null,
            ]);
            $this->dispatchBrowserEvent('validation', $mensaje->getData());
        }
    }

    public function allcomprobantes()
    {
        if ($this->checkall) {
            $guias = Guia::noEnviadoSunat()->where('sucursal_id',  auth()->user()->sucursal_id);

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

            $this->selectedcomprobantes = $guias->get()->pluck('id');
        } else {
            $this->reset(['selectedcomprobantes']);
        }
    }
}
