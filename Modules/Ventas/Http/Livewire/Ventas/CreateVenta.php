<?php

namespace Modules\Ventas\Http\Livewire\Ventas;

use App\Helpers\GetPrice;
use App\Models\Caja;
use App\Models\Cajamovimiento;
use App\Models\Carshoop;
use App\Models\Category;
use App\Models\Channelsale;
use App\Models\Client;
use App\Models\Concept;
use App\Models\Direccion;
use App\Models\Empresa;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Opencaja;
use App\Models\Pricetype;
use App\Models\Serie;
use App\Models\Tribute;
use App\Models\Ubigeo;
use App\Rules\CampoUnique;
use App\Rules\ValidateDocument;
use App\Rules\ValidateNacimiento;
use App\Rules\ValidateSerieRequerida;
use App\Rules\ValidateStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Almacen\Entities\Almacen;
use Modules\Almacen\Entities\Producto;
use Modules\Facturacion\Entities\Comprobante;
use Modules\Facturacion\Entities\Typecomprobante;
use Modules\Facturacion\Entities\Typepayment;
use Modules\Ventas\Entities\Venta;

class CreateVenta extends Component
{

    public $client;
    public $typepayment;
    public $methodpayment;

    public $open = false;
    public $disponibles = 1;

    public $cotizacion_id, $moneda_id;
    public $clients = [];
    public $client_id, $direccion_id, $producto_id, $serie_id,
        $almacen_id, $pricetype_id;
    public $mensaje;
    public $search = '';
    public $searchserie = '';
    public $searchalmacen = '';
    public $searchcategory = '';

    public $typecomprobante_id;
    public $typepayment_id;
    public $increment = 0;
    public $countcuotas = 1;
    public $stepDecimal = "0.01";
    public $decimal = 2;
    public $opencaja_id, $methodpayment_id, $detallepago, $concept_id, $cuenta_id;
    public $tribute_id, $empresa_id;

    public $document, $nameclient, $sexo, $email, $nacimiento,
        $direccion, $ubigeo_id, $pricetypeclient_id, $channelsale_id, $telefono;

    protected $queryString = [
        // 'search' => ['except' => ''],
        // 'searchserie' => ['except' => ''],
        // 'searchalmacen' => ['except' => ''],
        // 'searchcategory' => ['except' => ''],
    ];

    public $cart = [];
    public $cuotas = [];
    public $accounts = [];

    public $exonerado = 0;
    public $gravado = 0;
    public $igv = 0;
    public $descuentos = 0;
    public $otros = 0;
    public $subtotal = 0;
    public $total = 0;
    public $totalIncrement = 0;


