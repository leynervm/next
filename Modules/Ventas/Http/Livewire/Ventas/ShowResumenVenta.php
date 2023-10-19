<?php

namespace Modules\Ventas\Http\Livewire\Ventas;

use App\Helpers\GetClient;
use App\Models\Carshoop;
use App\Models\Carshoopserie;
use App\Models\Client;
use App\Models\Concept;
use App\Models\Empresa;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Opencaja;
use App\Models\Pricetype;
use App\Models\Tribute;
use App\Models\Typepayment;
use App\Rules\ValidateCarrito;
use App\Rules\ValidateDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Facturacion\Entities\Comprobante;
use App\Models\Typecomprobante;
use Modules\Ventas\Entities\Venta;

class ShowResumenVenta extends Component
{

    public $empresa, $typepayment, $methodpayment, $moneda;
    public $cotizacion_id, $client_id, $direccion_id, $pricetype_id, $mensaje;
    public $moneda_id, $opencaja, $methodpayment_id, $typepayment_id, $detallepago, $concept, $cuenta_id;
    public $typecomprobante, $typecomprobante_id, $tribute_id;
    public $document, $name, $direccion, $pricetypeasigned;

    public $incluyeigv = 0;
    public $increment = 0;
    public $countcuotas = 1;
    public $exonerado = 0;
    public $gravado = 0;
    public $igv = 0;
    public $descuentos = 0;
    public $otros = 0;
    public $subtotal = 0;
    public $total = 0;
    public $totalincrement = 0;

    public $accounts = [];
    public $cuotas = [];
    public $items = [];

    protected $listeners = ['render', 'deleteitem', 'deleteserie'];

    protected function rules()
    {
        return [
            // 'empresa_id' => ['required', 'integer', 'exists:empresas,id'],

            'document' => ['required', 'numeric', $this->typecomprobante->code == "01" ? 'digits:11' : new ValidateDocument],
            'name' => ['required', 'string', 'min:3'],
            'direccion' => ['required', 'string', 'min:3'],

            'client_id' => ['nullable', 'integer', 'min:1', 'exists:clients,id'],
            'direccion' => ['required', 'min:3'],
            'typecomprobante_id' => ['required', 'integer', 'exists:typecomprobantes,id'],
            'moneda_id' => ['required', 'integer', 'exists:monedas,id'],
            'typepayment_id' => ['required', 'integer', 'exists:typepayments,id'],
            'tribute_id' => ['required', 'integer', 'exists:tributes,id'],
            'increment' => [
                'nullable', 'numeric',  'min:0', 'decimal:0,2',
                Rule::requiredIf($this->typepayment->paycuotas == 1),
            ],
            'countcuotas' => [
                'nullable', 'integer', 'min:1',
                Rule::requiredIf($this->typepayment->paycuotas == 1),

            ],
            'concept.id' => [
                'required', 'integer', 'min:1', 'exists:concepts,id',
                Rule::requiredIf($this->typepayment->paycuotas == 0),

            ],
            'opencaja.id' => [
                'nullable', 'integer', 'min:1', 'exists:opencajas,id',
                Rule::requiredIf($this->typepayment->paycuotas == 0),

            ],
            'methodpayment_id' => [
                'nullable', 'integer', 'min:1', 'exists:methodpayments,id',
                Rule::requiredIf($this->typepayment->paycuotas == 0),

            ],
            'cuenta_id' => [
                'nullable', 'integer', 'min:1', 'exists:cuentas,id',
                Rule::requiredIf($this->methodpayment->cuentas->count() > 1),

            ],
            'detallepago' => ['nullable'],
            'cotizacion_id' => ['nullable', 'exists:cotizacions,id'],
            'empresa.id' => ['required', 'integer', 'exists:empresas,id'],
            'items' => ['required', 'array', 'min:1'],
        ];
    }

