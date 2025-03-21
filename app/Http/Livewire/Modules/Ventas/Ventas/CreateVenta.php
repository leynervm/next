<?php

namespace App\Http\Livewire\Modules\Ventas\Ventas;

use App\Enums\MovimientosEnum;
use App\Enums\PromocionesEnum;
use App\Models\Moneda;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Almacen;
use App\Models\Carshoopitem;
use App\Models\Client;
use App\Models\Concept;
use App\Models\Guia;
use App\Models\Itemserie;
use App\Models\Kardex;
use App\Models\Methodpayment;
use App\Models\Modalidadtransporte;
use App\Models\Monthbox;
use App\Models\Motivotraslado;
use App\Models\Openbox;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Serie;
use App\Models\Seriecomprobante;
use App\Models\Tvitem;
use App\Models\Typepayment;
use App\Rules\ValidateCarrito;
use App\Rules\ValidateDocument;
use App\Rules\ValidateStock;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Modules\Ventas\Entities\Venta;
use Nwidart\Modules\Facades\Module;

class CreateVenta extends Component
{

    use WithPagination;

    public $empresa, $sucursal, $pricetype, $moneda, $producto;
    public $open, $istransferencia = false, $opencombos = false;
    public $disponibles = 1;
    public $producto_id, $serie_id, $almacen_id, $pricetype_id;

    public $search = '';
    public $searchserie = '';
    public $searchalmacen = '';
    public $searchcategory = '';
    public $searchsubcategory = '';
    public $searchmarca = '';
    public $cart = [];
    public $seriescarrito = [];
    public $subcategories = [];
    public $seriespromocion = [];

