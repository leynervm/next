<?php

namespace App\Http\Livewire\Modules\Facturacion\Comprobantes;

use App\Mail\EnviarXMLMailable;
use App\Models\Typecomprobante;
use App\Models\Typepayment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
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

    public $checkall = false;
    public $selectedcomprobantes = [];


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

        if ($this->checkall) {
            $this->allcomprobantes();
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
                'title' => 'COMPROBANTE ELECTRÓNICO ' . $comprobante->seriecompleta . ' YA FUÉ EMITIDO A SUNAT.',
                'text' => null,
            ]);
            $this->dispatchBrowserEvent('validation', $mensaje->getData());
        }
    }

    public function enviarxml($id)
    {
        $config_email = validarConfiguracionEmail();

        if (!$config_email->getData()->success) {
            $mensaje = response()->json([
                'title' => $config_email->getData()->error,
                'text' => null,
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        $comprobante =  Comprobante::find($id)->with(['sucursal', 'client'])->find($id);
        if ($comprobante->client) {
            if ($comprobante->client->email) {
                Mail::to($comprobante->client->email)->send(new EnviarXMLMailable($comprobante));
                $mensaje = response()->json([
                    'title' => 'ENVIANDO CORREO',
                    'icon' => 'success',
                ])->getData();
                $this->dispatchBrowserEvent('toast', $mensaje);
            } else {
                $mensaje = response()->json([
                    'title' => 'NO SE HA ENCONTRADO CORREO DEL CLIENTE',
                    'text' => null,
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
                $comprobante =  Comprobante::find($id);
                if ($comprobante && !$comprobante->isSendSunat()) {
                    $response = $comprobante->enviarComprobante();

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
                $text .= "<li>$correctos comprobantes electrónicos emitidos correctamente.</li>";
            }
            if ($con_observaciones > 0) {
                $text .= "<li>$con_observaciones comprobantes electrónicos emitidos con observaciones.</li>";
            }
            $text .= "</ul>";

            if ($correctos + $con_observaciones > 0) {
                $mensaje = response()->json([
                    'title' => "COMPROBANTES ELECTRÓNICOS ENVIADOS CORRECTAMENTE A SUNAT",
                    'text' => $text,
                    'icon' => 'success'
                ]);
            } else {
                $mensaje = response()->json([
                    'title' => "COMPROBANTES ELECTRÓNICOS NO FUERON EMITIDOS A SUNAT !",
                    'text' => "Intente nuevamente enviar los comprobantes electrónicos seleccionados.",
                ]);
            }

            $this->resetValidation();
            $this->reset(['selectedcomprobantes', 'checkall']);
            $this->dispatchBrowserEvent('validation', $mensaje->getData());
        } else {
            $mensaje = response()->json([
                'title' => "SELECCIONE LOS COMPROBANTES ELECTRÓNICOS A EMITIR !",
                'text' => null,
            ]);
            $this->dispatchBrowserEvent('validation', $mensaje->getData());
        }
    }

    public function allcomprobantes()
    {
        if ($this->checkall) {
            $comprobantes = Comprobante::noEnviadoSunat()
                ->where('sucursal_id', auth()->user()->sucursal_id);

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

            // $comprobantes = $comprobantes->get()->pluck('id');
            $this->selectedcomprobantes = $comprobantes->get()->pluck('id');
        } else {
            $this->reset(['selectedcomprobantes']);
        }
    }
}