    public function mount(Empresa $empresa, Typepayment $typepayment, Methodpayment $methodpayment, Typecomprobante $typecomprobante, Moneda $moneda, Concept $concept, Opencaja $opencaja)
    {
        $this->empresa = $empresa;
        $this->methodpayment = $methodpayment;
        $this->typepayment = $typepayment;
        $this->typecomprobante = $typecomprobante;
        $this->concept = $concept;
        $this->opencaja = $opencaja;

        $this->methodpayment_id = $methodpayment->id ?? null;
        $this->typepayment_id = $typepayment->id ?? null;
        $this->typecomprobante_id = $typecomprobante->id ?? null;
        $this->moneda_id = $moneda->id ?? null;
        $this->moneda = $moneda;

        $this->total = Carshoop::Micarrito()->sum('total');
        $this->exonerado = $this->total ?? 0;
        $this->totalincrement = $this->total;
    }


    public function render()
    {
        $typecomprobantes = Typecomprobante::orderBy('code', 'asc')->get();
        $carrito = Carshoop::Micarrito()->with(['carshoopseries', 'producto', 'almacen'])->get();
        $typepayments = Typepayment::orderBy('name', 'asc')->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        $monedas = Moneda::orderBy('currency', 'asc')->get();
        return view('ventas::livewire.ventas.show-resumen-venta', compact('carrito', 'typecomprobantes', 'typepayments', 'methodpayments', 'monedas'));
    }

    public function updatedIncluyeigv($value)
    {
        $this->reset(['gravado', 'exonerado', 'igv']);
        $totalAmount = number_format($this->total, 4, '.', '');
        if ($this->increment) {
            $incremento = ($totalAmount * $this->increment) / 100;
            $totalAmount = number_format(($totalAmount + $incremento), 4, '.', '');
        }

        if ($value) {
            $this->igv = $totalAmount * 18 / 118;
            $this->gravado = $totalAmount - $this->igv;
        } else {
            $this->exonerado = $totalAmount;
        }
    }

    public function setTotal()
    {
        $totalAmount = Carshoop::Micarrito()->sum('total') ?? 0;
        $this->total = $totalAmount;

        if ($this->increment) {
            $incremento = ($totalAmount * $this->increment) / 100;
            $totalAmount = number_format(($totalAmount + $incremento), 4, '.', '');
            $this->totalincrement = number_format($totalAmount, 4, '.', '');
        }

        if ($this->incluyeigv) {
            $this->igv = $totalAmount * 18 / 118;
            $this->gravado = $totalAmount - $this->igv;
        } else {
            $this->exonerado = $totalAmount;
        }
    }

    public function updatedMonedaId($value)
    {
        if ($value) {
            $this->moneda = Moneda::findOrFail($value);
            $this->emitTo('ventas.ventas.create-venta', 'setMoneda', $value);
        }
    }

    public function updatedTypepaymentId($value)
    {
        $this->reset(['typepayment', 'increment', 'gravado', 'exonerado', 'igv', 'countcuotas', 'cuotas', 'accounts', 'cuenta_id']);
        $this->typepayment_id = !empty(trim($value)) ? trim($value) : null;
        $this->typepayment = Typepayment::findOrFail($value);

        $totalAmount = number_format($this->total, 4, '.', '');
        if ($this->incluyeigv) {
            $this->igv = $totalAmount * 18 / 118;
            $this->gravado = $totalAmount - $this->igv;
        } else {
            $this->exonerado = $totalAmount;
        }
    }

    public function updatedIncrement($value)
    {

        $totalAmount = number_format($this->total, 2, '.', '');

        if ((!empty(trim($value))) || $value > 0) {
            $incremento = ($totalAmount * trim($value)) / 100;
            $totalAmount = number_format($totalAmount + $incremento, 4, '.', '');
            $this->totalincrement = number_format($totalAmount, 4, '.', '');
        } else {
            $this->reset(['increment', 'totalincrement']);
            $this->resetValidation(['increment']);
        }
    }