    protected function rules()
    {
        return [
            // 'empresa_id' => ['required', 'integer', 'exists:empresas,id'],
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'direccion_id' => ['required', 'integer', 'exists:direccions,id'],
            'typecomprobante_id' => ['required', 'integer', 'exists:typecomprobantes,id'],
            'moneda_id' => ['required', 'integer', 'exists:monedas,id'],
            'typepayment_id' => ['required', 'integer', 'exists:typepayments,id'],
            'tribute_id' => ['required', 'integer', 'exists:tributes,id'],
            'increment' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 1),
                'numeric', 'decimal:0,2', 'min:0'
            ],
            'countcuotas' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 1),
                'integer', 'min:1'
            ],
            'concept_id' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer', 'exists:concepts,id'
            ],
            'opencaja_id' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer', 'exists:opencajas,id'
            ],
            'methodpayment_id' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer', 'exists:methodpayments,id'
            ],
            'cuenta_id' => [
                'nullable',
                Rule::requiredIf($this->methodpayment->cuentas->count() > 1),
                'integer', 'exists:cuentas,id'
            ],
            'detallepago' => ['nullable'],
            'cotizacion_id' => ['nullable', 'exists:cotizacions,id']
        ];
    }

    public function mount()
    {
        $this->client = new Client();
        $this->typepayment = new Typepayment();
        $this->methodpayment = new Methodpayment();
        $this->clients = Client::orderBy('name', 'asc')->get();
        $this->moneda_id = Moneda::defaultMoneda()->first()->id ?? null;
        $this->pricetype_id = Pricetype::defaultPricetype()->first()->id ?? null;
        $this->typepayment = Typepayment::defaultTypepayment()->first();
        $this->typepayment_id = Typepayment::defaultTypepayment()->first()->id ?? null;
        $this->methodpayment_id = Methodpayment::defaultMethodPayment()->first()->id ?? null;
        $this->typecomprobante_id = Typecomprobante::defaultTypecomprobante()->first()->id ?? null;
        $this->opencaja_id =  Opencaja::CajasAbiertas()->CajasUser()->orderBy('startdate', 'desc')->first()->id ?? null;
        $this->total = Carshoop::where('user_id', Auth::user()->id)->sum('total');
        $this->totalIncrement = $this->total;
        $this->decimal = Pricetype::defaultPricetype()->first()->decimalrounded ?? 2;

        $stepDecimal = Pricetype::defaultPricetype()->first()->decimalrounded ?? 2;
        $step = "";
        for ($i = 0; $i < $stepDecimal; $i++) {
            $step .= '0';
        }
        $this->stepDecimal = "0." . substr($step . "1", -$stepDecimal);
    }

    public function render()
    {
        $productos = Producto::select('id', 'name', 'pricebuy', 'priceusbuy', 'category_id')->orderBy('name', 'asc');
        // $categories = Category::orderBy('name', 'asc')->get();
        $pricetypes = Pricetype::orderBy('name', 'asc')->get();
        $almacens = Almacen::orderBy('name', 'asc')->get();
        $carrito = Carshoop::where('user_id', Auth::user()->id)->get();
        $typecomprobantes = Typecomprobante::orderBy('code', 'asc')->get();
        $typepayments = Typepayment::orderBy('name', 'asc')->get();
        $opencajas = Opencaja::CajasAbiertas()->CajasUser()->orderBy('startdate', 'desc')->get();
        $monedas = Moneda::orderBy('currency', 'asc')->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();

        $pricetypes = Pricetype::orderBy('name', 'asc')->get();
        $ubigeos = Ubigeo::all();
        $channelsales = Channelsale::orderBy('name', 'asc')->get();

        if (trim($this->searchalmacen) !== "") {
            $productos->whereHas('almacens', function ($query) {
                $query->where('almacen_id', $this->searchalmacen);
            })->with(['almacens' => function ($query) {
                $query->where('almacen_id', $this->searchalmacen);
            }]);
        }

        // if (trim($this->searchcategory) !== "") {
        //     $productos->where('category_id', $this->searchcategory);
        // }

        if (trim($this->search) !== "") {
            $productos->where('name', 'ilike',  $this->search . '%');
        }

        if ($this->disponibles) {
            $productos->whereHas('disponiblealmacens', function ($query) {
            })->with(['disponiblealmacens' => function ($query) {
            }]);
        }

        // if (trim($this->searchserie) !== "") {
        //     $productos->whereHas('series', function ($query) {
        //         $query->where('serie', $this->searchserie);
        //     })
        //         ->with(['series' => function ($query) {
        //             $query->where('serie', $this->searchserie);
        //         }]);
        // }


        $productos = $productos->paginate();
        return view('ventas::livewire.ventas.create-venta', compact('productos', 'pricetypes', 'almacens', 'carrito', 'typecomprobantes', 'typepayments', 'opencajas', 'methodpayments', 'monedas', 'pricetypes', 'ubigeos', 'channelsales'));
    }

    public function getProductoBySerie($value)
    {
        $this->searchserie = trim($this->searchserie);
        $validateData = $this->validate([
            'searchserie' => ['required', 'min:3']
        ]);

        $serie = Serie::where('serie', trim(mb_strtoupper($value, "UTF-8")))
            ->where('status', 0)->first();
        if ($serie) {

            $precios = GetPrice::getPriceProducto($serie->producto, $this->pricetype_id)->getData();

            if ($precios->success) {

                DB::beginTransaction();

                try {

                    $stock = $serie->producto->almacens->find($serie->almacen_id)->pivot->cantidad;

                    if ($stock > 0) {

                        if (($stock - 1) >= 0) {

                            $carrito = Carshoop::where('producto_id',  $serie->producto->id)
                                ->where('almacen_id',  $serie->almacen_id)->where('status', 0)->first();

                            if ($carrito) {
                                $carrito->cantidad = $carrito->cantidad + 1;
                                $carrito->igv = $carrito->igv + $serie->producto->igv;
                                $carrito->subtotal = $carrito->subtotal + $precios->pricewithdescount;
                                $carrito->total = $carrito->total + $precios->pricewithdescount;
                                $carrito->save();
                            } else {
                                $carrito = Carshoop::create([
                                    'date' => Carbon::now('America/Lima'),
                                    'cantidad' => 1,
                                    'pricebuy' => $serie->producto->pricebuy,
                                    'price' => $precios->pricewithdescount,
                                    'igv' => $serie->producto->igv,
                                    'subtotal' => $precios->pricewithdescount,
                                    'total' => $precios->pricewithdescount,
                                    'status' => 0,
                                    'almacen_id' => $serie->almacen_id,
                                    'producto_id' => $serie->producto->id,
                                    'user_id' => Auth::user()->id,
                                ]);
                            }

                            $carrito->carshoopseries()->create([
                                'date' => Carbon::now('America/Lima'),
                                'serie_id' => $serie->id
                            ]);

                            $serie->update([
                                'status' => 1,
                                'dateout' => Carbon::now('America/Lima')
                            ]);

                            $serie->producto->almacens()->updateExistingPivot($serie->almacen_id, [
                                'updated_at' => now('America/Lima'),
                                'cantidad' => $stock - 1,
                                'user_id' => Auth::user()->id
                            ]);

                            DB::commit();
                            $this->resetValidation();
                            $this->dispatchBrowserEvent('created');
                            $this->reset(['searchserie']);
                            $this->total = Carshoop::where('user_id', Auth::user()->id)->sum('total');
                            if ($this->typepayment->paycuotas) {
                                $this->calcular_cuotas();
                            }
                        } else {
                            $this->addError('searchserie', 'Cantidad supera al stock disponible.');
                        }
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    DB::rollBack();
                    throw $e;
                }
            } else {
                $this->addError('searchserie', $precios->error);
                // dd($precios->error);
            }
        } else {
            $this->addError('searchserie', 'Serie no encontrada');
        }
    }

    public function updatedClientId($value)
    {
        $this->reset(['direccion_id', 'stepDecimal']);
        $this->client = Client::find($value);

        if (count($this->client->direccions)) {
            $this->direccion_id = $this->client->direccions->first()->id ?? null;
        }

        if ($this->client->nacimiento) {
            $nacimiento = Carbon::parse($this->client->nacimiento)->format("d-m");
            $hoy = Carbon::now('America/Lima')->format("d-m");

            if ($nacimiento ==  $hoy) {
                $this->mensaje = "FELÍZ CUMPLEAÑOS";
            }
        }

        if ($this->client->pricetype_id) {
            $this->pricetype_id = $this->client->pricetype_id;
            $this->decimal = $this->client->pricetype->decimalrounded ?? 2;

            $stepDecimal = $this->client->pricetype->decimalrounded ?? 2;
            $step = "";
            for ($i = 0; $i < $stepDecimal; $i++) {
                $step .= '0';
            }
            $this->stepDecimal = "0." . substr($step . "1", -$stepDecimal);
        }
    }

    public function updatedTypepaymentId($value)
    {

        $this->reset(['typepayment', 'increment', 'countcuotas', 'cuotas', 'methodpayment_id', 'accounts', 'cuenta_id']);
        $this->typepayment_id = !empty(trim($value)) ? trim($value) : null;
        $this->typepayment = Typepayment::findOrFail($value);
        if ($this->typepayment->paycuotas) {
            $this->calcular_cuotas();
        }


        // if ($this->client->pricetype_id) {
        //     $this->pricetype_id = $this->client->pricetype_id;
        // }
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

    public function addtocar($formData, Producto $producto)
    {

        $this->resetValidation(["errorstocart.*"]);
        $price = trim($formData["price"]);
        $almacen_id = isset($formData["almacen_$producto->id[]"]) ? trim($formData["almacen_$producto->id[]"]) : null;
        $serie = isset($formData["serie"]) ? trim($formData["serie"]) : "";
        $cantidad = isset($formData["cantidad"]) ? trim($formData["cantidad"]) : 1;

        $this->cart[$producto->id]["price"] = $price;
        $this->cart[$producto->id]["almacen_id"] = $almacen_id;
        $this->cart[$producto->id]["serie"] = $serie;
        $this->cart[$producto->id]["cantidad"] = $cantidad;

        $validateDate = $this->validate([
            "cart.$producto->id.price" => ['required', 'numeric', 'decimal:0,4'],
            "cart.$producto->id.almacen_id" => [
                'required', 'integer', 'exists:almacens,id',
                new ValidateStock($producto->id, $almacen_id)
            ],
            "cart.$producto->id.cantidad" => ['required', 'numeric', 'min:1', 'decimal:0,2'],
            "cart.$producto->id.serie" => [
                Rule::requiredIf($producto->seriesdisponibles()->exists()),
                new ValidateSerieRequerida($producto->id, $almacen_id, $serie),
            ]
        ]);

        DB::beginTransaction();

        try {

            $stock = $producto->almacens->find($almacen_id)->pivot->cantidad;

            if ($stock > 0) {
                if (($stock - $cantidad) >= 0) {

                    $carrito = Carshoop::where('producto_id',  $producto->id)
                        ->where('almacen_id',  $almacen_id)->where('status', 0)->first();

                    if ($carrito) {
                        $carrito->cantidad = $carrito->cantidad + $cantidad;
                        $carrito->igv = $carrito->igv + ($cantidad * $producto->igv);
                        $carrito->subtotal = $carrito->subtotal + ($price * $cantidad);
                        $carrito->total = $carrito->total + ($price * $cantidad);
                        $carrito->save();
                    } else {
                        $carrito = Carshoop::create([
                            'date' => Carbon::now('America/Lima'),
                            'cantidad' => $cantidad,
                            'pricebuy' => $producto->pricebuy,
                            'price' => $price,
                            'igv' => $cantidad * $producto->igv,
                            'subtotal' => $price * $cantidad,
                            'total' => $price * $cantidad,
                            'status' => 0,
                            'almacen_id' => $almacen_id,
                            'producto_id' => $producto->id,
                            'user_id' => Auth::user()->id,
                        ]);
                    }

                    $existseries = $producto->seriesdisponibles()
                        ->where('almacen_id', $almacen_id)
                        ->exists();

                    $serieProducto = Serie::where('serie', trim(mb_strtoupper($serie, "UTF-8")))->first();

                    if ($existseries) {
                        if ($serieProducto) {
                            $carrito->carshoopseries()->create([
                                'date' => Carbon::now('America/Lima'),
                                'serie_id' => $serieProducto->id
                            ]);

                            $serieProducto->update([
                                'status' => 1,
                                'dateout' => Carbon::now('America/Lima')
                            ]);
                        }
                    }

                    $producto->almacens()->updateExistingPivot($almacen_id, [
                        'updated_at' => now('America/Lima'),
                        'cantidad' => $stock - $cantidad,
                        'user_id' => Auth::user()->id
                    ]);

                    DB::commit();
                    $this->resetValidation();
                    $this->dispatchBrowserEvent('reset-form', $producto->id);
                    $this->dispatchBrowserEvent('created');
                    $this->total = Carshoop::where('user_id', Auth::user()->id)->sum('total');
                    if ($this->typepayment->paycuotas) {
                        $this->calcular_cuotas();
                    }
                } else {
                    $this->addError("cart.$producto->id.cantidad", 'Cantidad supera al stock disponible.');
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function save()
    {

        $this->concept_id = Concept::defaultConceptVentas()->first()->id ?? null;
        $this->tribute_id = Tribute::defaultTribute()->first()->id ?? null;
        $this->empresa_id = Empresa::defaultEmpresa()->first()->id ?? null;

        $carrito = Carshoop::where('user_id', Auth::user()->id)->get();
        $serie = Typecomprobante::findOrFail($this->typecomprobante_id);

        $totalAmount = number_format($this->total, 2, '.', '');
        $incremento = ($totalAmount * $this->increment) / 100;
        $this->totalIncrement = number_format($totalAmount + $incremento, 2, '.', '');

        if (count($carrito)) {
            if ($serie) {

                $validateData = $this->validate();
                DB::beginTransaction();

                try {
                    $cajamovimiento = null;

                    if ($this->typepayment) {
                        if ($this->typepayment->paycuotas == 0) {
                            $cajamovimiento = Cajamovimiento::create([
                                'date' => now('America/Lima'),
                                'amount' => number_format($this->total, 2, '.', ''),
                                'referencia' => null,
                                'detalle' => null,
                                'moneda_id' => $this->moneda_id,
                                'methodpayment_id' => $this->methodpayment_id,
                                'typemovement' => '+',
                                'cuenta_id' => $this->cuenta_id,
                                'concept_id' => $this->concept_id,
                                'opencaja_id' => $this->opencaja_id,
                                'user_id' => Auth::user()->id,
                            ]);
                        }
                    }

                    $venta = Venta::create([
                        'date' => now('America/Lima'),
                        'code' => 1,
                        'exonerado' => number_format($this->totalIncrement, 2, '.', ''),
                        'gravado' => number_format($this->gravado, 2, '.', ''),
                        'descuento' => number_format($this->descuentos, 2, '.', ''),
                        'otros' => number_format($this->otros, 2, '.', ''),
                        'igv' => number_format($this->igv, 2, '.', ''),
                        'subtotal' => number_format($this->totalIncrement, 2, '.', ''),
                        'total' => number_format($this->totalIncrement, 2, '.', ''),
                        'moneda_id' => $this->moneda_id,
                        'typepayment_id' => $this->typepayment_id,
                        'client_id' => $this->client_id,
                        'cajamovimiento_id' => $cajamovimiento->id ?? null,
                        'user_id' => Auth::user()->id,
                    ]);

                    $numeracion = Comprobante::where('typecomprobante_id', $this->typecomprobante_id)
                        ->count();

                    $comprobante = $venta->comprobante()->create([
                        'seriecompleta' => $serie->serie . '-' . $numeracion + 1,
                        'date' => now('America/Lima'),
                        'expire' => Carbon::now('America/Lima')->addDay(),
                        'direccion' => Direccion::findOrFail($this->direccion_id)->name ?? null,
                        'exonerado' => number_format($this->totalIncrement, 2, '.', ''),
                        'gravado' => number_format($this->gravado, 2, '.', ''),
                        'descuento' => number_format($this->descuentos, 2, '.', ''),
                        'otros' => number_format($this->otros, 2, '.', ''),
                        'igv' => number_format($this->igv, 2, '.', ''),
                        'subtotal' => number_format($this->totalIncrement, 2, '.', ''),
                        'total' => number_format($this->totalIncrement, 2, '.', ''),
                        'percent' => 18,
                        'referencia' => 'VT-',
                        'leyenda' => 'SON SOLES/100',
                        'client_id' => $this->client_id,
                        'tribute_id' => $this->tribute_id,
                        'typepayment_id' => $this->typepayment_id,
                        'typecomprobante_id' => $this->typecomprobante_id,
                        'moneda_id' => $this->moneda_id,
                        'empresa_id' => $this->empresa_id,
                        'user_id' => Auth::user()->id,
                    ]);

                    if (count($this->cuotas)) {
                        $amountCuotas = number_format(0, 2);
                        $totalCuotas = number_format($this->totalIncrement, 2, '.', '');
                        foreach ($this->cuotas as $cuota) {
                            $amountCuotas = number_format($amountCuotas + $cuota["amount"], 2, '.', '');
                            $venta->cuotas()->create([
                                'cuota' => $cuota["cuota"],
                                'amount' => $cuota["amount"],
                                'expiredate' => $cuota["date"],
                                'datepayment' => $cuota["date"],
                                'user_id' => Auth::user()->id,
                            ]);
                        }


                        if ($amountCuotas !== $totalCuotas) {
                            // dd($amountCuotas . " -" . $totalCuotas);
                            $this->addError('cuotas', "Monto total de cuotas($amountCuotas) no coincide con monto total de venta($totalCuotas)");
                            return false;
                        }
                    }

                    foreach ($carrito as $car) {

                        $priceunitincr = number_format(($car->price * $this->increment) / 100, $this->decimal, '.', '');
                        $pricesale = $car->price + $priceunitincr;

                        $tvitem = $venta->tvitems()->create([
                            'date' => now('America/Lima'),
                            'cantidad' => $car->cantidad,
                            'pricebuy' => $car->pricebuy,
                            'price' => $pricesale,
                            'igv' => $car->igv,
                            'subtotal' => $car->subtotal,
                            'total' => $pricesale * $car->cantidad,
                            'status' => 0,
                            'increment' => $this->increment,
                            'almacen_id' => $car->almacen_id,
                            'producto_id' => $car->producto_id,
                            'user_id' => Auth::user()->id,
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
                            'subtotal' => $pricesale * $car->cantidad,
                            'total' => $pricesale * $car->cantidad,
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
                $this->addError('carrito', 'Error al obtener serie del comprobante.');
            }
        } else {
            $this->addError('carrito', 'Carrito de compras vacío.');
        }
    }

    public function deleteitemcart(Carshoop $carshoop)
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
                'updated_at' => now('America/Lima'),
                'cantidad' => $stock + $carshoop->cantidad,
                'user_id' => Auth::user()->id
            ]);

            $carshoop->delete();
            DB::commit();
            $this->total = Carshoop::where('user_id', Auth::user()->id)->sum('total');
            if ($this->typepayment->paycuotas) {
                $this->calcular_cuotas();
            }
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function calcular_cuotas()
    {
        $this->reset(['cuotas']);
        $this->resetValidation(['increment', 'countcuotas']);

        if ((!empty(trim($this->countcuotas))) || $this->countcuotas > 0) {

            $totalAmount = number_format($this->total, 2, '.', '');

            if ((!empty(trim($this->increment))) || $this->increment > 0) {
                $incremento = ($totalAmount * $this->increment) / 100;
                $totalAmount = number_format($totalAmount + $incremento, 2, '.', '');
                $this->totalIncrement = number_format($totalAmount, 2, '.', '');
            }
            // $this->total = $totalAmount;

            $amountCuota = number_format($totalAmount / $this->countcuotas, 2, '.', '');
            $date = Carbon::now('America/Lima')->format('Y-m-d');

            $sumaCuotas = 0.00;
            for ($i = 1; $i <= $this->countcuotas; $i++) {
                $cuota = "00000" . $i;
                $sumaCuotas = number_format($sumaCuotas + $amountCuota, 2, '.', '');

                if ($i == $this->countcuotas) {
                    $result =  number_format($totalAmount - $sumaCuotas, 2, '.', '');
                    $amountCuota = number_format($amountCuota + ($result), 2, '.', '');
                }

                $this->cuotas[] = [
                    // 'cuota' => "Cuota" . substr($cuota, -3),
                    'cuota' => $i,
                    'date' => $date,
                    'amount' => $amountCuota,
                    'suma' => $sumaCuotas,
                ];
                $date = Carbon::parse($date)->addMonth()->format('Y-m-d');
            }
        } else {
            $this->addError('countcuotas', 'Ingrese cantidad válida de cuotas');
        }
    }

    public function updatingOpen()
    {
        $this->resetValidation();
        $this->reset([
            'document', 'nameclient', 'email',
            'nacimiento', 'sexo', 'pricetypeclient_id', 'channelsale_id',
            'direccion', 'ubigeo_id', 'telefono'
        ]);
    }

    public function savecliente()
    {

        $this->document = trim($this->document);
        $this->nameclient = trim($this->nameclient);
        $this->direccion = trim($this->direccion);
        $this->email = trim($this->email);
        $this->telefono = trim($this->telefono);

        $this->validate([
            'document' => [
                'required', 'numeric', 'min_digits:8',
                new ValidateDocument, 'unique:clients,document',
                new CampoUnique('clients', 'document', null, true)
            ],
            'nameclient' => [
                'required', 'string', 'min:3',
                new CampoUnique('clients', 'name', null, true)
            ],
            'sexo' => ['required', 'string', 'min:1', 'max:1'],
            'email' => ['nullable', 'email'],
            'nacimiento' => ['nullable', new ValidateNacimiento()],
            'direccion' => ['required', 'string', 'min:3'],
            'ubigeo_id' => ['nullable', 'integer', 'exists:ubigeos,id'],
            'pricetypeclient_id' => ['required', 'integer', 'exists:pricetypes,id'],
            'channelsale_id' => ['required', 'integer', 'exists:channelsales,id'],
            'telefono' => ['required', 'numeric', 'min_digits:7', 'digits_between:7,9'],
        ]);

        DB::beginTransaction();
        try {

            $client = Client::withTrashed()
                ->where('name', mb_strtoupper($this->nameclient, "UTF-8"))->first();

            if ($client) {
                $client->document = $this->document;
                $client->name = $this->nameclient;
                $client->email = $this->email;
                $client->nacimiento = $this->nacimiento;
                $client->sexo = $this->sexo;
                $client->pricetype_id = $this->pricetypeclient_id;
                $client->channelsale_id = $this->channelsale_id;
                $client->restore();
            } else {
                $client = Client::create([
                    'date' => now('America/Lima'),
                    'document' => $this->document,
                    'name' => $this->nameclient,
                    'email' => $this->email,
                    'nacimiento' => $this->nacimiento,
                    'sexo' => $this->sexo,
                    'pricetype_id' => $this->pricetypeclient_id,
                    'channelsale_id' => $this->channelsale_id,
                    // 'user_id' => Auth::user()->id,
                ]);
            }

            $direccion = $client->direccions()->create([
                'name' => $this->direccion,
                'ubigeo_id' => $this->ubigeo_id,
                'user_id' => Auth::user()->id,
            ]);

            $client->telephones()->create([
                'phone' => $this->telefono,
                'user_id' => Auth::user()->id,
            ]);

            DB::commit();
            $this->resetValidation();
            $this->reset([
                'open', 'document', 'nameclient', 'email',
                'nacimiento', 'sexo', 'pricetypeclient_id', 'channelsale_id',
                'direccion', 'ubigeo_id', 'telefono'
            ]);
            $this->clients = Client::orderBy('name', 'asc')->get();
            $this->client = $client;
            $this->client_id = $client->id;
            $this->pricetype_id = $client->pricetype_id;
            $this->direccion_id = $direccion->id;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function hydrate()
    {
        $this->clients = Client::orderBy('name', 'asc')->get();
        $this->dispatchBrowserEvent('render-ventas-select2');
    }
}