    public $tvitem, $almacens = [], $almacenitem = [];

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'producto'],
        'searchcategory' => ['except' => '', 'as' => 'categoria'],
        'searchsubcategory' => ['except' => '', 'as' => 'subcategoria'],
        'searchmarca' => ['except' => '', 'as' => 'marca'],
    ];

    public $typepayment, $modalidadtransporte, $motivotraslado;
    public $cotizacion_id, $client_id, $direccion_id, $mensaje;
    public $moneda_id, $monthbox, $openbox, $methodpayment_id, $typepayment_id, $detallepago, $concept;
    public $typecomprobante, $typecomprobante_id, $seriecomprobante_id, $observaciones;
    public $document = '', $name, $direccion, $pricetypeasigned;

    public $serieguia_id, $ructransport, $nametransport,
        $documentdriver, $namedriver, $lastname, $licencia, $placa, $placavehiculo,
        $documentdestinatario, $namedestinatario, $peso, $packages, $datetraslado,
        $note, $ubigeoorigen_id, $direccionorigen, $anexoorigen, $ubigeodestino_id,
        $direcciondestino, $anexodestino, $motivotraslado_id, $modalidadtransporte_id;

    public $vehiculosml = false;
    public $incluyeguia = false;
    public $incluyeigv = 0;
    public $increment = 0;
    public $countcuotas = 1;
    public $exonerado = 0;
    public $gravado = 0;
    public $gratuito = 0;
    public $inafecto = 0;
    public $igv = 0;
    public $igvgratuito = 0;
    public $descuentos = 0;
    public $otros = 0;
    public $subtotal = 0;
    public $total = 0;
    public $amountincrement = 0;
    public $paymentactual = 0;

    public $cuotas = [];
    public $items = [];
    public $parcialpayments = [];
    public $typepay = '0';
    public $amountparcial;

    public $arrayequalremite = ['02', '04', '07'];
    public $arraydistintremite = ['01', '03', '05', '06', '09', '14', '17'];

    public $searchgre, $guiaremision;
    public $sincronizegre = false;

    protected function rules()
    {
        return [
            // 'empresa_id' => ['required', 'integer', 'exists:empresas,id'],
            'document' => [
                'required',
                'numeric',
                $this->typecomprobante->code == "01" ? 'digits:11' : new ValidateDocument,
                'regex:/^\d{8}(?:\d{3})?$/',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.document' : ''),
            ],
            'name' => ['required', 'string', 'min:6'],
            'direccion' => ['nullable', 'string', 'min:3'],
            'client_id' => ['required', 'integer', 'min:1', 'exists:clients,id'],
            'typecomprobante.id' => ['required', 'integer', 'min:1', 'exists:typecomprobantes,id'],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'typepayment.id' => ['required', 'integer', 'min:1', 'exists:typepayments,id'],
            'typepayment_id' => ['required', 'integer', 'min:1', 'exists:typepayments,id'],
            'paymentactual' => ['nullable', Rule::requiredIf($this->typepayment->isCredito()), 'numeric', 'min:0', $this->typepayment->isCredito() ? 'lt:' . $this->total : '', 'decimal:0,2'],
            'increment' => ['nullable', Rule::requiredIf($this->typepayment->isCredito()), 'numeric', 'min:0', 'decimal:0,2'],
            'countcuotas' => ['nullable', Rule::requiredIf($this->typepayment->isCredito()), 'integer', 'min:1'],
            'concept.id' => ['nullable', Rule::requiredIf($this->typepayment->isContado()), 'integer', 'min:1', 'exists:concepts,id'],
            'openbox.id' => ['nullable', Rule::requiredIf($this->typepayment->isContado()), 'integer', 'min:1', 'exists:openboxes,id'],
            'monthbox.id' => ['nullable', Rule::requiredIf($this->typepayment->isContado()), 'integer', 'min:1', 'exists:monthboxes,id',],
            'typepay' => ['required', 'integer', 'min:0', 'max:1',],
            'parcialpayments' => [
                'nullable',
                Rule::requiredIf($this->typepayment->isContado() && $this->typepay == '1'),
                'array'
            ],
            'methodpayment_id' => [
                'nullable',
                Rule::requiredIf($this->typepayment->isContado() && $this->typepay == '0'),
                'integer',
                'min:1',
                'exists:methodpayments,id',
            ],
            'detallepago' => ['nullable', Rule::requiredIf($this->istransferencia && $this->typepay == '0'), 'string', 'min:4'],
            'cotizacion_id' => ['nullable', 'integer', 'min:1', 'exists:cotizacions,id'],
            'sucursal.id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'seriecomprobante_id' => ['required', 'integer', 'min:1', 'exists:seriecomprobantes,id'],
            'items' => [new ValidateCarrito($this->moneda->id, $this->sucursal->id)],
            'ructransport' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
                'numeric',
                'digits:11',
                'regex:/^\d{11}$/',
                $this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.document' : '',
            ],
            'nametransport' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
                'string',
                'min:6',
                $this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.name' : '',
            ],
            'documentdestinatario' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia),
                'numeric',
                'regex:/^\d{8}(?:\d{3})?$/',
                $this->incluyeguia && $this->motivotraslado->code == '03' ? 'different:document' : '',
                // 'different:document'
            ],
            'namedestinatario' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia),
                'string',
                'min:6',
                $this->incluyeguia && $this->motivotraslado->code == '03' ? 'different:document' : '',
            ],
            'documentdriver' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'numeric',
                'regex:/^\d{8}(?:\d{3})?$/',
                // 'different:document'
            ],
            'namedriver' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string',
                'min:6'
            ],
            'lastname' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string',
                'min:6'
            ],
            'licencia' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string',
                'min:9',
                'max:10'
            ],
            'placa' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string',
                'min:6',
                'max:8'
            ],
            'peso' => ['nullable', Rule::requiredIf($this->incluyeguia), 'numeric', 'gt:0', 'decimal:0,4'],
            'packages' => ['nullable', Rule::requiredIf($this->incluyeguia), 'integer', 'min:1'],
            'datetraslado' => ['nullable', Rule::requiredIf($this->incluyeguia), 'date', 'after_or_equal:today'],
            'placavehiculo' => ['nullable', 'string', 'min:6', 'max:8'],
            'note' => ['nullable', 'string', 'min:10'],
            'ubigeoorigen_id' => ['nullable', Rule::requiredIf($this->incluyeguia), 'integer', 'min:1', 'exists:ubigeos,id'],
            'direccionorigen' => ['nullable', Rule::requiredIf($this->incluyeguia), 'string', 'min:6'],
            'ubigeodestino_id' => ['nullable', Rule::requiredIf($this->incluyeguia), 'integer', 'min:1', 'exists:ubigeos,id'],
            'direcciondestino' => ['nullable', Rule::requiredIf($this->incluyeguia), 'string', 'min:6'],
            'motivotraslado_id' => ['nullable', Rule::requiredIf($this->incluyeguia), 'integer', 'min:1', 'exists:motivotraslados,id'],
            'modalidadtransporte_id' => ['nullable', Rule::requiredIf($this->incluyeguia), 'integer', 'min:1', 'exists:modalidadtransportes,id'],
            'serieguia_id' => ['nullable', Rule::requiredIf($this->incluyeguia), 'integer', 'min:1', 'exists:seriecomprobantes,id'],
        ];
    }

    protected function messages()
    {
        return [
            'parcialpayments.required' => 'Agregar pagos parciales',
            'parcialpayments.required_if' => 'No se encontraron registros de pagos.',
            'cart.*.cantidad.min' => 'La cantidad mínima es 1.'
        ];
    }

    protected $validationAttributes = [
        'items' => 'carrito de ventas',
        'document' => 'dni / ruc',
        'searchgre' =>  'serie guía remisión',
        'items' => 'productos',
        'cart.*.serie_id' =>  'serie'
    ];


    public function mount(Seriecomprobante $seriecomprobante, Moneda $moneda, Concept $concept, $pricetype, $empresa)
    {
        $this->producto = new Producto();
        $this->tvitem = new Tvitem();
        $this->sucursal = auth()->user()->sucursal;
        $this->sucursal->load(['almacens' => function ($query) {
            $query->orderBy('default', 'desc')->orderBy('id', 'asc');
        }]);
        $this->empresa = $empresa;
        $this->moneda = $moneda;
        $this->moneda_id = $moneda->id ?? null;
        $this->pricetype = $pricetype;
        $this->pricetype_id = $pricetype->id ?? null;

        if (count($this->sucursal->almacens) > 0) {
            $this->almacen_id = $this->sucursal->almacens->first()->id;
        }

        $this->motivotraslado = new Motivotraslado();
        $this->modalidadtransporte = new Modalidadtransporte();
        $this->typepayment = new Typepayment();
        $this->methodpayment_id = Methodpayment::default()->first()->id ?? null;
        $this->monthbox = Monthbox::usando($this->sucursal->id)->first();
        $this->openbox = Openbox::mybox($this->sucursal->id)->first();
        $this->typepayment_id = Typepayment::default()->first()->id ?? null;
        $this->seriecomprobante_id = $seriecomprobante->id;
        $this->typecomprobante = $seriecomprobante->typecomprobante;
        $this->concept = $concept;
        $this->setTotal();
        $this->ubigeoorigen_id = $this->sucursal->ubigeo_id;
        $this->direccionorigen = $this->sucursal->direccion;
    }

    public function render()
    {

        $productos = Producto::query()->select(
            'productos.id',
            'productos.name',
            'productos.slug',
            'marca_id',
            'category_id',
            'subcategory_id',
            'unit_id',
            'requireserie',
            'novedad',
            'sku',
            'pricebuy',
            'pricesale',
            'precio_1',
            'precio_2',
            'precio_3',
            'precio_4',
            'precio_5',
            'marcas.name as name_marca',
            'categories.name as name_category',
            'subcategories.name as name_subcategory',
            DB::raw(
                "ts_rank(to_tsvector('spanish', 
                    COALESCE(productos.name, '') || ' ' || 
                    COALESCE(marcas.name, '') || ' ' || 
                    COALESCE(categories.name, '')
                ), plainto_tsquery('spanish', '" . $this->search . "')) AS rank"
            )
        )->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->leftJoin('subcategories', 'productos.subcategory_id', '=', 'subcategories.id')
            ->leftJoin('categories', 'productos.category_id', '=', 'categories.id')
            ->with(['unit', 'imagen', 'series' => function ($query) {
                $query->disponibles()->with('almacen');
            }, 'promocions' => function ($query) {
                $query->with(['itempromos.producto' => function ($subQuery) {
                    $subQuery->with(['unit', 'imagen', 'almacens']);
                }])->availables()->disponibles();
            }])->withWhereHas('almacens', function ($query) {
                $query->whereIn('almacens.id', $this->sucursal->almacens->pluck('id'));
                if ($this->disponibles) {
                    $query->where('cantidad', '>', 0);
                }
            })->withCount(['almacens as stock' => function ($query) {
                $query->whereIn('almacen_id', $this->sucursal->almacens->pluck('id'))
                    ->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)'));
            }]);

        if (trim($this->search) !== '') {
            $productos->whereRaw(
                "to_tsvector('spanish', 
                COALESCE(productos.name, '') || ' ' || 
                COALESCE(marcas.name, '') || ' ' || 
                COALESCE(categories.name, '')) @@ plainto_tsquery('spanish', '" . $this->search . "')",
            )->orWhereRaw(
                "similarity(productos.name, '" . $this->search . "') > 0.5 
                OR similarity(marcas.name, '" . $this->search . "') > 0.5 
                OR similarity(categories.name, '" . $this->search . "') > 0.5",
            )->orderByDesc('novedad')->orderBy('subcategories.orden')
                ->orderBy('categories.orden')->orderByDesc('rank')
                ->orderByDesc(DB::raw("similarity(productos.name, '" . $this->search . "')"));
        }

        if (trim($this->searchmarca) !== "") {
            $productos->where('marcas.slug', $this->searchmarca);
        }

        if (trim($this->searchcategory) !== "") {
            $productos->where('categories.slug', $this->searchcategory);
        }

        if (trim($this->searchsubcategory) !== "") {
            $productos->where('subcategories.slug', $this->searchsubcategory);
        }

        $productos = $productos->visibles()->orderBy('novedad', 'desc')->orderBy('subcategories.orden', 'ASC')
            ->orderBy('categories.orden', 'ASC')->paginate(20)
            ->through(function ($producto) {
                $producto->descuento = $producto->promocions->whereIn('type', [PromocionesEnum::DESCUENTO->value, PromocionesEnum::OFERTA->value])->first()->descuento ?? 0;
                $producto->liquidacion = $producto->promocions->where('type', PromocionesEnum::LIQUIDACION->value)->count() > 0 ? true : false;
                return $producto;
            });

        if (Module::isEnabled('Facturacion')) {
            $modalidadtransportes = Modalidadtransporte::orderBy('code', 'asc')->get();
            $motivotraslados = Motivotraslado::whereIn('code', ['01', '03'])->orderBy('code', 'asc')->get();
        } else {
            $modalidadtransportes = [];
            $motivotraslados = [];
        }

        // $typecomprobantes = auth()->user()->sucursal->seriecomprobantes()
        //     ->withWhereHas('typecomprobante', function ($query) {
        //         $query->whereNotIn('code', ['07', '09']);
        //         if (Module::isDisabled('Facturacion')) {
        //             $query->default();
        //         }
        //     });
        $comprobantesguia = auth()->user()->sucursal->seriecomprobantes()
            ->withWhereHas('typecomprobante', function ($query) {
                $query->whereIn('code', ['09', '13']);
                if (Module::isDisabled('Facturacion')) {
                    $query->default();
                }
            });

        // $typecomprobantes = $typecomprobantes->orderBy('code', 'asc')->get();
        $comprobantesguia = $comprobantesguia->orderBy('code', 'asc')->get();

        $tvitems = Tvitem::with(['moneda', 'promocion', 'kardexes.almacen', 'producto' => function ($query) {
            $query->with(['unit', 'imagen', 'almacens', 'seriesdisponibles']);
        }, 'itemseries' => function ($query) {
            $query->with(['serie.almacen']);
        }, 'carshoopitems' => function ($subq) {
            $subq->with(['kardexes.almacen', 'itempromo', 'producto' => function ($q) {
                $q->with(['unit', 'imagen', 'almacens', 'seriesdisponibles']);
            }, 'itemseries' => function ($subq) {
                $subq->with(['serie.almacen']);
            }]);
        }])->ventas()->micart()->orderBy('id', 'asc')->paginate(15, ['*'], 'carshoopPage');

        if ($this->empresa->usepricedolar) {
            $monedas = Moneda::orderBy('currency', 'asc')->get();
        } else {
            $monedas = Moneda::where('code', 'PEN')->orderBy('currency', 'asc')->get();
        }

        return view('livewire.modules.ventas.ventas.create-venta', compact('productos', 'tvitems', 'comprobantesguia', 'monedas', 'modalidadtransportes', 'motivotraslados'));
    }

    public function updatedSearch()
    {
        $this->resetValidation();
        $this->resetPage();
    }

    public function updatedSearchmarca()
    {
        $this->resetValidation();
        $this->resetPage();
    }

    public function updatedSearchcategory()
    {
        $this->resetValidation();
        $this->resetPage();
    }

    public function updatedSearchsubcategory()
    {
        $this->resetValidation();
        $this->resetPage();
    }

    public function updatedPricetypeId($value)
    {
        $this->resetValidation();
        $this->pricetype = Pricetype::find($value);
        $this->updatecartpricelist($this->pricetype);
    }

    public function limpiarcliente()
    {
        $this->reset(['client_id', 'document', 'name', 'direccion']);
    }

    public function savepay()
    {
        $this->validate([
            'amountparcial' => ['required', 'numeric', 'min:0', 'gt:0', 'decimal:0,4'],
            'methodpayment_id' => ['required', 'integer', 'min:1', 'exists:methodpayments,id'],
            'detallepago' => ['nullable', Rule::requiredIf($this->istransferencia), 'string', 'min:4'],
        ]);

        $parcialamount = 0;
        if (count($this->parcialpayments)) {
            $collect = collect($this->parcialpayments);
            $parcialamount = $collect->sum('amount');
        }

        if (($parcialamount + $this->amountparcial) > number_format($this->total - $this->amountincrement, 2, '.', '')) {
            $mensaje =  response()->json([
                'title' => 'PAGO PARCIAL SUPERA MONTO TOTAL DE VENTA !',
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        $methodpayments = array_column($this->parcialpayments, 'methodpayment_id');

        if (in_array($this->methodpayment_id, $methodpayments)) {
            $index = array_search($this->methodpayment_id, $methodpayments);
            $this->parcialpayments[$index]['amount'] += $this->amountparcial;
        } else {
            $this->parcialpayments[] = [
                'id' => Str::uuid()->toString(),
                'amount' => number_format($this->amountparcial, 3, '.', ''),
                'detalle' => $this->istransferencia ? $this->detallepago : null,
                'methodpayment_id' => $this->methodpayment_id,
                'method' => Methodpayment::find($this->methodpayment_id)->name,
            ];
        }

        $this->resetValidation();
        $this->reset(['amountparcial', 'methodpayment_id', 'detallepago', 'istransferencia']);
        $this->setTotal();
    }

    public function removepay($index)
    {
        unset($this->parcialpayments[$index]);
        $this->parcialpayments = array_values($this->parcialpayments);
        $this->setTotal();
    }

    public function setTotal()
    {

        $this->resetValidation();
        $results = Tvitem::select(
            DB::raw("COALESCE(SUM(subtotal),0) as subtotal"),
            DB::raw("COALESCE(SUM(total),0) as total"),
            DB::raw("COALESCE(SUM(CASE WHEN igv > 0 AND gratuito = '0' THEN igv * cantidad ELSE 0 END),0) as igv"),
            DB::raw("COALESCE(SUM(CASE WHEN igv > 0 AND gratuito = '1' THEN igv * cantidad ELSE 0 END), 0) as igvgratuito"),
            DB::raw("COALESCE(SUM(CASE WHEN igv > 0 AND gratuito = '0' THEN price * cantidad ELSE 0 END), 0) as gravado"),
            DB::raw("COALESCE(SUM(CASE WHEN igv = 0 AND gratuito = '0' THEN price * cantidad ELSE 0 END), 0) as exonerado"),
            DB::raw("COALESCE(SUM(CASE WHEN gratuito = '1' THEN price * cantidad ELSE 0 END), 0) as gratuitos")
        )->ventas()->micart()->first();
        // dd($results[0]);

        if ($this->typepay == '1') {
            $collect = collect($this->parcialpayments);
            $this->paymentactual = $collect->sum('amount') ?? 0;
        } else {
            $this->paymentactual = 0;
        }

        // SE LE INCREMENTA AL MONTO PENDIENTE PAGO, NO AL TOTAL
        $saldopagar = number_format($results->total - $this->paymentactual, 3, '.', '');
        // TOTAL = ESTÁ INCLUY. IGV+ GRAB+EXO+DESC+GRATUI+INCREM.
        $this->amountincrement = number_format($saldopagar * $this->increment / 100, 3, '.', '');

        $this->igv = number_format($results->igv, 3, '.', '');
        $this->igvgratuito = number_format($results->igvgratuito, 3, '.', '');
        $this->gratuito =  number_format($results->gratuitos, 3, '.', '');
        $this->exonerado = number_format($results->exonerado, 3, '.', '');
        $this->gravado = number_format($results->gravado, 3, '.', '');
        $this->subtotal = number_format($results->subtotal, 3, '.', '');


        if ($this->empresa->isAfectacionIGV()) {
            $priceIGV = getPriceIGV($this->amountincrement, $this->empresa->igv);
            $this->gravado += $priceIGV->price;
            $this->igv +=  $priceIGV->igv;
        } else {
            $this->exonerado += $this->amountincrement;
        }

        $this->total = number_format($results->total + $this->amountincrement, 3, '.', '');
    }

    public function updatedMonedaId($value)
    {
        if ($value) {
            $this->moneda = Moneda::find($value);
        }
    }

    public function updatedTypepaymentId($value)
    {
        $this->reset([
            'increment',
            'paymentactual',
            'amountincrement',
            'gravado',
            'exonerado',
            'igv',
            'countcuotas',
            'cuotas',
            'typepay',
            'parcialpayments',
            'amountparcial'
        ]);
        if ($value) {
            $this->setTotal();
        }
    }

    public function updatedIncrement($value)
    {
        $this->increment = !empty($value) ? number_format($value, 2, '.', '') : '0.00';
        $this->setTotal();
    }

    public function updategratis(Tvitem $tvitem)
    {
        if ($tvitem->isGratuito()) {
            $total = ($tvitem->price + $tvitem->igv) * $tvitem->cantidad;
            $tvitem->total = $total;
            $tvitem->gratuito = Tvitem::NO_GRATUITO;
        } else {
            $tvitem->total = 0;
            $tvitem->gratuito = Tvitem::GRATUITO;
        }
        $tvitem->save();
        $this->setTotal();
    }

    public function save()
    {
        $this->resetValidation();
        if (!$this->monthbox || !$this->monthbox->isUsing()) {
            $this->dispatchBrowserEvent('validation', getMessageMonthbox());
            return false;
        }

        if (!$this->openbox || !$this->openbox->isActivo()) {
            $this->dispatchBrowserEvent('validation', getMessageOpencaja());
            return false;
        }

        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->direccion = trim($this->direccion);
        $this->ructransport = trim($this->ructransport);
        $this->nametransport = trim($this->nametransport);
        $this->direcciondestino = trim($this->direcciondestino);
        $this->direccionorigen = trim($this->direccionorigen);
        $this->note = trim($this->note);
        $this->documentdriver = trim($this->documentdriver);
        $this->name = trim($this->name);
        $this->lastname = trim($this->lastname);
        $this->licencia = trim($this->licencia);
        $this->placa = trim($this->placa);
        $this->observaciones = !empty(trim($this->observaciones)) ? trim($this->observaciones) : null;

        if ($this->typepayment_id) {
            $this->typepayment = Typepayment::find($this->typepayment_id);
        }

        if ($this->modalidadtransporte_id) {
            $this->modalidadtransporte = Modalidadtransporte::find($this->modalidadtransporte_id);
        }

        if ($this->motivotraslado_id) {
            $this->motivotraslado = Motivotraslado::find($this->motivotraslado_id);
        }

        if ($this->motivotraslado->code == '01') {
            $this->documentdestinatario = $this->document;
            $this->namedestinatario = $this->name;
        }

        if ($this->vehiculosml) {
            $this->reset(['documentdriver', 'namedriver', 'lastname', 'licencia', 'placa', 'ructransport', 'nametransport']);
        }

        $carshoops = Tvitem::with(['moneda', 'promocion', 'kardexes.almacen', 'carshoopitems.producto', 'producto', 'itemseries.serie'])
            ->ventas()->micart()->orderBy('id', 'asc')->get();

        if ($this->seriecomprobante_id) {
            $seriecomprobante = Seriecomprobante::with('typecomprobante')->find($this->seriecomprobante_id);
            $this->typecomprobante = $seriecomprobante->typecomprobante;

            if ($this->typepayment->paycuotas && $this->typecomprobante->code <> '01') {
                $this->addError('typepayment_id', 'Tipo de pago no permitido para el comprobante seleccionado.');
                $mensaje =  response()->json([
                    'title' => 'TIPO DE PAGO NO PERMITIDO PARA EL COMPROBANTE SELECCIONADO !',
                    'text' => "Comprobante seleccionado no permite generar ventas con tipo de pago a CREDITO."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            if ($this->guiaremision && $seriecomprobante->typecomprobante->isSunat()) {
                if (!$this->guiaremision->seriecomprobante->typecomprobante->isSunat()) {
                    $mensaje =  response()->json([
                        'title' => 'GRE LOCAL NO SE PUEDE VINCULAR A COMPROBANTE VENTA EMITIBLE A SUNAT !',
                        'text' => "No se puede vincular una Guía de remisión local a un comprobante de pago emitible a SUNAT."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }
            }

            $this->setTotal();
            $numeracion = $this->empresa->isProduccion() ? $seriecomprobante->contador + 1 : $seriecomprobante->contadorprueba + 1;
        }

        $this->validate();

        if ($this->typepay == '1') {
            $collect = collect($this->parcialpayments);
            $parcialamount = $collect->sum('amount') ?? 0;

            if ($this->typepayment->isContado() && number_format($parcialamount, 2, '.', '') <> number_format($this->total, 2, '.', '')) {
                $mensaje =  response()->json([
                    'title' => "MONTO PARCIAL " . $this->moneda->simbolo . " " . number_format($parcialamount, 2, '.', ', ') . " DIFERENTE AL TOTAL PAGAR DE VENTA " . $this->moneda->simbolo . " " . number_format($this->total, 2, '.', ', ') . " !",
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        }

        if ($this->typepayment->isCredito() && $this->paymentactual >= $this->total) {
            $mensaje =  response()->json([
                'title' => 'SE RECOMIENDA USAR TIPO DE PAGO "CONTADO" EN EL PRESENTE COMPROBANTE',
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        DB::beginTransaction();
        try {

            $client = Client::find($this->client_id);
            if ($this->direccion != '') {
                $client->direccions()->updateOrCreate(['name' => $this->direccion]);
            }
            // $totalcomboGRA = 0;
            // $totalIGVcomboGRA = 0;

            $venta = Venta::create([
                'date' => now('America/Lima'),
                'seriecompleta' => $seriecomprobante->serie . '-' . $numeracion,
                'direccion' => $this->direccion,
                'observaciones' => $this->observaciones,
                'exonerado' => number_format($this->exonerado, 34, '.', ''),
                'gravado' => number_format($this->gravado, 3, '.', ''),
                'gratuito' => number_format($this->gratuito, 3, '.', ''),
                'inafecto' => number_format($this->inafecto, 3, '.', ''),
                'descuento' => number_format($this->descuentos, 3, '.', ''),
                'otros' => number_format($this->otros, 3, '.', ''),
                'igv' => number_format($this->igv, 3, '.', ''),
                'igvgratuito' => number_format($this->igvgratuito, 3, '.', ''),
                'subtotal' => number_format($this->subtotal, 3, '.', ''),
                'total' => number_format($this->total, 3, '.', ''),
                'paymentactual' => number_format($this->typepayment->isCredito() ? $this->paymentactual : $this->total, 3, '.', ''),
                'tipocambio' => $this->moneda->code == 'USD' ? $this->empresa->tipocambio : null,
                'increment' => $this->increment ?? 0,
                'typepayment_id' => $this->typepayment_id,
                'client_id' => $client->id,
                'seriecomprobante_id' => $this->seriecomprobante_id,
                'moneda_id' => $this->moneda_id,
                'sucursal_id' => $this->sucursal->id,
                'user_id' => auth()->user()->id,
            ]);

            if ($this->typepay == '1') {
                foreach ($this->parcialpayments as $item) {
                    $venta->savePayment(
                        $this->sucursal->id,
                        $item["amount"],
                        $item["amount"],
                        null,
                        $this->moneda_id,
                        $item["methodpayment_id"],
                        MovimientosEnum::INGRESO->value,
                        $this->concept->id,
                        $this->openbox->id,
                        $this->monthbox->id,
                        $seriecomprobante->serie . '-' . $numeracion,
                        $item["detalle"]
                    );
                }
            } else {
                $venta->savePayment(
                    $this->sucursal->id,
                    $this->total,
                    $this->total,
                    null,
                    $this->moneda_id,
                    $this->methodpayment_id,
                    MovimientosEnum::INGRESO->value,
                    $this->concept->id,
                    $this->openbox->id,
                    $this->monthbox->id,
                    $seriecomprobante->serie . '-' . $numeracion,
                    $this->istransferencia ? $this->detallepago : null
                );
            }

            if (Module::isEnabled('Facturacion')) {
                if ($seriecomprobante->typecomprobante->isSunat()) {
                    $comprobante = $venta->createComprobante();

                    if ($this->incluyeguia) {
                        $serieguiaremision = Seriecomprobante::find($this->serieguia_id);
                        $numeracionguia =  $this->empresa->isProduccion() ? $serieguiaremision->contador + 1 : $serieguiaremision->contadorprueba + 1;

                        $guia = $comprobante->guia()->create([
                            'seriecompleta' => $serieguiaremision->serie . '-' . $numeracionguia,
                            'date' => Carbon::now('America/Lima'),
                            'expire' => Carbon::now('America/Lima')->format('Y-m-d'),
                            'datetraslado' => $this->datetraslado,
                            'ructransport' => $this->ructransport,
                            'nametransport' => $this->nametransport,
                            'rucproveedor' => null,
                            'nameproveedor' => null,
                            'placavehiculo' => $this->placavehiculo,
                            'documentdestinatario' => $this->documentdestinatario,
                            'namedestinatario' => $this->namedestinatario,
                            'documentcomprador' => $this->motivotraslado->code == '03' ? $this->document : null,
                            'namecomprador' => $this->motivotraslado->code == '03' ? $this->name : null,
                            'peso' => $this->peso,
                            'unit' => 'KGM',
                            'packages' => $this->packages,
                            'direccionorigen' => $this->direccionorigen,
                            'anexoorigen' => null,
                            'direcciondestino' => $this->direcciondestino,
                            'anexodestino' => null,
                            'note' => $this->note,
                            'sendmode' => $this->empresa->sendmode,
                            'indicadorvehiculosml' => $this->vehiculosml ? 1 : 0,
                            'indicadorvehretorvacio' => 0,
                            'indicadorvehretorenvacios' => 0,
                            'motivotraslado_id' => $this->motivotraslado_id,
                            'modalidadtransporte_id' => $this->modalidadtransporte_id,
                            'ubigeoorigen_id' => $this->ubigeoorigen_id,
                            'ubigeodestino_id' => $this->ubigeodestino_id,
                            'client_id' => $client->id,
                            'seriecomprobante_id' => $serieguiaremision->id,
                            'sucursal_id' => $this->sucursal->id,
                            'user_id' => auth()->user()->id,
                        ]);

                        if (!$this->vehiculosml) {
                            if ($this->modalidadtransporte->code == '02') {
                                $guia->transportdrivers()->create([
                                    'document' => $this->documentdriver,
                                    'name' => $this->name,
                                    'lastname' => $this->lastname,
                                    'licencia' => $this->licencia,
                                    'principal' => 1
                                ]);

                                $guia->transportvehiculos()->create([
                                    'placa' => $this->placa,
                                    'principal' => 1
                                ]);
                            }
                        }

                        if ($this->empresa->isProduccion()) {
                            $serieguiaremision->contador = $numeracionguia;
                        } else {
                            $serieguiaremision->contadorprueba = $numeracionguia;
                        }
                        $serieguiaremision->save();
                    }
                }
            }

            if ($this->guiaremision) {
                if (isset($comprobante)) {
                    $this->guiaremision->guiable()->associate($comprobante);
                    $this->guiaremision->save();
                } else {
                    $this->guiaremision->guiable()->associate($venta);
                    $this->guiaremision->save();
                }
            }

            $counter = 1;
            $totalAmountCuotas = number_format($this->total - $this->paymentactual, 3, '.', '');
            // $amountCuota = number_format($totalAmountCuotas / $this->countcuotas, 3, '.', '');
            $percentPay = 0;
            if ($this->total > 0) {
                $percentPay = number_format($this->paymentactual * 100 / ($this->total - $this->amountincrement), 3, '.', '');
            }
            $percentItem = number_format($this->increment - ($this->increment * $percentPay / 100), 3, '.', '');
            // dd($percentPay, $percentItem);

            foreach ($carshoops as $item) {
                if ($percentItem > 0) {
                    $item->price = getPriceIncrement($item->price, $percentItem);
                    $item->igv = getPriceIncrement($item->igv, $percentItem);
                    $item->subtotaligv = $item->igv * $item->cantidad;
                    $item->subtotal = $item->price  * $item->cantidad;
                    $item->total = ($item->price + $item->igv) * $item->cantidad;
                }

                $item->date = now('America/Lima');
                $item->tvitemable_id = $venta->id;
                $item->save();
                // dd($carritoSum->countnogratuitos, $carritoSum->total, $totalSinIncrement, $priceIncrItem, $porcentajeIncr);
                // $subtotalItem = number_format($item->cantidad * $item->price, 3, '.', '');
                // $subtotalItemIGV = number_format($item->cantidad *  $item->igv, 3, '.', '');
                // $totalItem = number_format($subtotalItemIGV + $subtotalItem, 3, '.', '');

                // $newTvitem = [
                //     'date' => now('America/Lima'),
                //     'cantidad' => $item->cantidad,
                //     'pricebuy' => $item->pricebuy,
                //     'price' => number_format($item->price, 3, '.', ''),
                //     'igv' => number_format($item->igv, 3, '.', ''),
                //     'subtotaligv' => number_format($item->igv * $item->cantidad, 3, '.', ''),
                //     'subtotal' => number_format($item->subtotal, 3, '.', ''),
                //     'total' => number_format($item->total, 3, '.', ''),
                //     'status' => 0,
                //     'alterstock' => $item->alterstock,
                //     'gratuito' => $item->gratuito,
                //     'increment' => $percentItem,
                //     'promocion_id' => $item->promocion_id,
                //     'almacen_id' => $item->almacen_id,
                //     'producto_id' => $item->producto_id,
                //     'user_id' => auth()->user()->id
                // ];

                // $tvitem = $venta->tvitems()->create($newTvitem);
                foreach ($item->kardexes as $kardex) {
                    $kardex->detalle = Kardex::SALIDA_VENTA;
                    $kardex->reference = $seriecomprobante->serie . '-' . $numeracion;
                    $kardex->save();
                }

                if ($this->incluyeguia) {
                    $tvgre = $item->replicate();
                    $tvgre->date = now('America/Lima');
                    $tvgre->alterstock = Almacen::NO_ALTERAR_STOCK;
                    $tvgre->tvitemable_id = $guia->id;
                    $tvgre->tvitemable_type = Guia::class;
                    $tvgre->save();
                }

                if ($this->incluyeguia && count($item->itemseries) > 0) {
                    foreach ($item->itemseries as $itemserie) {
                        $seriegre = $itemserie->replicate();
                        $seriegre->seriable_id = $tvgre->id;
                        $seriegre->save();
                    }
                }

                if ($item->gratuito) {
                    $afectacion = $item->igv > 0 ? '15' : '21';
                } else {
                    $afectacion = $item->igv > 0 ? '10' : '20';
                }

                $codeafectacion = $item->igv > 0 ? '1000' : '9997';
                $nameafectacion = $item->igv > 0 ? 'IGV' : 'EXO';
                $typeafectacion = $item->igv > 0 ? 'VAT' : 'VAT';
                $abreviatureafectacion = $item->igv > 0 ? 'S' : 'E';

                if (Module::isEnabled('Facturacion')) {
                    if ($seriecomprobante->typecomprobante->isSunat()) {
                        $descripcion = $item->producto->name;
                        if ($item->promocion && $item->promocion->isCombo()) {
                            $descripcion = $item->promocion->titulo;
                        }
                        $comprobante->facturableitems()->create([
                            'item' => $counter,
                            'descripcion' => $descripcion,
                            'code' => $item->producto->code,
                            'cantidad' => $item->cantidad,
                            'price' => number_format($item->price, 3, '.', ''),
                            'igv' => number_format($item->igv, 3, '.', ''),
                            'subtotaligv' => number_format($item->subtotaligv, 3, '.', ''),
                            'subtotal' => number_format($item->subtotal, 3, '.', ''),
                            'total' => number_format($item->total, 3, '.', ''),
                            'unit' => $item->producto->unit->code,
                            'codetypeprice' => $item->gratuito ? '02' : '01', //01: Precio unitario (incluye el IGV) 02: Valor referencial unitario en operaciones no onerosas
                            'afectacion' => $afectacion,
                            'codeafectacion' => $item->gratuito ? '9996' : $codeafectacion,
                            'nameafectacion' => $item->gratuito ? 'GRA' : $nameafectacion,
                            'typeafectacion' => $item->gratuito ? 'FRE' : $typeafectacion,
                            'abreviatureafectacion' => $item->gratuito ? 'Z' : $abreviatureafectacion,
                            'percent' => $item->igv > 0 ? $this->empresa->igv : 0,
                        ]);
                    }
                }

                $counter++;

                if ($this->incluyeguia && count($item->carshoopitems) > 0) {
                    // $countercombo = $counter;
                    foreach ($item->carshoopitems as $carshoopitem) {
                        // $stockCombo = $carshoopitem->producto->almacens->find($item->almacen_id)->pivot->cantidad;

                        // $subtotalItemCombo = number_format($item->cantidad * $carshoopitem->price, 3, '.', '');
                        // $subtotalItemIGVCombo = number_format($item->cantidad * $carshoopitem->igv, 3, '.', '');
                        // $totalItemCombo = number_format($subtotalItemCombo + $subtotalItemIGVCombo, 3, '.', '');
                        // $totalIGVcomboGRA = $totalIGVcomboGRA + $subtotalItemIGVCombo;
                        // $totalcomboGRA = $totalcomboGRA + $subtotalItemCombo;

                        $carshoopgre = $carshoopitem->replicate();
                        $carshoopgre->tvitem_id = $tvgre->id;
                        $carshoopgre->save();

                        foreach ($carshoopitem->kardexes as $kardex) {
                            $kardex->detalle = Kardex::SALIDA_COMBO_VENTA;
                            $kardex->reference = $seriecomprobante->serie . '-' . $numeracion;
                            $kardex->save();
                        }

                        // if (Module::isEnabled('Facturacion')) {
                        //     if ($seriecomprobante->typecomprobante->isSunat()) {
                        //         $comprobante->facturableitems()->create([
                        //             'item' => $counter,
                        //             'descripcion' => $carshoopitem->producto->name,
                        //             'code' => $carshoopitem->producto->code,
                        //             'cantidad' => $item->cantidad,
                        //             // 'price' => number_format($carshoopitem->price, 3, '.', ''),
                        //             // 'igv' => number_format($carshoopitem->igv, 3, '.', ''),
                        //             // 'subtotaligv' => number_format($subtotalItemIGVCombo, 3, '.', ''),
                        //             // 'subtotal' => number_format($subtotalItemCombo, 3, '.', ''),
                        //             // 'total' => number_format($totalItemCombo, 3, '.', ''),
                        //             'price' => 0,
                        //             'igv' => 0,
                        //             'subtotaligv' => 0,
                        //             'subtotal' => 0,
                        //             'total' => 0,
                        //             'unit' => $item->producto->unit->code,
                        //             'codetypeprice' => '02',
                        //             'afectacion' => $item->igv > 0 ? '15' : '21',
                        //             'codeafectacion' => '9996',
                        //             'nameafectacion' => 'GRA',
                        //             'typeafectacion' => 'FRE',
                        //             'abreviatureafectacion' => 'Z',
                        //             'percent' => $carshoopitem->igv > 0 ? $this->empresa->igv : 0,
                        //         ]);
                        //     }
                        // }
                        $counter++;
                    }

                    // if ($totalcomboGRA > 0 || $totalIGVcomboGRA > 0) {
                    //     $venta->gratuito = number_format($venta->gratuito + $totalcomboGRA, 4, '.', '');
                    //     $venta->igvgratuito = number_format($venta->igvgratuito + $totalIGVcomboGRA, 4, '.', '');
                    //     $venta->save();

                    // if (Module::isEnabled('Facturacion')) {
                    //     if ($seriecomprobante->typecomprobante->isSunat()) {
                    //         $comprobante->gratuito = number_format($comprobante->gratuito + $totalcomboGRA, 4, '.', '');
                    //         $comprobante->igvgratuito = number_format($comprobante->igvgratuito + $totalIGVcomboGRA, 4, '.', '');
                    //         $comprobante->save();
                    //     }
                    // }
                    // }
                }
            }

            if ($this->typepayment->isCredito()) {
                if ((!empty(trim($this->countcuotas))) || $this->countcuotas > 0) {
                    $venta->registrarCuotas($totalAmountCuotas, $this->countcuotas);
                } else {
                    $this->addError('countcuotas', 'Ingrese cantidad válida de cuotas');
                    return false;
                }
            }

            if ($this->empresa->isProduccion()) {
                $seriecomprobante->contador = $numeracion;
            } else {
                $seriecomprobante->contadorprueba = $numeracion;
            }
            $seriecomprobante->save();
            DB::commit();
            $this->resetValidation();
            $this->resetExcept([
                'concept',
                'openbox',
                'monthbox',
                'empresa',
                'typecomprobante',
                'typepayment',
                'moneda',
                'sucursal',
                'ubigeoorigen_id',
                'direccionorigen',
                'motivotraslado',
                'modalidadtransporte',
                'carshoopPage'
            ]);
            $this->dispatchBrowserEvent('toast', toastJSON('Venta registrado correctamente'));
            if (auth()->user()->hasPermissionTo('admin.ventas.edit')) {
                return redirect()->route('admin.ventas.edit', $venta);
            }
            return redirect()->route('admin.ventas');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getGRE()
    {

        $this->searchgre = mb_strtoupper(trim($this->searchgre), "UTF-8");
        $this->validate([
            'searchgre' => ['required', 'string', 'min:6', 'max:13']
        ]);

        DB::beginTransaction();
        try {
            $guia = Guia::with(['tvitems', 'client.direccions', 'guiable'])->where('seriecompleta', $this->searchgre)
                ->where('sucursal_id', $this->sucursal->id)->withTrashed()->first();
            if ($guia) {
                if ($guia->trashed()) {
                    $mensaje =  response()->json([
                        'title' => 'GUÍA DE REMISIÓN SE ENCUENTRA ANULADO !',
                        'text' => "Guía de remisión se encuentra anulado, ingrese una nueva guia de remisión."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                if ($guia->guiable) {
                    $mensaje =  response()->json([
                        'title' => 'GRE YA SE ENCUENTRA RELACIONADO A UN COMPROBANTE !',
                        'text' => "Guía de remisión se encuentra vinculado al comprobante " . $guia->guiable->seriecompleta . "."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                $existsreservados = $guia->tvitems()->where('alterstock', Almacen::RESERVAR_STOCK)->exists();
                if ($existsreservados) {
                    $mensaje =  response()->json([
                        'title' => 'GRE CONTIENE ITEMS PENDIENTES POR CONFIRMAR !',
                        'text' => "Guía de remisión contiene items reservados, pendientes por confirmar, verifique el estado de los items y vuelva a intentarlo."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                } else {

                    $tvitems = $guia->tvitems()->with(['producto', 'moneda', 'almacen', 'kardexes.almacen', 'itemseries.serie'])
                        ->where('alterstock', Almacen::DISMINUIR_STOCK)->get();
                    if (count($tvitems) > 0) {
                        $this->sincronizegre = true;
                        $this->guiaremision = $guia;
                        if ($guia->client) {
                            $this->client_id = $guia->client_id;
                            $this->name = $guia->client->name;
                            $this->document = $guia->client->document;
                            if (count($guia->client->direccions) > 0) {
                                $this->direccion = $guia->client->direccions()->first()->name;
                            }
                        }

                        Self::confirmaradditemsventa($tvitems);
                    } else {
                        $this->addError('searchgre', 'No se encontraron items del comprobante.');
                    }
                }
            } else {
                $this->addError('searchgre', 'No se encontraron resultados.');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function confirmaradditemsventa($tvitems)
    {
        DB::beginTransaction();
        try {
            foreach ($tvitems as $item) {
                $date = now('America/Lima');
                $pricesale = $item->producto->getPrecioVentaDefault($this->pricetype ?? null);
                $igvsale = 0;

                if ($this->empresa->isAfectacionIGV()) {
                    $priceIGV = getPriceIGV($pricesale, $this->empresa->igv);
                    $pricesale = number_format($priceIGV->price, 3, '.', '');
                    $igvsale = number_format($priceIGV->igv, 3, '.', '');
                }

                if ($this->moneda->isDolar()) {
                    $pricesale = convertMoneda($pricesale, 'USD', $this->empresa->tipocambio, 3);
                }

                $tvitem = Tvitem::create([
                    'date' => $date,
                    'cantidad' => $item->cantidad,
                    'pricebuy' =>  $item->producto->pricebuy,
                    'price' => $pricesale,
                    'igv' => $igvsale,
                    'subtotaligv' => $igvsale * $item->cantidad,
                    'subtotal' => $pricesale * $item->cantidad,
                    'total' => ($pricesale + $igvsale) * $item->cantidad,
                    'gratuito' => Tvitem::NO_GRATUITO,
                    // 'alterstock' => $item->alterstock,
                    'alterstock' => Almacen::NO_ALTERAR_STOCK,
                    'moneda_id' => $this->moneda_id,
                    'producto_id' => $item->producto_id,
                    'user_id' => auth()->user()->id,
                    'sucursal_id' => auth()->user()->sucursal_id,
                    'tvitemable_type' => Venta::class,
                ]);

                if (count($item->itemseries) > 0) {
                    foreach ($item->itemseries as $itemserie) {
                        $tvitem->itemseries()->create([
                            'date' =>  $date,
                            'serie_id' => $itemserie->serie_id,
                            'user_id' => auth()->user()->id
                        ]);
                    }
                }

                if (count($item->kardexes) > 0) {
                    foreach ($item->kardexes as $kardex) {
                        $kardexR = $kardex->replicate();
                        $kardexR->date = now('America/Lima');
                        $kardexR->detalle = Kardex::SALIDA_VENTA;
                        $kardexR->kardeable_id = $tvitem->id;
                        $kardexR->save();
                    }
                }
            }
            DB::commit();
            $this->dispatchBrowserEvent('toast', toastJSON('Items de GRE agregados correctamente'));
            $this->setTotal();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatecartpricelist($pricetype)
    {

        $tvitems = Tvitem::with(['promocion' => function ($query) {
            $query->with(['itempromos.producto']);
        }, 'producto', 'carshoopitems.producto', 'itemseries'])
            ->ventas()->micart()->orderBy('id', 'asc')->get();

        foreach ($tvitems as $item) {
            $promocion = verifyPromocion($item->promocion);
            $pricesale = $item->producto->getPrecioVentaDefault($pricetype);
            $igvsale = 0;

            if ($promocion) {
                if ($promocion->isCombo()) {
                    $combo = getAmountCombo($promocion, $pricetype);
                    $pricesale = $pricesale + $combo->total;
                }

                if (in_array($promocion->type, [PromocionesEnum::DESCUENTO->value, PromocionesEnum::OFERTA->value])) {
                    $pricesale = getPriceDscto($pricesale, $promocion->descuento, $pricetype ?? null);
                }

                if ($promocion->isLiquidacion()) {
                    $pricesale = getPriceDinamic($item->producto->pricebuy, 0, !empty($pricetype) ? $pricetype->incremento : 2, 0, !empty($pricetype) ? $pricetype->decimals : 2);
                }
            }

            $pricesale = $this->moneda->isDolar() ? convertMoneda($pricesale, 'USD', $this->empresa->tipocambio, 3) : $pricesale;
            if ($this->empresa->isAfectacionIGV()) {
                $priceIGV = getPriceIGV($pricesale, $this->empresa->igv);
                $pricesale = $priceIGV->price;
                $igvsale = $priceIGV->igv;
            }

            $item->pricebuy = $item->producto->pricebuy;
            $item->price = $pricesale;
            $item->igv = $igvsale;
            $item->subtotaligv =  $item->igv * $item->cantidad;
            $item->subtotal =  $item->price * $item->cantidad;
            if ($item->isGratuito()) {
                $item->total = 0;
            } else {
                $item->total = ($item->price + $item->igv) * $item->cantidad;
            }
            $item->save();
        }
        $this->setTotal();
    }

    public function limpiarventa()
    {

        $this->resetExcept([
            'empresa',
            'sucursal',
            'monthbox',
            'openbox',
            'moneda',
            'moneda_id',
            'typecomprobante',
            'motivotraslado',
            'modalidadtransporte',
            'typepayment',
            'methodpayment',
            'methodpayment_id',
            'pricetype',
            'pricetype_id',
            'typepayment',
            'typepayment_id',
            'carshoopPage'
        ]);
        Self::deleteallitems();
        if ($this->empresa->usarlista()) {
            $this->pricetype_id = Pricetype::activos()->orderByDesc('default')->orderBy('id', 'asc')->first()->id ?? null;
            // $this->pricetype = Pricetype::activos()->orderByDesc('default')->orderBy('id', 'asc')->first();
        }

        $this->producto = new Producto();
        $this->tvitem = new Tvitem();
        $this->motivotraslado = new Motivotraslado();
        $this->modalidadtransporte = new Modalidadtransporte();
        $this->moneda_id = Moneda::orderByDesc('default')->first()->id;
        // $this->moneda = Moneda::default()->first();
        $this->typepayment_id = Typepayment::default()->first()->id ?? null;
        $this->concept = Concept::ventas()->first();
        $this->methodpayment_id = Methodpayment::default()->first()->id ?? null;
        if (count($this->sucursal->almacens) > 0) {
            $this->almacen_id = $this->sucursal->almacens->first()->id;
        }

        self::setTotal();
        $this->resetValidation();
        $this->resetPage();
        $this->dispatchBrowserEvent('toast', toastJSON('VENTA LIMPIADA CORRECTAMENTE'));
    }

    public function delete(Tvitem $tvitem)
    {
        try {
            DB::beginTransaction();
            $tvitem->load(['itemseries.serie', 'promocion', 'producto.almacens', 'kardexes', 'carshoopitems' => function ($query) {
                $query->with(['producto', 'kardexes', 'itemseries.serie']);
            }]);
            foreach ($tvitem->itemseries as $itemserie) {
                if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                    $tvitem->updateSerieDisponible($itemserie->serie);
                }
                $itemserie->delete();
            }

            foreach ($tvitem->kardexes as $kardex) {
                $kardex->incrementOrDecrementStock($tvitem->producto, $tvitem);
                $kardex->delete();
            }

            foreach ($tvitem->carshoopitems as $carshoopitem) {
                foreach ($carshoopitem->kardexes as $kardex) {
                    $kardex->incrementOrDecrementStock($carshoopitem->producto, $tvitem);
                    $kardex->delete();
                }

                foreach ($carshoopitem->itemseries as $itemserie) {
                    if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                        $tvitem->updateSerieDisponible($itemserie->serie);
                    }
                    $itemserie->delete();
                }
                $carshoopitem->delete();
            }
            $tvitem->incrementOrDecrementPromocion($tvitem->cantidad, true);
            $tvitem->forceDelete();
            DB::commit();
            $this->reset(['tvitem']);
            $this->tvitem = new Tvitem();
            $this->resetValidation();
            self::setTotal();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteallitems()
    {
        try {
            DB::beginTransaction();
            $tvitems = Tvitem::with(['itemseries.serie', 'kardexes.almacen', 'producto.almacens'])->ventas()->micart()->orderBy('id', 'asc')->get();
            $mensaje = count($tvitems) . " ITEMS ELIMINADOS CORRECTAMENTE";
            $tvitems->map(function ($tvitem) {
                Self::delete($tvitem);
            });
            DB::commit();
            $this->resetValidation();
            self::setTotal();
            $this->dispatchBrowserEvent('toast', toastJSON($mensaje));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getcombos(Producto $producto)
    {
        $this->resetValidation();
        $producto->load(['unit', 'imagen', 'almacens', 'seriesdisponibles' => function ($query) {
            $query->with('almacen');
        }, 'promocions' => function ($query) {
            $query->with(['itempromos.producto' => function ($subQuery) {
                $subQuery->with(['unit', 'almacens', 'imagen']);
            }])->availables()->disponibles();
        }])->loadCount(['almacens as stock' => function ($query) {
            $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
        }]);
        $producto->descuento = $producto->promocions->where('type', PromocionesEnum::DESCUENTO->value)->first()->descuento ?? 0;
        $producto->liquidacion = $producto->promocions->where('type', PromocionesEnum::LIQUIDACION->value)->count() > 0 ? true : false;
        $this->producto = $producto;

        if ($producto->isRequiredserie()) {
            foreach ($producto->promocions as $item) {
                $this->seriespromocion[$item->id]['serie_id'] = null;
            }
        }
        $this->opencombos =  true;
    }

    public function seterrors($errors, $index = null)
    {
        $this->resetValidation();
        if (count($errors) > 0) {
            foreach ($errors as $key => $value) {
                if (str_starts_with($key, 'selectedalmacen_')) {
                    $key = 'almacen_id';
                }
                if (empty($index)) {
                    $this->addError($key, $value);
                } else {
                    $this->addError("cart.$index.$key", $value);
                }
            }
        }
    }

    public function desvinculargre()
    {
        $this->reset(['searchgre', 'sincronizegre', 'guiaremision']);
    }

    public function searchcliente($property, $name)
    {
        $this->resetValidation();
        $this->$property = trim($this->$property);

        if ($this->incluyeguia && $property !== 'document') {
            if (!empty($this->motivotraslado_id)) {
                $this->motivotraslado = Motivotraslado::find($this->motivotraslado_id);
            }
            if (!empty($this->modalidadtransporte_id)) {
                $this->modalidadtransporte = Modalidadtransporte::find($this->modalidadtransporte_id);
            }
        }

        if ($property == 'ructransport') {
            $this->validate(['ructransport' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
                'numeric',
                'digits:11',
                'regex:/^\d{11}$/',
                $this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.document' : '',
            ]]);
        } else if ($property == 'documentdestinatario') {
            $this->validate(['documentdestinatario' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia),
                'numeric',
                'regex:/^\d{8}(?:\d{3})?$/',
                $this->incluyeguia && $this->motivotraslado->code == '03' ? 'different:document' : '',
                // 'different:document',
            ]]);
        } else if ($property == 'documentdriver') {
            $this->validate(['documentdriver' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02'),
                'numeric',
                'regex:/^\d{8}(?:\d{3})?$/'
            ]]);
        } else {
            $this->validate(['document' => [
                'required',
                'numeric',
                new ValidateDocument,
                'regex:/^\d{8}(?:\d{3})?$/',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.document' : ''),
            ]]);
        }

        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->$property,
            'autosaved' => $property == 'document' ? true : false,
            'savedireccions' => $property == 'document' ? true : false,
            'searchbd' => true,
            'obtenerlista' => $property == 'document' ? true : false,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());
            // dd($cliente);
            if (isset($cliente->success) && $cliente->success) {
                $this->$name = $cliente->name;
                if ($property == 'document') {
                    $this->direccion = $cliente->direccion;
                    if ($this->empresa->usarLista() && $cliente->pricetype) {
                        $this->pricetype_id = $cliente->pricetype->id;
                        $this->pricetype = Pricetype::find($cliente->pricetype->id);
                        $this->updatecartpricelist($this->pricetype);
                    }
                    if (isset($cliente->id)) {
                        $this->client_id = $cliente->id;
                    }
                    if ($cliente->birthday) {
                        $this->dispatchBrowserEvent('birthday', $cliente->name);
                    }
                }
            } else {
                $this->$name = '';
                if ($property == 'document') {
                    $this->direccion = '';
                }
                $mensaje =  response()->json([
                    'title' => $cliente->error,
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
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

    // Para modal-carshoopitems
    public function confirmkardexstock($key)
    {
        $validateData = $this->validate([
            "almacens.$key.id" => ['required', 'integer', 'min:1', 'exists:almacens,id'],
            'tvitem.producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            "almacens.$key.cantidad" => $this->tvitem->producto->isRequiredserie() ?
                ['nullable'] : [
                    'required',
                    'integer',
                    'gt:0',
                    new ValidateStock($this->tvitem->producto_id, $this->almacens[$key]['id'], $this->almacens[$key]['cantidad']),
                    'lte:' . $this->tvitem->cantidad - $this->tvitem->kardexes->sum('cantidad'),
                ],
            "almacens.$key.serie_id" => $this->tvitem->producto->isRequiredserie() ?
                [
                    Rule::requiredIf($this->tvitem->producto->isRequiredserie()),
                    'integer',
                    'min:1',
                    'exists:series,id',
                    new ValidateStock($this->tvitem->producto_id, $this->almacens[$key]['id'], 1),
                ] : ['nullable'],
        ], [], [
            "tvitem.producto_id" => 'producto',
            "almacens.$key.id" => 'almacen',
            "almacens.$key.cantidad" => 'cantidad',
            "almacens.$key.serie_id" => 'serie',
        ]);

        DB::beginTransaction();
        try {
            $date = now('America/Lima');
            $serie_id = $this->tvitem->producto->isRequiredserie() && !empty($this->almacens[$key]['serie_id']) ? $this->almacens[$key]['serie_id'] : null;
            $cantidad = $this->tvitem->producto->isRequiredserie() ? 1 : $this->almacens[$key]['cantidad'];
            $stock = $this->tvitem->producto->almacens()->find($key)->pivot->cantidad;

            if (!empty($serie_id)) {
                $serie = Serie::find($serie_id);
                if ($this->tvitem->itemseries()->where('serie_id', $serie_id)->exists()) {
                    $mensaje =  response()->json([
                        'title' => "SERIE $serie->serie YA SE ENCUENTRA AGREGADO !",
                        'text' => null
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                if ($this->tvitem->isDiscountStock() || $this->tvitem->isReservedStock()) {
                    if (!$serie->isDisponible()) {
                        $mensaje =  response()->json([
                            'title' => "SERIE $serie->serie NO SE ENCUENTRA DISPONIBLE !",
                            'text' => null
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }
                    $this->tvitem->registrarSalidaSerie($serie_id);
                } else {
                    $this->tvitem->itemseries()->create([
                        'date' =>  $date,
                        'serie_id' => $serie_id,
                        'user_id' => auth()->user()->id
                    ]);
                }
            }

            $kardex = $this->tvitem->updateOrCreateKardex($key, $stock, $cantidad);
            $kardex->detalle = Kardex::SALIDA_VENTA;
            $kardex->save();
            if ($this->tvitem->isDiscountStock() || $this->tvitem->isReservedStock()) {
                $this->tvitem->producto->descontarStockProducto($key, $cantidad);
            }
            DB::commit();
            $this->dispatchBrowserEvent('toast', toastJSON('STOCK ACTUALIZADO CORRECTAMENTE'));
            $this->tvitem->refresh();
            // if ($this->tvitem->kardexes->sum('cantidad') == $this->tvitem->cantidad) {
            //     $this->openstock = false;
            // }
            foreach ($this->tvitem->producto->almacens as $item) {
                $this->almacens[$item->id]['tvitem_id'] = $this->tvitem->id;
                $this->almacens[$item->id]['id'] = $item->id;
                $this->almacens[$item->id]['serie_id'] = null;
                $this->almacens[$item->id]['cantidad'] = $this->tvitem->producto->isRequiredserie() ? 1 : 0;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletekardex(Tvitem $tvitem, Kardex $kardex)
    {
        DB::beginTransaction();
        try {
            $tvitem->load(['producto.almacens']);
            if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                $tvitem->producto->incrementarStockProducto($kardex->almacen_id, $kardex->cantidad);
            }
            $tvitem->incrementOrDecrementPromocion($kardex->cantidad, true);
            $kardex->delete();
            $tvitem->cantidad = $tvitem->cantidad - $kardex->cantidad;
            if ($tvitem->cantidad == 0) {
                $tvitem->forceDelete();
                $this->reset(['tvitem']);
                $this->tvitem = new Tvitem();
            } else {
                $tvitem->save();
            }
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletekardexcarshoop(Carshoopitem $carchoopitem, Kardex $kardex)
    {
        DB::beginTransaction();
        try {
            $carchoopitem->load(['tvitem', 'producto.almacens']);
            if ($carchoopitem->tvitem->isDiscountStock() || $carchoopitem->tvitem->isReservedStock()) {
                $carchoopitem->producto->incrementarStockProducto($kardex->almacen_id, 1);
            }
            $kardex->delete();
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteitemserie(Itemserie $itemserie)
    {
        DB::beginTransaction();
        try {
            $itemserie->load(['seriable' => function ($query) {
                $query->with(['kardexes', 'promocion']);
            }, 'serie'  => function ($query) {
                $query->with(['producto.almacens']);
            }]);

            // $itemserie->load(['seriable.kardexes', 'serie'  => function ($query) {
            //     $query->with(['producto.almacens']);
            // }]);
            $tvitem = $itemserie->seriable;
            $almacen_id = $itemserie->serie->almacen_id;
            $kardex = $tvitem->kardexes->where('almacen_id', $almacen_id)->first();

            if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                $tvitem->updateSerieDisponible($itemserie->serie);
                if ($kardex) {
                    $itemserie->serie->producto->incrementarStockProducto($almacen_id, 1);
                }
            }

            if ($kardex) {
                $kardex->cantidad = $kardex->cantidad - 1;
                $kardex->newstock = $kardex->newstock - 1;
                if ($kardex->cantidad == 0) {
                    $kardex->delete();
                } else {
                    $kardex->save();
                }
            }
            $itemserie->delete();
            $tvitem->incrementOrDecrementPromocion(1, true);
            $tvitem->cantidad = $tvitem->cantidad - 1;
            if ($tvitem->cantidad == 0) {
                $tvitem->forceDelete();
                $this->reset(['tvitem']);
                $this->tvitem = new Tvitem();
            } else {
                $tvitem->save();
            }
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteitemserieitem(Itemserie $itemserie)
    {
        DB::beginTransaction();
        try {
            $itemserie->load(['seriable.kardexes', 'serie'  => function ($query) {
                $query->with(['producto.almacens']);
            }]);
            $carshoopitem = $itemserie->seriable;
            $almacen_id = $itemserie->serie->almacen_id;
            $kardex = $carshoopitem->kardexes->where('almacen_id', $almacen_id)->first();
            // dd($carshoopitem->tvitem->isDiscountStock());
            if ($carshoopitem->tvitem->isDiscountStock() || $carshoopitem->tvitem->isReservedStock()) {
                $carshoopitem->tvitem->updateSerieDisponible($itemserie->serie);
                if ($kardex) {
                    $itemserie->serie->producto->incrementarStockProducto($almacen_id, 1);
                }
            }

            if ($kardex) {
                $kardex->cantidad = $kardex->cantidad - 1;
                $kardex->newstock = $kardex->newstock - 1;
                if ($kardex->cantidad == 0) {
                    $kardex->delete();
                } else {
                    $kardex->save();
                }
            }
            $itemserie->delete();
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function openmodalcarshoops(Tvitem $tvitem)
    {

        $this->reset(['almacens', 'almacenitem']);
        $this->resetValidation();

        $tvitem->load(['itemseries' => function ($query) {
            $query->with(['serie.almacen']);
        }, 'kardexes.almacen', 'producto' => function ($query) {
            $query->with(['almacens', 'unit', 'seriesdisponibles']);
        }, 'carshoopitems' => function ($query) {
            $query->with(['kardexes.almacen', 'itempromo', 'itemseries' => function ($query) {
                $query->with(['serie.almacen']);
            }, 'producto' => function ($subq) {
                $subq->with(['almacens', 'unit', 'marca', 'category', 'seriesdisponibles']);
            }]);
        }]);
        $this->tvitem = $tvitem;
        foreach ($tvitem->producto->almacens as $item) {
            $this->almacens[$item->id]['tvitem_id'] = $tvitem->id;
            $this->almacens[$item->id]['id'] = $item->id;
            $this->almacens[$item->id]['serie_id'] = '';
            $this->almacens[$item->id]['cantidad'] = $tvitem->producto->isRequiredserie() ? 1 : 0;
        }

        foreach ($tvitem->carshoopitems as $item) {
            foreach ($item->producto->almacens as $almacen) {
                $this->almacenitem[$item->id]['almacens'][$almacen->id]['id'] = $almacen->id;
                $this->almacenitem[$item->id]['almacens'][$almacen->id]['serie_id'] = '';
                $this->almacenitem[$item->id]['almacens'][$almacen->id]['cantidad'] = $item->producto->isRequiredserie() ? 1 : 0;
            }
        }
    }

    public function confirmkardexstockitem($key, Carshoopitem $carshoopitem)
    {

        $carshoopitem->load(['tvitem', 'kardexes.almacen', 'itempromo', 'producto' => function ($query) {
            $query->with(['unit', 'almacens', 'series' => function ($subq) {
                $subq->disponibles();
            }]);
        }, 'itemseries' => function ($query) {
            $query->with(['serie.almacen']);
        }]);

        // dd($this->almacenitem);
        $validateData = $this->validate([
            "almacenitem.$carshoopitem->id.almacens.$key.id" => ['required', 'integer', 'min:1', 'exists:almacens,id'],
            // 'carshoopitem.producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            "almacenitem.$carshoopitem->id.almacens.$key.cantidad" => $carshoopitem->producto->isRequiredserie() ?
                ['nullable'] : [
                    'required',
                    'integer',
                    'gt:0',
                    new ValidateStock($carshoopitem->producto_id, $this->almacenitem[$carshoopitem->id]['almacens'][$key]['id'], $this->almacenitem[$carshoopitem->id]['almacens'][$key]['cantidad']),
                    'lte:' . $carshoopitem->cantidad - $carshoopitem->kardexes->sum('cantidad'),
                ],
            "almacenitem.$carshoopitem->id.almacens.$key.serie_id" => $carshoopitem->producto->isRequiredserie() ?
                [
                    Rule::requiredIf($carshoopitem->producto->isRequiredserie()),
                    'integer',
                    'min:1',
                    'exists:series,id',
                    new ValidateStock($carshoopitem->producto_id, $this->almacenitem[$carshoopitem->id]['almacens'][$key]['id'], 1),
                ] : ['nullable'],
        ], [], [
            "almacenitem.$carshoopitem->id.almacens.$key.id" => 'almacen',
            "almacenitem.$carshoopitem->id.almacens.$key.cantidad" => 'cantidad',
            "almacenitem.$carshoopitem->id.almacens.$key.serie_id" => 'serie',
        ]);

        DB::beginTransaction();
        try {
            $serie_id = $carshoopitem->producto->isRequiredserie() && !empty($this->almacenitem[$carshoopitem->id]['almacens'][$key]['serie_id']) ? $this->almacenitem[$carshoopitem->id]['almacens'][$key]['serie_id'] : null;
            $cantidad = $carshoopitem->producto->isRequiredserie() ? 1 : $this->almacenitem[$carshoopitem->id]['almacens'][$key]['cantidad'];
            $stock = $carshoopitem->producto->almacens()->find($key)->pivot->cantidad;

            if (!empty($serie_id)) {
                $serie = Serie::find($serie_id);

                if ($carshoopitem->itemseries()->where('serie_id', $serie_id)->exists()) {
                    $mensaje =  response()->json([
                        'title' => "SERIE $serie->serie YA SE ENCUENTRA AGREGADO !",
                        'text' => null
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                if ($carshoopitem->tvitem->isDiscountStock() || $carshoopitem->tvitem->isReservedStock()) {
                    if (!$serie->isDisponible()) {
                        $mensaje =  response()->json([
                            'title' => "SERIE $serie->serie NO SE ENCUENTRA DISPONIBLE !",
                            'text' => null
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }
                    $carshoopitem->registrarSalidaSerie($serie_id);
                } else {
                    $carshoopitem->itemseries()->create([
                        'date' => now('America/Lima'),
                        'serie_id' => $serie_id,
                        'user_id' => auth()->user()->id
                    ]);
                }
            }

            $kardex = $carshoopitem->updateOrCreateKardex($key, $stock, $cantidad);
            $kardex->detalle = Kardex::SALIDA_VENTA;
            $kardex->save();

            if ($carshoopitem->tvitem->isDiscountStock() || $carshoopitem->tvitem->isReservedStock()) {
                $carshoopitem->producto->descontarStockProducto($key, $cantidad);
            }
            DB::commit();
            $this->dispatchBrowserEvent('toast', toastJSON('STOCK ACTUALIZADO CORRECTAMENTE'));
            foreach ($carshoopitem->tvitem->carshoopitems as $item) {
                foreach ($item->producto->almacens as $almacen) {
                    $this->almacenitem[$item->id]['almacens'][$almacen->id]['id'] = $almacen->id;
                    $this->almacenitem[$item->id]['almacens'][$almacen->id]['serie_id'] = '';
                    $this->almacenitem[$item->id]['almacens'][$almacen->id]['cantidad'] = $item->producto->isRequiredserie() ? 1 : 0;
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
    // End

    // public function hydrate()
    // {
    //     if ($this->opencombos) {
    //         $this->producto->load(['unit', 'imagen', 'almacens', 'seriesdisponibles' => function ($query) {
    //             $query->with('almacen');
    //         }, 'promocions' => function ($query) {
    //             $query->with(['itempromos.producto' => function ($subQuery) {
    //                 $subQuery->with(['unit', 'almacens', 'imagen']);
    //             }])->availables()->disponibles();
    //         }])->loadCount(['almacens as stock' => function ($query) {
    //             $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
    //         }]);
    //         $this->producto->descuento = $this->producto->promocions->whereIn('type', [PromocionesEnum::DESCUENTO->value, PromocionesEnum::OFERTA->value])->first()->descuento ?? 0;
    //         $this->producto->liquidacion = $this->producto->promocions->where('type', PromocionesEnum::LIQUIDACION->value)->count() > 0 ? true : false;
    //     }
    // }
}