    public function updatedMethodpaymentId($value)
    {

        $this->reset(['accounts', 'cuenta_id']);
        $this->methodpayment_id = !empty(trim($value)) ? trim($value) : null;
        if ($this->methodpayment_id) {
            $this->methodpayment = Methodpayment::findOrFail($value);
            $this->accounts = $this->methodpayment->cuentas;
            if ($this->methodpayment->cuentas->count() == 1) {
                $this->cuenta_id = $this->methodpayment->cuentas->first()->id;
            }
        }
    }

    public function save()
    {

        $this->document = trim($this->document);
        $this->direccion = trim($this->direccion);
        $carrito = Carshoop::Micarrito()->get();
        $this->items =  $carrito;

        $this->validate([
            'items' => [
                'required', 'array', 'min:1', new ValidateCarrito($this->moneda->id)
            ]
        ]);

        $this->tribute_id = Tribute::defaultTribute()->first()->id ?? null;
        $this->typecomprobante = Typecomprobante::find($this->typecomprobante_id);
        $totalAmount = number_format($this->total, 2, '.', '');
        $incremento = ($totalAmount * $this->increment) / 100;
        $this->totalincrement = number_format($totalAmount + $incremento, 4, '.', '');
        $client = Client::withTrashed()->where('document', $this->document)->first();

        if ($client) {
            if ($client->trashed()) {
                $client->pricetype_id = Pricetype::DefaultPricetype()->first()->id ?? null;
                $client->restore();
                $client->direccions()->create([
                    'name' => $this->direccion,
                ]);
            }
            $this->client_id  = $client->id;
        } else {

            DB::beginTransaction();
            try {
                $client = Client::create([
                    'document' => $this->document,
                    'name' => $this->name,
                    'sexo' => strlen(trim($this->document)) == 11 ? 'E' : null,
                    'pricetype_id' => Pricetype::DefaultPricetype()->first()->id ?? null,
                ]);

                $client->direccions()->create([
                    'name' => $this->direccion,
                ]);
                DB::commit();
                $this->client_id  = $client->id;
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }

        if ($this->typecomprobante) {

            $validateData = $this->validate();
            DB::beginTransaction();

            try {

                $numeracion = Comprobante::where('typecomprobante_id', $this->typecomprobante_id)
                    ->count();

                $venta = Venta::create([
                    'date' => now('America/Lima'),
                    'code' => 1,
                    'exonerado' => number_format($this->totalincrement, 4, '.', ''),
                    'gravado' => number_format($this->gravado, 4, '.', ''),
                    'descuento' => number_format($this->descuentos, 4, '.', ''),
                    'otros' => number_format($this->otros, 4, '.', ''),
                    'igv' => number_format($this->igv, 4, '.', ''),
                    'subtotal' => number_format($this->totalincrement, 4, '.', ''),
                    'total' => number_format($this->totalincrement, 4, '.', ''),
                    'tipocambio' => $this->moneda->code == 'USD' ? $this->empresa->tipocambio : null,
                    'increment' => $this->increment ?? 0,
                    'moneda_id' => $this->moneda_id,
                    'typepayment_id' => $this->typepayment_id,
                    'client_id' => $this->client_id,
                    'user_id' => Auth::user()->id,
                ]);

                if (!$this->typepayment->paycuotas) {
                    $venta->cajamovimiento()->create([
                        'date' => now('America/Lima'),
                        'amount' => number_format($this->total, 4, '.', ''),
                        'referencia' => $this->typecomprobante->serie . '-' . $numeracion + 1,
                        'detalle' => trim($this->detallepago),
                        'moneda_id' => $this->moneda_id,
                        'methodpayment_id' => $this->methodpayment_id,
                        'typemovement' => '+',
                        'cuenta_id' => $this->cuenta_id,
                        'concept_id' => $this->concept->id,
                        'opencaja_id' => $this->opencaja->id,
                        'user_id' => Auth::user()->id,
                    ]);
                }

                $comprobante = $venta->comprobante()->create([
                    'seriecompleta' => $this->typecomprobante->serie . '-' . $numeracion + 1,
                    'date' => now('America/Lima'),
                    'expire' => Carbon::now('America/Lima')->addDay(),
                    'direccion' => $this->direccion,
                    'exonerado' => number_format($this->totalincrement, 4, '.', ''),
                    'gravado' => number_format($this->gravado, 4, '.', ''),
                    'descuento' => number_format($this->descuentos, 4, '.', ''),
                    'otros' => number_format($this->otros, 4, '.', ''),
                    'igv' => number_format($this->igv, 4, '.', ''),
                    'subtotal' => number_format($this->totalincrement, 4, '.', ''),
                    'total' => number_format($this->totalincrement, 4, '.', ''),
                    'percent' => 18,
                    'referencia' => 'VT-',
                    'leyenda' => 'SON SOLES/100',
                    'client_id' => $this->client_id,
                    'tribute_id' => $this->tribute_id,
                    'typepayment_id' => $this->typepayment_id,
                    'typecomprobante_id' => $this->typecomprobante_id,
                    'moneda_id' => $this->moneda_id,
                    'empresa_id' => $this->empresa->id,
                    'user_id' => Auth::user()->id,
                ]);

                if ($this->typepayment->paycuotas) {
                    if ((!empty(trim($this->countcuotas))) || $this->countcuotas > 0) {

                        $totalAmount = number_format($this->total, 4, '.', '');

                        if ((!empty(trim($this->increment))) || $this->increment > 0) {
                            $incremento = ($totalAmount * $this->increment) / 100;
                            $totalAmount = number_format($totalAmount + $incremento, 4, '.', '');
                            // $this->totalincrement = number_format($totalAmount, 4, '.', '');
                        }

                        $amountCuota = number_format($totalAmount / $this->countcuotas, 4, '.', '');
                        $date = Carbon::now('America/Lima')->format('Y-m-d');

                        $sumaCuotas = 0.00;
                        for ($i = 1; $i <= $this->countcuotas; $i++) {
                            $sumaCuotas = number_format($sumaCuotas + $amountCuota, 4, '.', '');
                            if ($i == $this->countcuotas) {
                                $result =  number_format($totalAmount - $sumaCuotas, 4, '.', '');
                                $amountCuota = number_format($amountCuota + ($result), 4, '.', '');
                            }

                            $venta->cuotas()->create([
                                'cuota' => $i,
                                'amount' => $amountCuota,
                                'expiredate' => $date,
                                'user_id' => Auth::user()->id,
                            ]);
                            $date = Carbon::parse($date)->addMonth()->format('Y-m-d');
                        }
                    } else {
                        $this->addError('countcuotas', 'Ingrese cantidad válida de cuotas');
                    }
                }

                foreach ($carrito as $car) {

                    $priceunitincr = number_format(($car->price * $this->increment) / 100, 3, '.', '');
                    $pricesale = number_format($car->price + $priceunitincr, 3, '.', '');
                    $subtotal = number_format($pricesale * $car->cantidad, 3, '.', '');

                    $tvitem = $venta->tvitems()->create([
                        'date' => now('America/Lima'),
                        'cantidad' => $car->cantidad,
                        'pricebuy' => $car->pricebuy,
                        'price' => $pricesale,
                        'igv' => $car->igv,
                        'subtotal' => $subtotal,
                        'total' => $subtotal + $car->igv,
                        'status' => 0,
                        'increment' => $this->increment,
                        'almacen_id' => $car->almacen_id,
                        'producto_id' => $car->producto_id,
                        'user_id' => Auth::user()->id
                    ]);

                    if (count($car->carshoopseries)) {
                        foreach ($car->carshoopseries as $ser) {
                            $tvitem->itemseries()->create([
                                'date' => now('America/Lima'),
                                'status' => 0,
                                'serie_id' => $ser->serie_id,
                                'user_id' => Auth::user()->id,
                            ]);
                            $ser->serie()->update([
                                'status' => 2,
                                'dateout' => now('America/Lima')
                            ]);
                            $ser->delete();
                        }
                    }

                    $comprobante->facturableitems()->create([
                        'descripcion' => $car->producto->name,
                        'code' =>  $car->producto->sku,
                        'cantidad' => $car->cantidad,
                        'price' => $pricesale,
                        'igv' => $car->igv,
                        'subtotal' => $subtotal,
                        'total' => $subtotal + $car->igv
                    ]);

                    $car->delete();
                }

                DB::commit();
                $comprobante->referencia = 'VT-' . $venta->id;
                $comprobante->save();
                $this->resetValidation();
                $this->resetErrorBag();
                $this->reset();
                $this->dispatchBrowserEvent('created');
                return redirect()->route('admin.ventas');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        } else {
            $this->addError('typecomprobante_id', 'Error al obtener serie del comprobante.');
        }
    }

    public function deleteitem(Carshoop $carshoop)
    {

        DB::beginTransaction();

        try {

            $stock = $carshoop->producto->almacens->find($carshoop->almacen_id)->pivot->cantidad;

            if ($carshoop->carshoopseries()->exists()) {
                foreach ($carshoop->carshoopseries as $itemserie) {
                    $itemserie->serie()->update([
                        'status' => 0,
                        'dateout' => null
                    ]);
                }
                $carshoop->carshoopseries()->delete();
            }

            $carshoop->producto->almacens()->updateExistingPivot($carshoop->almacen_id, [
                'cantidad' => $stock + $carshoop->cantidad,
            ]);

            $carshoop->delete();
            DB::commit();
            $this->emitTo('ventas.ventas.create-venta', 'render');
            $this->setTotal();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteserie(Carshoopserie $carshoopserie)
    {

        DB::beginTransaction();
        try {
            $cantidad = $carshoopserie->carshoop->cantidad - 1;
            $carshoopserie->serie->dateout = null;
            $carshoopserie->serie->status = 0;
            $carshoopserie->serie->save();
            $carshoopserie->carshoop->cantidad = $cantidad;
            $carshoopserie->carshoop->subtotal = $carshoopserie->carshoop->price * $cantidad;
            $carshoopserie->carshoop->save();
            $carshoopserie->forceDelete();


            // FALTA CODIGO DEVOLVER STOCK ALMACEN CUANDO ELIMINO UNA SERIE





            DB::commit();
            $this->setTotal();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getClient()
    {
        if (strlen(trim($this->document)) == 8 || strlen(trim($this->document)) == 11) {
            $client = new GetClient();
            $response = $client->getClient(trim($this->document));
            if ($response->getData()) {
                if ($response->getData()->success) {
                    $this->resetValidation(['document', 'name', 'direccion']);
                    $this->name = $response->getData()->name;
                    $this->direccion = $response->getData()->direccion;
                    // $this->pricetype_id = $response->getData()->pricetype_id;
                    $this->pricetypeasigned = $response->getData()->pricetypeasigned;
                    $this->emitTo('ventas.ventas.create-venta', 'setPricetypeId', $response->getData()->pricetype_id);
                    // if ($this->client->nacimiento) {
                    //     $nacimiento = Carbon::parse($this->client->nacimiento)->format("d-m");
                    //     $hoy = Carbon::now('America/Lima')->format("d-m");

                    //     if ($nacimiento ==  $hoy) {
                    //         $this->mensaje = "FELÍZ CUMPLEAÑOS";
                    //     }
                    // }

                } else {
                    // dd($response);
                    $this->resetValidation(['document']);
                    $this->addError('document', $response->getData()->message);
                }
            } else {
                dd($response);
            }
        } else {
            $this->resetValidation(['document']);
            $this->addError('document', 'Documento inválido.');
        }
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-show-resumen-venta');
    }
}
