<?php

namespace Modules\Ventas\Http\Livewire\Ventas;

use App\Helpers\GetClient;
use App\Helpers\GetPrice;
use App\Models\Cajamovimiento;
use App\Models\Carshoop;
use App\Models\Category;
use App\Models\Client;
use App\Models\Concept;
use App\Models\Empresa;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Opencaja;
use App\Models\Pricetype;
use App\Models\Serie;
use App\Models\Tribute;
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
use App\Models\Typepayment;
use Modules\Ventas\Entities\Venta;
use Illuminate\Support\Str;

use Livewire\WithPagination;

class CreateVenta extends Component
{

    use WithPagination;

    public $client;
    public $typepayment;
    public $methodpayment;
    public $empresa;
    public $pricetype;

    public $open = false;
    public $disponibles = 1;
    public $readyToLoad = false;

    public $cotizacion_id, $moneda_id;
    // public $clients = [];
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
    public $opencaja_id, $methodpayment_id, $detallepago, $concept_id, $cuenta_id;
    public $tribute_id, $empresa_id;

    public $document, $name, $direccion, $pricetypeasigned;


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

    // protected $listeners = ['updatepricetypeid'];

    protected function rules()
    {
        return [
            // 'empresa_id' => ['required', 'integer', 'exists:empresas,id'],

            'document' => ['required', 'numeric', 'min_digits:8'],
            'name' => ['required', 'string', 'min:3'],
            'direccion' => ['required', 'string', 'min:3'],

            'client_id' => ['required', 'integer', 'exists:clients,id'],
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
            'concept_id' => [
                'required', 'integer', 'exists:concepts,id',
                Rule::requiredIf($this->typepayment->paycuotas == 0),

            ],
            'opencaja_id' => [
                'nullable', 'integer', 'exists:opencajas,id',
                Rule::requiredIf($this->typepayment->paycuotas == 0),

            ],
            'methodpayment_id' => [
                'nullable', 'integer', 'exists:methodpayments,id',
                Rule::requiredIf($this->typepayment->paycuotas == 0),

            ],
            'cuenta_id' => [
                'nullable', 'integer', 'exists:cuentas,id',
                Rule::requiredIf($this->methodpayment->cuentas->count() > 1),

            ],
            'detallepago' => ['nullable'],
            'cotizacion_id' => ['nullable', 'exists:cotizacions,id'],
            'empresa_id' => ['required', 'integer', 'exists:empresas,id'],
            // 'carrito' => ['required', 'array', 'min:1'],
        ];
    }


    protected $messages = [
        'cart.*.almacen_id.required' => 'Almacen del producto requerido',
        'cart.*.price.required' => 'Precio del producto requerido',
        'cart.*.price.min' => 'Precio del producto deber se mayor a 0.0001',
        'cart.*.cantidad.required' => 'Cantidad del producto requerido',
        'cart.*.cantidad.min' => 'Cantidad del producto debe ser mayor a 0',
        'cart.*.serie.required' => 'Serie del producto requerido',
    ];

    public function mount()
    {
        $this->empresa = Empresa::DefaultEmpresa()->first() ?? new Empresa();
        $this->client = new Client();
        $this->typepayment = new Typepayment();
        $this->methodpayment = new Methodpayment();
        $pricetype = Pricetype::defaultPricetype()->first();
        $typepayment = Typepayment::defaultTypepayment()->first();

        $this->pricetype = $pricetype ?? new Pricetype();
        $this->pricetype_id = $pricetype->id ?? null;
        $this->typepayment = $typepayment ?? null;
        $this->typepayment_id = $typepayment->id ?? null;
        $this->moneda_id = Moneda::defaultMoneda()->first()->id ?? null;
        $this->methodpayment_id = Methodpayment::defaultMethodPayment()->first()->id ?? null;
        $this->typecomprobante_id = Typecomprobante::defaultTypecomprobante()->first()->id ?? null;
        $this->total = Carshoop::Micarrito()->sum('total');
        $this->totalIncrement = $this->total;
    }

