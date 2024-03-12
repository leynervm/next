<?php

namespace App\Http\Livewire\Modules\Facturacion\Comprobantes;

use App\Helpers\Facturacion\createXML;
use App\Helpers\Facturacion\SendXML;
use App\Models\Sucursal;
use App\Models\Typecomprobante;
use App\Models\Typepayment;
use App\Models\User;
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

        $comprobantes = Comprobante::withTrashed()->with(['facturableitems', 'sucursal'])
            ->withWherehas('sucursal', function ($query) {
                $query->withTrashed();
                if ($this->searchsucursal !== '') {
                    $query->where('id', $this->searchsucursal);
                } else {
                    $query->where('id', auth()->user()->sucursal_id);
                }
            });

        $sucursals = Sucursal::withTrashed()->whereHas('comprobantes')->get();

        $typepayments = Typepayment::whereHas('comprobantes', function ($query) {
            $query->withTrashed()->where('sucursal_id', auth()->user()->sucursal_id);
        })->orderBy('name', 'asc')->get();

        $users = User::whereHas('comprobantes')->orderBy('name', 'asc')->get();

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

        return view('livewire.modules.facturacion.comprobantes.show-comprobantes', compact('comprobantes', 'typepayments', 'typecomprobantes', 'sucursals', 'users'));
    }

    public function enviarsunat($id)
    {

        $comprobante =  Comprobante::find($id)->with(['sucursal' => function ($query) {
            $query->withTrashed();
        }])->find($id);

        if ($comprobante->sucursal->empresa->cert) {
            $filename = 'company/cert/' . $comprobante->sucursal->empresa->cert;
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

        if (!$comprobante->sucursal->empresa->usuariosol || !$comprobante->sucursal->empresa->usuariosol) {
            $mensaje = response()->json([
                'title' => 'Configurar usuario y clave SOL para la emisión de comprobantes electrónicos !',
                'text' => 'No se pudo encontrar los datos de usuario y clave SOL para emitir guías de remisión a SUNAT.',
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

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
        $pass_firma = $comprobante->sucursal->empresa->passwordcert;
        $response = $objApi->enviarComprobante($comprobante->sucursal->empresa, $nombreXML, storage_path('app/company/cert/' . $comprobante->sucursal->empresa->cert), $pass_firma, storage_path('app/' . $ruta), storage_path('app/' . $ruta));

        if ($response->code == '0') {
            $comprobante->codesunat = $response->code;
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

        // dd($mensaje->getData());
        $comprobante->descripcion = $response->descripcion;
        $comprobante->save();
    }
}
