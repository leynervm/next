<?php

namespace App\Http\Livewire\Modules\Marketplace\Carrito;

use App\Models\Direccion;
use App\Models\Sucursal;
use App\Models\Ubigeo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;
use Livewire\Component;
use Modules\Marketplace\Entities\Order;
use Modules\Marketplace\Entities\Shipmenttype;
use Nwidart\Modules\Facades\Module;

class ShowShippments extends Component
{

    public $moneda;
    public $showaddadress = false;
    public $receiver = Order::EQUAL_RECEIVER;
    public $order = [];
    public $receiver_info = [
        'document' => '',
        'name' => '',
        'telefono' => null
    ];

    public $pricetype;
    public $lugar_id, $direccion, $referencia, $shipmenttype,
        $typecomprobante = '', $document_comprobante, $name_comprobante,
        $shipmenttype_id, $local_id, $daterecojo, $direccionenvio_id, $g_recaptcha_response;
    public $terms;
    public $cart = [];
    public $phoneuser = [];

    protected function rules()
    {
        return [
            'moneda.id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'shipmenttype_id' => ['required', 'integer', 'min:1', 'exists:shipmenttypes,id'],
            'local_id' => $this->shipmenttype->isRecojotienda() ?
                ['required', 'integer', 'min:1', 'exists:sucursals,id'] :
                ['nullable'],
            'daterecojo' => $this->shipmenttype->isRecojotienda() ?
                ['required', 'date', 'after_or_equal:today'] :
                ['nullable'],
            'direccionenvio_id' => $this->shipmenttype->isEnviodomicilio() ?
                ['required', 'integer', 'min:1', 'exists:direccions,id'] :
                ['nullable'],
            'receiver' => ['required', 'integer', Rule::in([Order::EQUAL_RECEIVER, Order::OTHER_RECEIVER])],
            'receiver_info.document' => ['required', 'string', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/'],
            'receiver_info.name' => ['required', 'string', 'min:8',],
            'receiver_info.telefono' => ['required', 'numeric', 'digits:9', 'regex:/^\d{9}$/'],
            'typecomprobante' => Module::isEnabled('Facturacion') ?
                ['required', Rule::in(['', Order::BOLETA, Order::FACTURA])] :
                ['nullable'],
            'document_comprobante' => Module::isEnabled('Facturacion') ?
                [
                    'required',
                    'string',
                    'numeric',
                    'digits_between:8,11',
                    'regex:/^\d{8}(?:\d{3})?$/',
                    $this->typecomprobante == Order::FACTURA ? 'regex:/^\d{11}$/' : ''
                ] : ['nullable'],
            'name_comprobante' => Module::isEnabled('Facturacion') ?
                ['required', 'string', 'min:6'] :
                ['nullable'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ];
    }

    public function getValidationAttributes()
    {
        return [
            'document_comprobante' => $this->typecomprobante == Order::FACTURA ? 'ruc' : 'dni',
            'name_comprobante' => $this->typecomprobante == Order::FACTURA ? 'razón social' : 'nombres completos',
            'typecomprobante' => 'tipo de comprobante',
        ];
    }

    public function mount($pricetype = null)
    {
        $this->pricetype = $pricetype;
        $this->moneda = view()->shared('moneda');
        $this->shipmenttype = new Shipmenttype();
        $this->receiver_info = [
            'document' => auth()->user()->document,
        ];
        Self::searchclient('document');
        $this->direccionenvio_id = auth()->user()->direccions()->default()->first()->id ?? null;
    }

    public function render()
    {
        $ubigeos = Ubigeo::query()->select('id', 'region', 'provincia', 'distrito', 'ubigeo_reniec')
            ->orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        $direccions = auth()->user()->direccions()->with('ubigeo')->orderByDesc('default')->orderBy('name', 'asc')->get();
        $shipmenttypes = Shipmenttype::orderBy('name', 'asc')->get();
        $locals = mi_empresa()->sucursals()->with('ubigeo')->orderByDesc('default')->orderBy('codeanexo', 'asc')->get();
        $shoppings = getCartRelations('shopping', true);

        return view('livewire.modules.marketplace.carrito.show-shippments', compact('shoppings', 'shipmenttypes', 'direccions', 'ubigeos', 'locals'));
    }

    public function validateorder()
    {

        if ($this->shipmenttype_id) {
            $this->shipmenttype = Shipmenttype::find($this->shipmenttype_id);
        }
        // $this->g_recaptcha_response = $recaptcha;
        $validateData = $this->validate();
        $carshoop = getCartRelations('shopping', true);
        if (count($carshoop) == 0) {
            $mensaje = response()->json([
                'title' => 'CARRITO DE COMPRAS ESTÁ VACÍO !',
                'text' => null,
                'type' => 'warning'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        foreach ($carshoop as $item) {
            if (!$item->options->is_disponible) {
                if (!empty($item->options->promocion_id)) {
                    $mensaje = response()->json([
                        'title' => 'PROMOCIÓN NO SE ENCUENTRA DISPONIBLE !',
                        'text' => null,
                        'type' => 'warning'
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                } else {
                    $mensaje = response()->json([
                        'title' => 'PRODUCTO NO SE ENCUENTRA DISPONIBLE !',
                        'text' => null,
                        'type' => 'warning'
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }
            }
        }

        $monedascart_id = $carshoop->map(fn($item) => $item->options->moneda_id)->unique()->values()->toArray();
        $diferencia = array_diff($monedascart_id, [$this->moneda->id ?? 0]);

        if (count($diferencia) > 0) {
            $mensaje = response()->json([
                'title' => 'EXISTEN PRODUCTOS EN EL CARRITO CON DISTINTAS MONEDAS !',
                'text' => null,
                'type' => 'warning'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        $direccion_envio = $this->shipmenttype->isEnviodomicilio() ? Direccion::with('ubigeo')->find($this->direccionenvio_id) : null;
        $local_entrega = $this->shipmenttype->isRecojotienda() ? Sucursal::with('ubigeo')->find($this->local_id) : null;

        $this->order = [
            'g_recaptcha_response' => $this->g_recaptcha_response,
            'moneda_id' => $this->moneda->id,
            'shipmenttype' => $this->shipmenttype,
            'local_entrega' => $local_entrega,
            'daterecojo' => $this->daterecojo,
            'direccion_envio' => $direccion_envio,
            'receiver' => $this->receiver,
            'receiver_info' => $this->receiver_info,
            'typecomprobante' => $this->typecomprobante,
            'document_comprobante' => $this->document_comprobante,
            'name_comprobante' => $this->name_comprobante,
            'terms' => $this->terms,
        ];
        return $this->order;
    }

    public function savedireccion()
    {
        if ($this->shipmenttype_id) {
            $tipoenvio = Shipmenttype::find($this->shipmenttype_id);
            if ($tipoenvio->isEnviodomicilio()) {
                $this->validate([
                    'shipmenttype_id' => ['required', 'integer', 'min:1', 'exists:shipmenttypes,id'],
                    'lugar_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
                    'direccion' => ['required', 'string', 'min:6'],
                    'referencia' => ['required', 'string', 'min:3'],
                ]);

                DB::beginTransaction();
                try {
                    $default = auth()->user()->direccions()->default()->count();
                    $direccion = auth()->user()->direccions()->create([
                        'name' => $this->direccion,
                        'referencia' => $this->referencia,
                        'default' => $default > 0 ? 0 : Direccion::DEFAULT,
                        'ubigeo_id' => $this->lugar_id
                    ]);
                    DB::commit();
                    if ($direccion->isDefault()) {
                        $this->direccionenvio_id = $direccion->id;
                    }
                    $this->reset(['direccion', 'referencia', 'lugar_id', 'showaddadress']);
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    DB::rollBack();
                    throw $e;
                }
            } else {
                $mensaje = response()->json([
                    'title' => 'TIPO DE ENVIÓ NO VALIDO PARA REGISTRAR DIRECCIÓN !',
                    'text' => 'El tipo de envío no permite registrar dirección de envio.',
                    'type' => 'warning'
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
            }
        }
    }

    public function savedefaultdireccion($direccion_id)
    {
        auth()->user()->direccions()->each(function ($item) use ($direccion_id) {
            $item->update([
                'default' => $item->id == $direccion_id ? Direccion::DEFAULT : 0,
            ]);
        });

        $this->direccionenvio_id = $direccion_id;
    }

    public function deletedireccion($direccion_id)
    {
        Direccion::find($direccion_id)->delete();
        if (auth()->user()->direccions()->default()->count() == 0 && count(auth()->user()->direccions) > 0) {
            auth()->user()->direccions()->orderBy('name', 'asc')->first()->update([
                'default' => Direccion::DEFAULT
            ]);
        }
    }

    public function searchclient($property = '')
    {
        $this->resetValidation();
        if ($property == 'document') {
            $this->receiver_info['document'] = trim($this->receiver_info['document']);
            $this->validate([
                'receiver_info.document' => ['required', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/']
            ]);
        } else {
            $this->document_comprobante = trim($this->document_comprobante);
            $this->validate([
                'document_comprobante' => [
                    'required',
                    'numeric',
                    'digits_between:8,11',
                    'regex:/^\d{8}(?:\d{3})?$/',
                    $this->typecomprobante == Order::FACTURA ? 'regex:/^\d{11}$/' : ''
                ]
            ]);
        }
        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $property == 'document' ? $this->receiver_info['document'] : $this->document_comprobante,
            'searchbd' => true,
            'autosaved' => $property == 'document' ? false : true,
            'savedireccions' => $property == 'document' ? false : true,
            'obtenerlista' => $property == 'document' ? false : true,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());
            if (isset($cliente->success) && $cliente->success) {
                if ($property == 'document') {
                    $this->receiver_info['name'] = $cliente->name;
                    $this->receiver_info['telefono'] = $cliente->telefono;
                } else {
                    $this->name_comprobante = $cliente->name;
                }
            } else {
                if ($property == 'document') {
                    $this->receiver_info['name'] = '';
                    $this->receiver_info['telefono'] = '';
                    $this->addError('receiver_info.document', $cliente->error);
                } else {
                    $this->name_comprobante = '';
                    $this->addError('name_comprobante', $cliente->error);
                }
            }
        } else {
            $mensaje =  response()->json([
                'title' => 'Error:' . $response->status() . ' ' . $response->json(),
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }
    }

    public function updatedReceiver($value)
    {
        if ($value == Order::EQUAL_RECEIVER) {
            $this->receiver_info['document'] = auth()->user()->document;
            self::searchclient('document');
        } else {
            $this->reset(['receiver_info']);
            $this->resetValidation();
        }
    }
}