    public function render()
    {
        $productos = Producto::select('id', 'name', 'pricebuy', 'priceusbuy', 'pricesale', 'category_id')->orderBy('name', 'asc');
        $categories = Category::orderBy('name', 'asc')->get();
        $pricetypes = Pricetype::orderBy('name', 'asc')->get();
        $almacens = Almacen::orderBy('name', 'asc')->get();
        $carrito = Carshoop::Micarrito()->with(['carshoopseries', 'producto', 'almacen'])->get();
        $typecomprobantes = Typecomprobante::orderBy('code', 'asc')->get();
        $typepayments = Typepayment::orderBy('name', 'asc')->get();
        // $opencajas = Opencaja::CajasAbiertas()->CajasUser()->orderBy('startdate', 'desc')->get();
        $monedas = Moneda::orderBy('currency', 'asc')->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();

        // if (trim($this->searchalmacen) !== "") {
        //     $productos->whereHas('almacens', function ($query) {
        //         $query->where('almacen_id', $this->searchalmacen);
        //     })->with(['almacens' => function ($query) {
        //         $query->where('almacen_id', $this->searchalmacen);
        //     }]);
        // }

        $relations = ['almacens', 'category', 'images', 'seriesdisponibles', 'ofertasdisponibles', 'existStock'];

        if (trim($this->searchcategory) !== "") {
            $productos->where('category_id', $this->searchcategory);
        }

        if ($this->disponibles) {
            array_push($relations, 'disponiblealmacens');
            $productos->whereHas('disponiblealmacens');
            // ->with($relations);
        } else {
            array_push($relations, 'almacens');
        }

        if (trim($this->search) !== "") {
            $productos->where('name', 'ilike',  $this->search . '%');
        }


        if ($this->readyToLoad) {
            $productos = $productos->with($relations)->paginate();
        } else {
            $productos = [];
        }

        return view('ventas::livewire.ventas.create-venta', compact('productos', 'pricetypes', 'categories', 'almacens', 'carrito', 'typecomprobantes', 'typepayments', 'methodpayments', 'monedas'));
    }


    public function loadProductos()
    {
        $this->readyToLoad = true;
    }


    public function loadprice(Pricetype $pricetype)
    {
        $this->pricetype = $pricetype;
        $this->pricetype_id = $pricetype->id;
    }

