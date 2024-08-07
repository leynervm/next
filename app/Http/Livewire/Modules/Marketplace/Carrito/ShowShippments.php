<?php

namespace App\Http\Livewire\Modules\Marketplace\Carrito;

use App\Models\Almacen;
use App\Models\Direccion;
use App\Models\Moneda;
use App\Models\Pricetype;
use App\Models\Ubigeo;
use App\Rules\Recaptcha;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;
use Livewire\Component;
use Modules\Marketplace\Entities\Order;
use Modules\Marketplace\Entities\Shipmenttype;
use Modules\Marketplace\Entities\Trackingstate;

class ShowShippments extends Component
{

    public Moneda $moneda;

    public $showaddadress = false;
    public $receiver = Order::EQUAL_RECEIVER;
    public $receiver_info = [
        'document' => null,
        'name' => null,
        'telefono' => null
    ];

    public $lugar_id, $direccion, $referencia, $shipmenttype,
        $shipmenttype_id, $local_id, $daterecojo, $direccionenvio_id, $g_recaptcha_response;
    public $terms;
    public $cart = [];
    public $phoneuser = [];

    protected function rules()
    {
        return [
            'moneda.id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'shipmenttype_id' => ['required', 'integer', 'min:1', 'exists:shipmenttypes,id'],
            'local_id' => [
                'nullable',
                Rule::requiredIf($this->shipmenttype->isRecojotienda()),
                'integer', 'min:1', 'exists:sucursals,id'
            ],
            'daterecojo' => [
                'nullable',
                Rule::requiredIf($this->shipmenttype->isRecojotienda()),
                'date', 'after_or_equal:today'
            ],
            'direccionenvio_id' => [
                'nullable',
                Rule::requiredIf($this->shipmenttype->isEnviodomicilio()),
                'integer', 'min:1', 'exists:direccions,id'
            ],
            'receiver' => [
                'required', 'integer',
                Rule::in([Order::EQUAL_RECEIVER, Order::OTHER_RECEIVER])
            ],
            'receiver_info.document' => [
                'required', 'string', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/'
            ],
            'receiver_info.name' => ['required', 'string', 'min:8',],
            'receiver_info.telefono' => ['required', 'numeric', 'digits:9', 'regex:/^\d{9}$/'],
            'cart' => ['required', 'array', 'min:1'],
            'terms' => ['required', 'boolean', 'min:0', 'max:1'],
            'g_recaptcha_response' => ['required', new Recaptcha()],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ];
    }

    public function mount()
    {

        $this->shipmenttype = new Shipmenttype();
        $this->phoneuser = auth()->user()->telephones()
            ->orderBy('default', 'desc')->orderBy('id', 'desc')->first();

        $this->receiver_info = [
            'document' => auth()->user()->document,
            'name' => auth()->user()->name,
            'telefono' => $this->phoneuser ? $this->phoneuser->phone : null
        ];
    }

    public function render()
    {
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        $direccions = auth()->user()->direccions()->with('ubigeo')->orderBy('default', 'desc')->orderBy('name', 'asc')->get();
        $shipmenttypes = Shipmenttype::orderBy('name', 'asc')->get();
        $locals = mi_empresa()->sucursals()->with('ubigeo')->orderBy('default', 'desc')->orderBy('codeanexo', 'asc')->get();
        return view('livewire.modules.marketplace.carrito.show-shippments', compact('shipmenttypes', 'direccions', 'ubigeos', 'locals'));
    }