    public function loadbycategory($value)
    {
        $this->searchcategory = $value;
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

                            $carrito = Carshoop::Micarrito()->where('producto_id',  $serie->producto->id)
                                ->where('almacen_id',  $serie->almacen_id)->first();

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
                                'cantidad' => $stock - 1,
                            ]);

                            DB::commit();
                            $this->resetValidation();
                            $this->dispatchBrowserEvent('created');
                            $this->reset(['searchserie']);
                            $this->total = Carshoop::Micarrito()->sum('total');
                            // if ($this->typepayment->paycuotas) {
                            //     $this->calcular_cuotas();
                            // }
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

    public function updatedTypepaymentId($value)
    {

        $this->reset(['typepayment', 'increment', 'countcuotas', 'cuotas', 'methodpayment_id', 'accounts', 'cuenta_id']);
        $this->typepayment_id = !empty(trim($value)) ? trim($value) : null;
        $this->typepayment = Typepayment::findOrFail($value);
        // if ($this->typepayment->paycuotas) {
        //     $this->calcular_cuotas();
        // }


        // if ($this->client->pricetype_id) {
        //     $this->pricetype_id = $this->client->pricetype_id;
        // }
    }

    public function updatedIncrement($value)
    {

        $totalAmount = number_format($this->total, 2, '.', '');

        if ((!empty(trim($value))) || $value > 0) {
            $incremento = ($totalAmount * trim($value)) / 100;
            $totalAmount = number_format($totalAmount + $incremento, 2, '.', '');
            $this->totalIncrement = number_format($totalAmount, 2, '.', '');
        } else {
            $this->reset(['increment', 'totalIncrement']);
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

    public function addtocar($formData, Producto $producto)
    {

        $this->resetValidation(["errorstocart.*"]);
        // $price = trim($formData["price"]);
        $price = isset($formData["price"]) ? trim($formData["price"]) : "";
        $almacen_id = isset($formData["almacen_$producto->id[]"]) ? trim($formData["almacen_$producto->id[]"]) : null;
        $serie = isset($formData["serie"]) ? trim($formData["serie"]) : "";
        $cantidad = isset($formData["cantidad"]) ? trim($formData["cantidad"]) : 1;

        $this->cart[$producto->id]["price"] = $price;
        $this->cart[$producto->id]["almacen_id"] = $almacen_id;
        $this->cart[$producto->id]["serie"] = $serie;
        $this->cart[$producto->id]["cantidad"] = $cantidad;

        $validateDate = $this->validate([
            "cart.$producto->id.price" => ['required', 'numeric', 'decimal:0,4', 'min:0.0001',],
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

                    $carrito = Carshoop::Micarrito()->where('producto_id',  $producto->id)
                        ->where('almacen_id',  $almacen_id)->first();

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
                        'cantidad' => $stock - $cantidad,
                    ]);

                    DB::commit();
                    $this->resetValidation();
                    $this->dispatchBrowserEvent('reset-form', $producto->id);
                    $this->dispatchBrowserEvent('created');
                    $this->total = Carshoop::Micarrito()->sum('total');
                    // if ($this->typepayment->paycuotas) {
                    //     $this->calcular_cuotas();
                    // }
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

        $this->direccion = trim($this->direccion);
        $this->concept_id = Concept::defaultConceptVentas()->first()->id ?? null;
        $this->tribute_id = Tribute::defaultTribute()->first()->id ?? null;
        $this->empresa_id = Empresa::defaultEmpresa()->first()->id ?? null;
        $this->opencaja_id =  Opencaja::CajasAbiertas()->CajasUser()->first()->id ?? null;

        $carrito = Carshoop::Micarrito()->get();
        $serie = Typecomprobante::find($this->typecomprobante_id);
        $totalAmount = number_format($this->total, 2, '.', '');
        $incremento = ($totalAmount * $this->increment) / 100;
        $this->totalIncrement = number_format($totalAmount + $incremento, 2, '.', '');

        if (count($carrito)) {

            $this->validate([
                'document' => [
                    'required', 'numeric', 'min_digits:8',
                ],
                'name' => [
                    'required', 'string', 'min:3',
                ],
                'direccion' => ['required', 'string', 'min:3']
            ]);

            $client = Client::where('document', $this->document)->first();

            if ($client) {
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

            if ($serie) {

                $validateData = $this->validate();
                DB::beginTransaction();

                try {

                    $cajamovimiento = null;
                    $numeracion = Comprobante::where('typecomprobante_id', $this->typecomprobante_id)
                        ->count();

                    // if ($this->typepayment) {
                    if (!$this->typepayment->paycuotas) {
                        $cajamovimiento = Cajamovimiento::create([
                            'date' => now('America/Lima'),
                            'amount' => number_format($this->total, 2, '.', ''),
                            'referencia' => $serie->serie . '-' . $numeracion + 1,
                            'detalle' => trim($this->detallepago),
                            'moneda_id' => $this->moneda_id,
                            'methodpayment_id' => $this->methodpayment_id,
                            'typemovement' => '+',
                            'cuenta_id' => $this->cuenta_id,
                            'concept_id' => $this->concept_id,
                            'opencaja_id' => $this->opencaja_id,
                            'user_id' => Auth::user()->id,
                        ]);
                    }
                    // }

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

                    $comprobante = $venta->comprobante()->create([
                        'seriecompleta' => $serie->serie . '-' . $numeracion + 1,
                        'date' => now('America/Lima'),
                        'expire' => Carbon::now('America/Lima')->addDay(),
                        'direccion' => $this->direccion,
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

                    if ($this->typepayment->paycuotas) {
                        if ((!empty(trim($this->countcuotas))) || $this->countcuotas > 0) {

                            $totalAmount = number_format($this->total, 2, '.', '');

                            if ((!empty(trim($this->increment))) || $this->increment > 0) {
                                $incremento = ($totalAmount * $this->increment) / 100;
                                $totalAmount = number_format($totalAmount + $incremento, 2, '.', '');
                                // $this->totalIncrement = number_format($totalAmount, 2, '.', '');
                            }

                            $amountCuota = number_format($totalAmount / $this->countcuotas, 2, '.', '');
                            $date = Carbon::now('America/Lima')->format('Y-m-d');

                            $sumaCuotas = 0.00;
                            for ($i = 1; $i <= $this->countcuotas; $i++) {
                                $sumaCuotas = number_format($sumaCuotas + $amountCuota, 2, '.', '');
                                if ($i == $this->countcuotas) {
                                    $result =  number_format($totalAmount - $sumaCuotas, 2, '.', '');
                                    $amountCuota = number_format($amountCuota + ($result), 2, '.', '');
                                }

                                // $this->cuotas[] = [
                                //     'cuota' => $i,
                                //     'date' => $date,
                                //     'amount' => $amountCuota,
                                //     'suma' => $sumaCuotas,
                                // ];

                                $venta->cuotas()->create([
                                    'cuota' => $i,
                                    'amount' => $amountCuota,
                                    'expiredate' => $date,
                                    'user_id' => Auth::user()->id,
                                ]);
                                $date = Carbon::parse($date)->addMonth()->format('Y-m-d');
                            }



                            // $amountCuotas = number_format(0, 2);
                            // $totalCuotas = number_format($this->totalIncrement, 2, '.', '');
                            // foreach ($this->cuotas as $cuota) {
                            //     $amountCuotas = number_format($amountCuotas + $cuota["amount"], 2, '.', '');
                            //     $venta->cuotas()->create([
                            //         'cuota' => $cuota["cuota"],
                            //         'amount' => $cuota["amount"],
                            //         'expiredate' => $cuota["date"],
                            //         'datepayment' => $cuota["date"],
                            //         'user_id' => Auth::user()->id,
                            //     ]);
                            // }

                            // if ($totalAmount !== $sumaCuotas) {
                            //     $this->addError('cuotas', "Monto total de cuotas($sumaCuotas) no coincide con monto total de venta($totalAmount)");
                            //     return false;
                            // }
                        } else {
                            $this->addError('countcuotas', 'Ingrese cantidad válida de cuotas');
                        }
                    }

                    foreach ($carrito as $car) {

                        $priceunitincr = number_format(($car->price * $this->increment) / 100, 2, '.', '');
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
                $this->addError('typecomprobante_id', 'Error al obtener serie del comprobante.');
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
                'cantidad' => $stock + $carshoop->cantidad,
            ]);

            $carshoop->delete();
            DB::commit();
            $this->total = Carshoop::Micarrito()->sum('total');
            // if ($this->typepayment->paycuotas) {
            //     $this->calcular_cuotas();
            // }
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // public function calcular_cuotas()
    // {
    //     $this->reset(['cuotas']);
    //     $this->resetValidation(['increment', 'countcuotas']);

    //     if ((!empty(trim($this->countcuotas))) || $this->countcuotas > 0) {

    //         $totalAmount = number_format($this->total, 2, '.', '');

    //         if ((!empty(trim($this->increment))) || $this->increment > 0) {
    //             $incremento = ($totalAmount * $this->increment) / 100;
    //             $totalAmount = number_format($totalAmount + $incremento, 2, '.', '');
    //             $this->totalIncrement = number_format($totalAmount, 2, '.', '');
    //         }
    //         // $this->total = $totalAmount;

    //         $amountCuota = number_format($totalAmount / $this->countcuotas, 2, '.', '');
    //         $date = Carbon::now('America/Lima')->format('Y-m-d');

    //         $sumaCuotas = 0.00;
    //         for ($i = 1; $i <= $this->countcuotas; $i++) {
    //             $cuota = "00000" . $i;
    //             $sumaCuotas = number_format($sumaCuotas + $amountCuota, 2, '.', '');

    //             if ($i == $this->countcuotas) {
    //                 $result =  number_format($totalAmount - $sumaCuotas, 2, '.', '');
    //                 $amountCuota = number_format($amountCuota + ($result), 2, '.', '');
    //             }

    //             $this->cuotas[] = [
    //                 // 'cuota' => "Cuota" . substr($cuota, -3),
    //                 'cuota' => $i,
    //                 'date' => $date,
    //                 'amount' => $amountCuota,
    //                 'suma' => $sumaCuotas,
    //             ];
    //             $date = Carbon::parse($date)->addMonth()->format('Y-m-d');
    //         }
    //     } else {
    //         $this->addError('countcuotas', 'Ingrese cantidad válida de cuotas');
    //     }
    // }

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
                    $this->pricetype_id = $response->getData()->pricetype_id;
                    $this->pricetypeasigned = $response->getData()->pricetypeasigned;

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
        $this->dispatchBrowserEvent('render-ventas-select2');
    }
}