    public function save($recaptcha)
    {

        $this->g_recaptcha_response = $recaptcha;
        if (Cart::instance('shopping')->count() > 0) {
            $count = 0;
            foreach (Cart::instance('shopping')->content() as $item) {
                if (is_null($item->model)) {
                    Cart::instance('shopping')->get($item->rowId);
                    Cart::instance('shopping')->remove($item->rowId);
                    $count++;
                }
            }
            if (auth()->check()) {
                Cart::instance('shopping')->store(auth()->id());
            }
            if ($count > 0) {
                $mensaje = response()->json([
                    'title' => "ALGUNOS PRODUCTOS FUERON REMOVIDOS DEL CARRITO.",
                    'text' => 'Carrito de compras actualizado, algunos productos han dejado de estar disponibles en tienda web.',
                    'type' => 'warning'
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        }

        $this->cart = Cart::instance('shopping')->content()->toArray();
        $monedascart_id = Arr::pluck($this->cart, 'options.moneda_id');
        $diferencia = array_diff($monedascart_id, [$this->moneda->id ?? 0]);
        if (count($diferencia) > 0) {
            $mensaje = response()->json([
                'title' => 'EXISTEN PRODUCTOS EN EL CARRITO CON EL PRECIO DE UNA MONEDA DIFERENTE A LA COMPRA !',
                'text' => 'No se puede realizar compra de productos con distintos tipos de moneda en una sola compra.',
                'type' => 'warning'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        $this->direccionenvio_id = auth()->user()->direccions()->default()->first()->id ?? null;
        if ($this->shipmenttype_id) {
            $this->shipmenttype = Shipmenttype::find($this->shipmenttype_id);
        }
        $validateData = $this->validate();

        DB::beginTransaction();
        try {

            $client = auth()->user()->client()->updateOrCreate(
                ['document' => auth()->user()->document],
                [
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'pricetype_id' => mi_empresa()->usarLista() ? Pricetype::default()->first()->id ?? null : null,
                ]
            );

            $order = Order::create([
                'date' => now('America/Lima'),
                'seriecompleta' => 'order-',
                'exonerado' => number_format(Cart::instance('shopping')->subtotal(), 3, '.', ''),
                'gravado' => 0,
                'igv' => 0,
                'subtotal' => number_format(Cart::instance('shopping')->subtotal(), 3, '.', ''),
                'total' => number_format(Cart::instance('shopping')->subtotal(), 3, '.', ''),
                'tipocambio' => number_format(mi_empresa()->tipocambio ?? 0, 3, '.', ''),
                'receiverinfo' => $this->receiver_info,
                'direccion_id' => $this->shipmenttype->isEnviodomicilio() ? $this->direccionenvio_id : null,
                'moneda_id' => $this->moneda->id,
                'client_id' => $client->id,
                'shipmenttype_id' => $this->shipmenttype_id,
                'user_id' => auth()->user()->id,
            ]);

            $order->seriecompleta = $order->seriecompleta . $order->id;
            $order->save();

            if (Trackingstate::default()->exists()) {
                $order->trackings()->create([
                    'date' => now(),
                    'descripcion' => 'PEDIDO REGISTRADO CORRECTAMENTE',
                    'trackingstate_id' => Trackingstate::default()->first()->id,
                    'user_id' => auth()->user()->id
                ]);
            }

            if ($this->shipmenttype->isRecojotienda()) {
                $order->entrega()->create([
                    'date' => $this->daterecojo,
                    'sucursal_id' => $this->local_id
                ]);
            }

            $counter = 1;
            foreach (Cart::instance('shopping')->content() as $item) {
                $order->tvitems()->create([
                    'date' => now('America/Lima'),
                    'cantidad' => formatDecimalOrInteger($item->qty),
                    'pricebuy' => number_format($item->options->pricebuy, 2, '.', ''),
                    'price' => number_format($item->price, 2, '.', ''),
                    'igv' => number_format($item->options->igv, 2, '.', ''),
                    'subtotaligv' => number_format($item->options->subtotaligv, 2, '.', ''),
                    'subtotal' => number_format($item->subtotal, 2, '.', ''),
                    'total' => number_format($item->subtotal, 2, '.', ''),
                    'status' => 0,
                    'alterstock' => Almacen::NO_ALTERAR_STOCK,
                    'gratuito' => 0,
                    'almacen_id' => null,
                    'producto_id' => $item->id,
                    'user_id' => auth()->user()->id
                ]);
            }
            DB::commit();
            Cart::instance('shopping')->destroy();
            $this->resetExcept(['moneda', 'shipmenttype', 'phoneuser']);
            return redirect()->route('orders.payment', $order);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
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
                    auth()->user()->direccions()->create([
                        'name' => $this->direccion,
                        'referencia' => $this->referencia,
                        'default' => $default > 0 ? 0 : Direccion::DEFAULT,
                        'ubigeo_id' => $this->lugar_id
                    ]);
                    DB::commit();
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
}
