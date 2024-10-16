<?php

namespace App\Http\Livewire\Modules\Ventas\Ventas;

use App\Enums\MovimientosEnum;
use App\Helpers\GetClient;
use App\Models\Category;
use App\Models\Moneda;
use App\Models\Serie;
use App\Rules\ValidateStock;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Almacen;
use App\Models\Carshoop;
use App\Models\Carshoopserie;
use App\Models\Client;
use App\Models\Concept;
use App\Models\Guia;
use App\Models\Kardex;
use App\Models\Methodpayment;
use App\Models\Modalidadtransporte;
use App\Models\Monthbox;
use App\Models\Motivotraslado;
use App\Models\Openbox;
use App\Models\Producto;
use App\Models\Seriecomprobante;
use App\Models\Tvitem;
use App\Models\Typepayment;
use App\Rules\ValidateCarrito;
use App\Rules\ValidateDocument;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Modules\Ventas\Entities\Venta;
use Nwidart\Modules\Facades\Module;

class CreateVenta extends Component
{

    use WithPagination;

    public $empresa, $sucursal, $pricetype, $moneda, $producto;
    public $open = false;
    public $disponibles = 1;
    public $almacendefault;
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

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'producto'],
        'searchcategory' => ['except' => '', 'as' => 'categoria'],
        'searchsubcategory' => ['except' => '', 'as' => 'subcategoria'],
        'searchmarca' => ['except' => '', 'as' => 'marca'],
    ];


    public $typepayment, $modalidadtransporte, $motivotraslado;
    public $cotizacion_id, $client_id, $direccion_id, $mensaje;
    public $moneda_id, $monthbox, $openbox, $methodpayment_id, $typepayment_id, $detallepago, $concept;
    public $typecomprobante, $typecomprobante_id, $seriecomprobante_id, $tribute_id;
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
            'direccion' => ['required', 'string', 'min:3'],
            'client_id' => ['required', 'integer', 'min:1', 'exists:clients,id'],
            'typecomprobante.id' => ['required', 'integer', 'min:1', 'exists:typecomprobantes,id'],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'typepayment.id' => ['required', 'integer', 'min:1', 'exists:typepayments,id'],
            'typepayment_id' => ['required', 'integer', 'min:1', 'exists:typepayments,id'],
            'paymentactual' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 1),
                'numeric',
                'min:0',
                $this->typepayment->paycuotas ? 'lt:' . $this->total : '',
                'decimal:0,2'
            ],
            'increment' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 1),
                'numeric',
                'min:0',
                'decimal:0,2'
            ],
            'countcuotas' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 1),
                'integer',
                'min:1'
            ],
            'concept.id' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer',
                'min:1',
                'exists:concepts,id',
            ],
            'openbox.id' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer',
                'min:1',
                'exists:openboxes,id',
            ],
            'monthbox.id' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer',
                'min:1',
                'exists:monthboxes,id',
            ],
            'typepay' => [
                'required',
                'integer',
                'min:0',
                'max:1',
            ],
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
            'detallepago' => ['nullable'],
            'cotizacion_id' => ['nullable', 'integer', 'min:1', 'exists:cotizacions,id'],
            'sucursal.id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'seriecomprobante_id' => ['required', 'integer', 'min:1', 'exists:seriecomprobantes,id'],
            'items' => [
                'required',
                'array',
                'min:1',
                new ValidateCarrito($this->moneda->id, $this->sucursal->id)
            ],
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
            'peso' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia),
                'numeric',
                'gt:0',
                'decimal:0,4',
            ],
            'packages' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia),
                'integer',
                'min:1',
            ],
            'datetraslado' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia),
                'date',
                'after_or_equal:today',
            ],
            'placavehiculo' => ['nullable', 'string', 'min:6', 'max:8'],
            'note' => ['nullable', 'string', 'min:10'],
            'ubigeoorigen_id' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia),
                'integer',
                'min:1',
                'exists:ubigeos,id'
            ],
            'direccionorigen' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia),
                'string',
                'min:6',
            ],
            'ubigeodestino_id' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia),
                'integer',
                'min:1',
                'exists:ubigeos,id'
            ],
            'direcciondestino' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia),
                'string',
                'min:6',
            ],
            'motivotraslado_id' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia),
                'integer',
                'min:1',
                'exists:motivotraslados,id'
            ],
            'modalidadtransporte_id' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia),
                'integer',
                'min:1',
                'exists:modalidadtransportes,id'
            ],
            'serieguia_id' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia),
                'integer',
                'min:1',
                'exists:seriecomprobantes,id'
            ],
        ];
    }

    protected function messages()
    {
        return [
            'parcialpayments.required_if' => 'No se encontraron registros de pagos.'
        ];
    }

    protected $validationAttributes = [
        'items' => 'carrito de ventas',
        'document' => 'dni / ruc',
        'searchgre' =>  'serie guía remisión'
    ];


    public function mount(Seriecomprobante $seriecomprobante, Moneda $moneda, Concept $concept, $pricetype, $empresa)
    {
        $this->producto = new Producto();
        $this->sucursal = auth()->user()->sucursal;
        $this->empresa = $empresa;
        $this->pricetype = $pricetype;
        $this->moneda = $moneda;
        $this->pricetype_id = $pricetype->id ?? null;

        if (trim($this->searchcategory) !== '') {
            $this->subcategories = Category::with('subcategories')->find($this->searchcategory)->subcategories()
                ->whereHas('productos')->orderBy('orden', 'asc')->orderBy('name', 'asc')->get();
        }

        if (count(auth()->user()->sucursal->almacens) > 0) {
            $this->almacendefault = auth()->user()->sucursal->almacens()->first();
            $this->almacen_id = $this->almacendefault->id;
        }

        $this->motivotraslado = new Motivotraslado();
        $this->modalidadtransporte = new Modalidadtransporte();
        $this->typepayment = new Typepayment();
        $this->methodpayment_id = Methodpayment::default()->first()->id ?? null;
        $this->sucursal = auth()->user()->sucursal;
        $this->empresa = mi_empresa();
        $this->monthbox = Monthbox::usando($this->sucursal->id)->first();
        $this->openbox = Openbox::mybox($this->sucursal->id)->first();
        $this->typepayment_id = Typepayment::default()->first()->id ?? null;
        $this->seriecomprobante_id = $seriecomprobante->id ?? null;
        $this->typecomprobante = $seriecomprobante->typecomprobante;
        $this->moneda_id = $moneda->id ?? null;
        $this->moneda = $moneda;
        $this->concept = $concept;
        $this->setTotal();
        $this->ubigeoorigen_id = $this->sucursal->ubigeo_id;
        $this->direccionorigen = $this->sucursal->direccion;
    }

    public function render()
    {

        $almacens = [];
        if ($this->almacen_id) {
            $almacens[] = $this->almacen_id;
        }

        $productos = Producto::query()->select('id', 'name', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5', 'requireserie', 'marca_id', 'category_id', 'subcategory_id', 'unit_id')
            ->addSelect(['image' => function ($query) {
                $query->select('url')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('default', 'desc')->limit(1);
            }])->with([
                'garantiaproductos.typegarantia',
                'seriesdisponibles' => function ($query) {
                    $query->where('almacen_id', $this->almacen_id);
                },
                'category' => function ($query) {
                    $query->select('id', 'name');
                },
                'marca',
                'unit',
                'promocions' => function ($query) {
                    $query->with(['itempromos.producto' => function ($subQuery) {
                        $subQuery->with('unit')->addSelect(['image' => function ($q) {
                            $q->select('url')->from('images')
                                ->whereColumn('images.imageable_id', 'productos.id')
                                ->where('images.imageable_type', Producto::class)
                                ->orderBy('default', 'desc')->limit(1);
                        }]);
                    }])->availables()->disponibles();
                }
            ])->withWhereHas('almacens', function ($query) use ($almacens) {
                $query->whereIn('almacens.id', $almacens);
                if ($this->disponibles) {
                    $query->where('cantidad', '>', 0);
                }
            });

        if (trim($this->search) !== '') {
            $searchTerms = explode(' ', $this->search);
            $productos->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->orWhere('name', 'ilike', '%' . $term . '%')
                        ->orWhereHas('marca', function ($q) use ($term) {
                            $q->whereNull('deleted_at')->where('name', 'ilike', '%' . $term . '%');
                        })
                        ->orWhereHas('category', function ($q) use ($term) {
                            $q->whereNull('deleted_at')->where('name', 'ilike', '%' . $term . '%');
                        })
                        ->orWhereHas('especificacions', function ($q) use ($term) {
                            $q->where('especificacions.name', 'ilike', '%' . $term . '%');
                        });
                }
            });
        }

        if (trim($this->searchmarca) !== "") {
            $productos->where('marca_id', $this->searchmarca);
        }

        if (trim($this->searchcategory) !== "") {
            $productos->where('category_id', $this->searchcategory);
        }

        if (trim($this->searchsubcategory) !== "") {
            $productos->where('subcategory_id', $this->searchsubcategory);
        }

        $productos = $productos->visibles()->orderBy('name', 'asc')->paginate(20)
            ->through(function ($producto) {
                $producto->promocion = $producto->promocions->first();
                return $producto;
            });

        if (Module::isEnabled('Facturacion')) {
            $modalidadtransportes = Modalidadtransporte::orderBy('code', 'asc')->get();
            $motivotraslados = Motivotraslado::whereIn('code', ['01', '03'])->orderBy('code', 'asc')->get();
        } else {
            $modalidadtransportes = [];
            $motivotraslados = [];
        }

        $typecomprobantes = auth()->user()->sucursal->seriecomprobantes()
            ->withWhereHas('typecomprobante', function ($query) {
                $query->whereNotIn('code', ['07', '09']);
                if (Module::isDisabled('Facturacion')) {
                    $query->default();
                }
            });
        $comprobantesguia = auth()->user()->sucursal->seriecomprobantes()
            ->withWhereHas('typecomprobante', function ($query) {
                $query->whereIn('code', ['09', '13']);
                if (Module::isDisabled('Facturacion')) {
                    $query->default();
                }
            });

        $typecomprobantes = $typecomprobantes->orderBy('code', 'asc')->get();
        $comprobantesguia = $comprobantesguia->orderBy('code', 'asc')->get();
        $carshoops = Carshoop::with(['carshoopseries', 'producto', 'almacen', 'promocion' => function ($query) {
            $query->with('itempromos.producto');
        }])->ventas()->where('user_id', auth()->user()->id)->where('sucursal_id', auth()->user()->sucursal_id)
            ->orderBy('id', 'asc')->paginate();

        if ($this->empresa->usepricedolar) {
            $monedas = Moneda::orderBy('currency', 'asc')->get();
        } else {
            $monedas = Moneda::where('code', 'PEN')->orderBy('currency', 'asc')->get();
        }

        return view('livewire.modules.ventas.ventas.create-venta', compact('productos', 'carshoops', 'typecomprobantes', 'comprobantesguia', 'monedas', 'modalidadtransportes', 'motivotraslados'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSearchmarca()
    {
        $this->resetPage();
    }

    public function updatedSearchcategory()
    {
        $this->resetPage();
    }

    public function updatedSearchsubcategory()
    {
        $this->resetPage();
    }

    public function updatedAlmacenId($value)
    {
        $this->resetPage();
        $this->resetValidation();
        if ($value) {
            $this->almacendefault = Almacen::find($value);
        }
    }

    public function getProductoBySerie()
    {

        $this->searchserie = trim($this->searchserie);
        $this->validate([
            'searchserie' => ['required', 'string', 'min:4'],
            'moneda.id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
        ]);

        $cantidad = 1;
        $existskardex = false;
        $date = now('America/Lima');
        $promocion_id = null;
        $carshoopitems = collect([]);

        DB::beginTransaction();
        try {

            $serieProducto = Serie::withWhereHas('producto', function ($query) {
                $query->select('id', 'name', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5', 'requireserie', 'unit_id')
                    ->with('unit')->visibles();
            })->disponibles()->whereRaw('UPPER(serie) = ?', trim(mb_strtoupper($this->searchserie, "UTF-8")))
                ->whereIn('almacen_id', auth()->user()->sucursal->almacens()->pluck('almacen_id'))->first();

            if (empty($serieProducto)) {
                $mensaje =  response()->json([
                    'title' => 'SERIE NO SE ENCUENTRA DISPONIBLE !',
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            if (Carshoopserie::where('serie_id', $serieProducto->id)->exists()) {
                $mensaje =  response()->json([
                    'title' => 'SERIE YA SE ENCUENTRA REGISTRADO EN EL CARRITO !',
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            if ($serieProducto->isDisponible()) {
                $serieProducto->status = Serie::SALIDA;
                $serieProducto->dateout = $date;
                $serieProducto->save();
            } else {
                $mensaje =  response()->json([
                    'title' => 'SERIE NO SE ENCUENTRA DISPONIBLE !',
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $this->validate([
                'searchserie' => [
                    new ValidateStock($serieProducto->producto_id, $serieProducto->almacen_id, $cantidad)
                ],
            ]);
            $stock = $serieProducto->producto->almacens->find($serieProducto->almacen_id)->pivot->cantidad;

            if ($stock && $stock > 0) {
                if (($stock - $cantidad) < 0) {
                    $mensaje =  response()->json([
                        'title' => 'CANTIDAD SUPERA EL STOCK DISPONIBLE DEL PRODUCTO !',
                        'text' => null
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }
            }

            $promocion = verifyPromocion($serieProducto->producto->promocions->first());
            $combo = $serieProducto->producto->getAmountCombo($promocion, $this->pricetype ?? null, $this->almacen_id);
            $pricesale = $serieProducto->producto->obtenerPrecioVenta($this->pricetype ?? null);

            if ($this->moneda->isDolar()) {
                $pricesale = convertMoneda($pricesale, 'USD', $this->empresa->tipocambio, 2);
            } else {
                $pricesale = formatDecimalOrInteger($pricesale, 2);
            }

            if ($promocion) {
                if ($promocion->limit > 0 && $cantidad + $promocion->outs > $promocion->limit) {
                    $mensaje =  response()->json([
                        'title' => "CANTIDAD SOBREPASA EL STOCK DEL PRODUCTO EN PROMOCIÓN !",
                        'text' => "La cantidad sobrepasa el límite de unidades en promoción del producto [" . $promocion->limit - $promocion->outs . " " . $promocion->producto->unit->name . " DISPONIBLES]."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                if ($promocion->limit > 0 && $promocion->outs >= $promocion->limit) {
                    $mensaje =  response()->json([
                        'title' => "STOCK DEL PRODUCTO EN PROMOCIÓN AGOTADO !",
                        'text' => "Límite de unidades en promoción del producto agotado."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                if ($combo) {
                    if (count($combo->products) > 0) {
                        foreach ($combo->products as $itemcombo) {
                            if (($itemcombo->stock - $cantidad) < 0) {
                                $mensaje =  response()->json([
                                    'title' => "CANTIDAD DEL PRODUCTO EN COMBO SUPERA EL STOCK DISPONIBLE EN ALMACÉN !",
                                    'text' => "La cantidad del producto $itemcombo->name ,supera stock disponible en almacén seleccionado."
                                ])->getData();
                                $this->dispatchBrowserEvent('validation', $mensaje);
                                return false;
                            }

                            $carshoopitems->push([
                                'date' => now('America/Lima'),
                                'pricebuy' => $itemcombo->pricebuy,
                                // 'price' => $itemcombo->price,
                                'price' => 0,
                                'igv' => 0,
                                'stock' => $itemcombo->stock,
                                'newstock' => $itemcombo->stock - $cantidad,
                                'producto_id' => $itemcombo->producto_id
                            ]);

                            Producto::find($itemcombo->producto_id)->almacens()->updateExistingPivot($this->almacen_id, [
                                'cantidad' => $itemcombo->stock - $cantidad,
                            ]);
                        }
                    }
                }

                $promocion_id = $promocion->id;
                $promocion->outs = $promocion->outs + $cantidad;
                $promocion->save();
                if ($promocion->limit ==  $promocion->outs) {
                    $serieProducto->producto->assignPrice($promocion);
                }
            }

            $pricesaleNew = $this->empresa->isAfectacionIGV() ? getPriceIGV($pricesale, $this->empresa->igv)->price : $pricesale;
            $igvsale = $this->empresa->isAfectacionIGV() ? getPriceIGV($pricesale, $this->empresa->igv)->igv : 0;
            $existsInCart = Carshoop::where('producto_id', $serieProducto->producto_id)
                ->where('almacen_id', $serieProducto->almacen_id)
                ->where('moneda_id', $this->moneda->id)
                ->where('gratuito', '0')
                ->where('user_id', auth()->user()->id)
                ->where('sucursal_id', auth()->user()->sucursal_id)
                ->where('alterstock', Almacen::DISMINUIR_STOCK)
                ->where('promocion_id', $promocion_id)
                ->where('cartable_type', Venta::class);

            if ($existsInCart->exists()) {
                $carshoop = $existsInCart->first();
                $carshoop->cantidad = $carshoop->cantidad + $cantidad;
                $carshoop->pricebuy = $serieProducto->producto->pricebuy;
                $carshoop->price = $this->empresa->isAfectacionIGV() ? getPriceIGV($pricesale, $this->empresa->igv)->price : $pricesale;
                $carshoop->igv = $this->empresa->isAfectacionIGV() ? getPriceIGV($pricesale, $this->empresa->igv)->igv : 0;
                $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                $carshoop->total = $pricesale * $carshoop->cantidad;
                $carshoop->save();

                if ($carshoop->kardex) {
                    $existskardex = true;
                    $carshoop->kardex->cantidad = $carshoop->kardex->cantidad +  $cantidad;
                    $carshoop->kardex->newstock = $carshoop->kardex->newstock - $cantidad;
                    $carshoop->kardex->save();
                }

                if ($carshoopitems->count() > 0) {
                    if (count($carshoop->carshoopitems) == 0) {
                        foreach ($carshoopitems as $itemcombo) {
                            $carshoop->carshoopitems()->create([
                                'pricebuy' => $itemcombo["pricebuy"],
                                // 'price' => $itemcombo["price"],
                                // 'igv' => $itemcombo["igv"],
                                'price' => 0,
                                'igv' => 0,
                                'producto_id' => $itemcombo["producto_id"]
                            ]);
                        }
                    }
                }
            } else {
                $carshoop = Carshoop::create([
                    'date' => $date,
                    'cantidad' => $cantidad,
                    'pricebuy' => $serieProducto->producto->pricebuy,
                    'price' => $pricesaleNew,
                    'igv' => $igvsale,
                    'subtotal' => $pricesaleNew * $cantidad,
                    'total' => $pricesale * $cantidad,
                    'gratuito' => 0,
                    'status' => 0,
                    'promocion_id' => $promocion_id,
                    'producto_id' => $serieProducto->producto_id,
                    'almacen_id' => $serieProducto->almacen_id,
                    'moneda_id' => $this->moneda->id,
                    'user_id' => auth()->user()->id,
                    'sucursal_id' => $this->sucursal->id,
                    'alterstock' => Almacen::DISMINUIR_STOCK,
                    'cartable_type' => Venta::class,
                ]);

                if ($carshoopitems->count() > 0) {
                    foreach ($carshoopitems as $itemcombo) {
                        $carshoop->carshoopitems()->create([
                            'pricebuy' => $itemcombo["pricebuy"],
                            // 'price' => $itemcombo["price"],
                            // 'igv' => $itemcombo["igv"],
                            'price' => 0,
                            'igv' => 0,
                            'producto_id' => $itemcombo["producto_id"]
                        ]);
                    }
                }
            }

            $carshoop->carshoopseries()->create([
                'date' =>  $date,
                'serie_id' => $serieProducto->id,
                'user_id' => auth()->user()->id
            ]);

            if (!$existskardex) {
                $carshoop->saveKardexOutCarshoop($stock, $promocion_id);
            }

            $carshoop->updateStockAlmacen($stock, $cantidad);
            DB::commit();
            $this->reset(['searchserie']);
            $this->resetValidation();
            $datos =  response()->json(['mensaje' => 'AGREGADO CORRECTAMENTE', 'form_id' => NULL])->getData();
            $this->dispatchBrowserEvent('show-resumen-venta', $datos);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // public function addtocar($formData, Producto $producto)
    // {
    public function addtocar(Producto $producto, $cart)
    {

        $price = $cart['price'] ?? "";
        $serie = $cart['serie'];
        $cantidad = $cart['cantidad'] ?? 1;
        $promocion_id = null;
        $carshoopitems = collect([]);

        $this->cart[$producto->id] = $cart;
        $this->cart[$producto->id]["almacen_id"] = $this->almacen_id;
        $producto->load([
            'unit',
            'promocions' => function ($query) {
                $query->with(['itempromos.producto' => function ($query) {
                    $query->with('unit')->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderBy('default', 'desc')->limit(1);
                    }]);
                }])->availables()->disponibles()->take(1);
            }
        ]);

        $this->validate(
            [
                "cart.*.price" => [
                    'required',
                    'numeric',
                    'min:0',
                    'decimal:0,4',
                    'gt:0',
                ],
                "cart.*.almacen_id" => [
                    'required',
                    'integer',
                    'exists:almacens,id',
                    new ValidateStock($producto->id, $this->almacen_id)
                ],
                "cart.*.cantidad" => [
                    'required',
                    'numeric',
                    'min:1',
                    'integer',
                    new ValidateStock($producto->id, $this->almacen_id, $cantidad)
                ],
                "cart.$producto->id.serie" => [
                    'nullable',
                    Rule::requiredIf($producto->isRequiredserie()),
                    'string',
                    'min:4',
                ],
                "moneda.id" => [
                    'required',
                    'integer',
                    'min:1',
                    'exists:monedas,id'
                ],
            ],
            [],
            ["cart.$producto->id.serie" => 'serie']
        );

        $stock = $producto->almacens->find($this->almacen_id)->pivot->cantidad;
        $promocion = verifyPromocion($producto->promocions->first());
        $combo = $producto->getAmountCombo($promocion, $this->pricetype ?? null, $this->almacen_id);

        if ($stock && $stock > 0) {
            if (($stock - $cantidad) < 0) {
                $mensaje =  response()->json([
                    'title' => 'CANTIDAD SUPERA EL STOCK DISPONIBLE DEL PRODUCTO !',
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        } else {
            $mensaje =  response()->json([
                'title' => 'STOCK DEL PRODUCTO EN ALMACÉN AGOTADO !',
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        DB::beginTransaction();
        try {
            if ($promocion) {
                if ($promocion->limit > 0 && ($promocion->outs + $cantidad) > $promocion->limit) {
                    $mensaje =  response()->json([
                        'title' => "CANTIDAD SUPERA STOCK DISPONIBLE EN PROMOCIÓN [" . $promocion->limit - $promocion->outs . " " . $producto->unit->name . "] DISPONIBLES",
                        'text' => null
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                if ($promocion->limit > 0 && $promocion->outs >= $promocion->limit) {
                    $mensaje =  response()->json([
                        'title' => "STOCK DEL PRODUCTO EN PROMOCIÓN AGOTADO !",
                        'text' => "Límite de unidades en promoción del producto agotado."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                if ($combo) {
                    if (count($combo->products) > 0) {
                        foreach ($combo->products as $itemcombo) {
                            if (($itemcombo->stock - $cantidad) < 0) {
                                $mensaje =  response()->json([
                                    'title' => "CANTIDAD DEL PRODUCTO EN COMBO SUPERA EL STOCK DISPONIBLE EN ALMACÉN !",
                                    'text' => "La cantidad del producto $itemcombo->name ,supera stock disponible en almacén seleccionado."
                                ])->getData();
                                $this->dispatchBrowserEvent('validation', $mensaje);
                                return false;
                            }

                            $carshoopitems->push([
                                'date' => now('America/Lima'),
                                'pricebuy' => $itemcombo->pricebuy,
                                'price' => $itemcombo->price,
                                'stock' => $itemcombo->stock,
                                'newstock' => $itemcombo->stock - $cantidad,
                                'producto_id' => $itemcombo->producto_id
                            ]);

                            Producto::find($itemcombo->producto_id)->almacens()->updateExistingPivot($this->almacen_id, [
                                'cantidad' => $itemcombo->stock - $cantidad,
                            ]);
                        }
                    }
                }

                $promocion_id = $promocion->id;
                $promocion->outs = $promocion->outs + $cantidad;
                $promocion->save();
                if ($promocion->limit == $promocion->outs) {
                    $producto->assignPrice($promocion);
                }
            }

            $existskardex = false;
            $date = now('America/Lima');
            $pricesale = $this->empresa->isAfectacionIGV() ? getPriceIGV($price, $this->empresa->igv)->price : $price;
            $igvsale = $this->empresa->isAfectacionIGV() ? getPriceIGV($price, $this->empresa->igv)->igv : 0;
            $existsInCart = Carshoop::where('producto_id', $producto->id)
                ->where('almacen_id', $this->almacen_id)
                ->where('moneda_id', $this->moneda->id)
                ->where('promocion_id', $promocion_id)
                ->where('gratuito', '0')
                ->where('user_id', auth()->user()->id)
                ->where('sucursal_id', auth()->user()->sucursal_id)
                ->where('alterstock', Almacen::DISMINUIR_STOCK)
                ->where('cartable_type', Venta::class);



            if ($existsInCart->exists()) {
                $carshoop = $existsInCart->first();
                $carshoop->cantidad = $carshoop->cantidad + $cantidad;
                $carshoop->pricebuy = $producto->pricebuy;
                $carshoop->price = $pricesale;
                $carshoop->igv = $igvsale;
                $carshoop->subtotal =  $pricesale * $carshoop->cantidad;
                $carshoop->total = $price * $carshoop->cantidad;
                $carshoop->save();

                if ($carshoop->kardex) {
                    $existskardex = true;
                    $carshoop->kardex->cantidad = $carshoop->kardex->cantidad +  $cantidad;
                    $carshoop->kardex->newstock = $carshoop->kardex->newstock - $cantidad;
                    $carshoop->kardex->save();
                }

                if ($carshoopitems->count() > 0) {
                    if (count($carshoop->carshoopitems) == 0) {
                        foreach ($carshoopitems as $itemcombo) {
                            $carshoop->carshoopitems()->create([
                                'pricebuy' => $itemcombo["pricebuy"],
                                // 'price' => $itemcombo["price"],
                                'price' => 0,
                                'producto_id' => $itemcombo["producto_id"]
                            ]);
                        }
                    }
                }
            } else {
                $carshoop = Carshoop::create([
                    'date' => $date,
                    'cantidad' => $cantidad,
                    'pricebuy' => $producto->pricebuy,
                    'price' => $pricesale,
                    'igv' => $igvsale,
                    'subtotal' => $pricesale * $cantidad,
                    'total' => $price * $cantidad,
                    'gratuito' => 0,
                    'status' => 0,
                    'producto_id' => $producto->id,
                    'almacen_id' => $this->almacen_id,
                    'moneda_id' => $this->moneda->id,
                    'user_id' => auth()->user()->id,
                    'sucursal_id' => $this->sucursal->id,
                    'alterstock' => Almacen::DISMINUIR_STOCK,
                    'promocion_id' => $promocion_id,
                    'cartable_type' => Venta::class,
                ]);

                if ($carshoopitems->count() > 0) {
                    foreach ($carshoopitems as $itemcombo) {
                        $carshoop->carshoopitems()->create([
                            'pricebuy' => $itemcombo["pricebuy"],
                            // 'price' => $itemcombo["price"],
                            'price' => 0,
                            'igv' => 0,
                            'producto_id' => $itemcombo["producto_id"]
                        ]);
                    }
                }
            }

            if (!empty($serie)) {
                $serieProducto = Serie::disponibles()->where('serie', trim(mb_strtoupper($serie, "UTF-8")))->first();

                if ($serieProducto) {
                    if (Carshoopserie::where('serie_id', $serieProducto->id)->exists()) {
                        $mensaje =  response()->json([
                            'title' => 'SERIE YA SE ENCUENTRA REGISTRADO EN EL CARRITO !',
                            'text' => "La serie $serieProducto->serie ya se encuentra registrado en los items de la venta."
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }

                    if ($serieProducto->isDisponible()) {
                        $serieProducto->status = Serie::SALIDA;
                        $serieProducto->dateout = $date;
                        $serieProducto->save();
                    } else {
                        $mensaje =  response()->json([
                            'title' => 'SERIE NO SE ENCUENTRA DISPONIBLE !',
                            'text' => "La serie $serieProducto->serie ya no se encuentra disponible en este momento."
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }

                    $carshoop->carshoopseries()->create([
                        'date' =>  $date,
                        'serie_id' => $serieProducto->id,
                        'user_id' => auth()->user()->id
                    ]);
                } else {
                    $mensaje =  response()->json([
                        'title' => 'SERIE NO SE ENCUENTRA DISPONIBLE !',
                        'text' => null
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }
            }

            if (!$existskardex) {
                $carshoop->saveKardexOutCarshoop($stock, $promocion_id);
            }

            $carshoop->updateStockAlmacen($stock, $cantidad);
            $this->setTotal();
            // $producto->almacens()->updateExistingPivot($this->almacen_id, [
            //     'cantidad' => $stock - $cantidad,
            // ]);
            DB::commit();
            $this->reset(['searchserie']);
            $this->resetValidation();
            $datos =  response()->json(['mensaje' => 'AGREGADO CORRECTAMENTE', 'form_id' => $producto->id])->getData();
            $this->dispatchBrowserEvent('show-resumen-venta', $datos);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function limpiarcliente()
    {
        $this->reset(['client_id', 'document', 'name', 'direccion']);
    }

    public function savepay()
    {
        $this->validate([
            'amountparcial' => [
                'required',
                'numeric',
                'min:0',
                'gt:0',
                'decimal:0,4'
            ],
            'methodpayment_id' => [
                'required',
                'integer',
                'min:1',
                'exists:methodpayments,id',
            ],
        ]);

        $parcialamount = 0;
        if (count($this->parcialpayments)) {
            $collect = collect($this->parcialpayments);
            $parcialamount = $collect->sum('amount');
        }

        if (($parcialamount + $this->amountparcial) > number_format($this->total - ($this->gratuito + $this->amountincrement), 2, '.', '')) {
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
                'methodpayment_id' => $this->methodpayment_id,
                'method' => Methodpayment::find($this->methodpayment_id)->name,
            ];
        }

        $this->resetValidation();
        $this->reset(['amountparcial', 'methodpayment_id']);
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

        $results = Carshoop::select(
            DB::raw("COALESCE(SUM(total),0) as total"),
            DB::raw("COALESCE(SUM(CASE WHEN igv > 0 AND gratuito = '0' THEN igv * cantidad ELSE 0 END),0) as igv"),
            DB::raw("COALESCE(SUM(CASE WHEN igv > 0 AND gratuito = '1' THEN igv * cantidad ELSE 0 END), 0) as igvgratuito"),
            DB::raw("COALESCE(SUM(CASE WHEN igv > 0 AND gratuito = '0' THEN price * cantidad ELSE 0 END), 0) as gravado"),
            DB::raw("COALESCE(SUM(CASE WHEN igv = 0 AND gratuito = '0' THEN price * cantidad ELSE 0 END), 0) as exonerado"),
            DB::raw("COALESCE(SUM(CASE WHEN gratuito = '1' THEN price * cantidad ELSE 0 END), 0) as gratuitos")
        )->ventas()->where('user_id', auth()->user()->id)->where('sucursal_id', auth()->user()->sucursal_id)->get();
        // dd($results[0]);

        if ($this->typepay == '1') {
            $collect = collect($this->parcialpayments);
            $this->paymentactual = $collect->sum('amount') ?? 0;
        } else {
            $this->paymentactual = 0;
        }

        // SE LE INCREMENTA AL MONTO PENDIENTE PAGO, NO AL TOTAL
        $saldopagar = number_format($results[0]->total - ($results[0]->gratuitos + $results[0]->igvgratuito + $this->paymentactual), 3, '.', '');
        // TOTAL = ESTÁ INCLUY. IGV+ GRAB+EXO+DESC+GRATUI+INCREM.
        $this->amountincrement = number_format($saldopagar * $this->increment / 100, 3, '.', '');

        $this->igv = number_format($results[0]->igv, 3, '.', '');
        $this->igvgratuito = number_format($results[0]->igvgratuito, 3, '.', '');
        $this->gratuito =  number_format($results[0]->gratuitos, 3, '.', '');
        $this->exonerado = number_format($results[0]->exonerado, 3, '.', '');
        $this->gravado = number_format($results[0]->gravado, 3, '.', '');

        if ($this->empresa->isAfectacionIGV()) {
            $this->gravado += getPriceIGV($this->amountincrement, $this->empresa->igv)->price;
            $this->igv +=  getPriceIGV($this->amountincrement, $this->empresa->igv)->igv;
        } else {
            $this->exonerado += $this->amountincrement;
        }

        $this->total = number_format($results[0]->total + $this->amountincrement, 3, '.', '');
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

    public function save()
    {

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

        $carshoops = Carshoop::with(['carshoopseries', 'producto', 'almacen'])
            ->ventas()->where('user_id', auth()->user()->id)
            ->where('sucursal_id', auth()->user()->sucursal_id)
            ->orderBy('id', 'asc')->get();
        $this->items = $carshoops;

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

            if ($this->typepayment->isContado() && number_format($parcialamount, 2, '.', '') <> number_format($this->total - $this->gratuito, 2, '.', '')) {
                $mensaje =  response()->json([
                    'title' => "MONTO PARCIAL (" . number_format($parcialamount, 2, '.', ', ') . ") DIFERENTE AL TOTAL PAGAR DE VENTA (" . number_format($this->total - $this->gratuito, 2, '.', ', ') . ") !",
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        }

        if ($this->typepayment->isCredito() && $this->paymentactual >= ($this->total - $this->gratuito)) {
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
            $client->direccions()->updateOrCreate(['name' => $this->direccion]);
            $totalcomboGRA = 0;
            $totalIGVcomboGRA = 0;

            $venta = Venta::create([
                'date' => now('America/Lima'),
                'seriecompleta' => $seriecomprobante->serie . '-' . $numeracion,
                'direccion' => $this->direccion,
                'exonerado' => number_format($this->exonerado, 34, '.', ''),
                'gravado' => number_format($this->gravado, 3, '.', ''),
                'gratuito' => number_format($this->gratuito, 3, '.', ''),
                'inafecto' => number_format($this->inafecto, 3, '.', ''),
                'descuento' => number_format($this->descuentos, 3, '.', ''),
                'otros' => number_format($this->otros, 3, '.', ''),
                'igv' => number_format($this->igv, 3, '.', ''),
                'igvgratuito' => number_format($this->igvgratuito, 3, '.', ''),
                'subtotal' => number_format($this->gravado + $this->exonerado + $this->inafecto, 3, '.', ''),
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
                        'PAGO PARCIAL VENTA'
                    );
                }
            } else {
                $venta->savePayment(
                    $this->sucursal->id,
                    $this->total - ($this->gratuito + $this->igvgratuito),
                    $this->total - ($this->gratuito + $this->igvgratuito),
                    null,
                    $this->moneda_id,
                    $this->methodpayment_id,
                    MovimientosEnum::INGRESO->value,
                    $this->concept->id,
                    $this->openbox->id,
                    $this->monthbox->id,
                    $seriecomprobante->serie . '-' . $numeracion,
                    null
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
            $totalAmountCuotas = number_format($this->total - ($this->gratuito + $this->paymentactual), 3, '.', '');
            // $amountCuota = number_format($totalAmountCuotas / $this->countcuotas, 3, '.', '');
            $percentPay = number_format($this->paymentactual * 100 / ($this->total - ($this->gratuito + $this->amountincrement)), 3, '.', '');
            $percentItem = number_format($this->increment - ($this->increment * $percentPay / 100), 3, '.', '');
            // dd($percentPay, $percentItem);

            foreach ($carshoops as $item) {
                if ($percentItem > 0) {
                    $item->price = getPriceIncrement($item->price, $percentItem);
                    $item->igv = getPriceIncrement($item->igv, $percentItem);
                    $item->subtotal = $item->price * $item->cantidad;
                    $item->total = ($item->price + $item->igv) * $item->cantidad;
                }

                // dd($carritoSum->countnogratuitos, $carritoSum->total, $totalSinIncrement, $priceIncrItem, $porcentajeIncr);
                // $subtotalItem = number_format($item->cantidad * $item->price, 3, '.', '');
                // $subtotalItemIGV = number_format($item->cantidad *  $item->igv, 3, '.', '');
                // $totalItem = number_format($subtotalItemIGV + $subtotalItem, 3, '.', '');

                $newTvitem = [
                    'date' => now('America/Lima'),
                    'cantidad' => $item->cantidad,
                    'pricebuy' => $item->pricebuy,
                    'price' => number_format($item->price, 3, '.', ''),
                    'igv' => number_format($item->igv, 3, '.', ''),
                    'subtotaligv' => number_format($item->igv * $item->cantidad, 3, '.', ''),
                    'subtotal' => number_format($item->subtotal, 3, '.', ''),
                    'total' => number_format($item->total, 3, '.', ''),
                    'status' => 0,
                    'alterstock' => $item->alterstock,
                    'gratuito' => $item->gratuito,
                    'increment' => $percentItem,
                    'promocion_id' => $item->promocion_id,
                    'almacen_id' => $item->almacen_id,
                    'producto_id' => $item->producto_id,
                    'user_id' => auth()->user()->id
                ];

                // dd($newTvitem);

                $tvitem = $venta->tvitems()->create($newTvitem);
                if ($item->kardex) {
                    $item->kardex->detalle = Kardex::SALIDA_VENTA;
                    $item->kardex->reference = $seriecomprobante->serie . '-' . $numeracion;
                    $item->kardex->kardeable_id = $tvitem->id;
                    $item->kardex->kardeable_type = Tvitem::class;
                    $item->kardex->save();
                }

                if ($this->incluyeguia) {
                    $tvitemguia = $guia->tvitems()->create($newTvitem);
                }

                if (count($item->carshoopseries) > 0) {
                    foreach ($item->carshoopseries as $carshoopserie) {
                        $newTvserieitem = [
                            'date' => now('America/Lima'),
                            'serie_id' => $carshoopserie->serie_id,
                            'user_id' => auth()->user()->id,
                        ];

                        $tvitem->itemseries()->create($newTvserieitem);
                        if ($this->incluyeguia) {
                            $tvitemguia->itemseries()->create($newTvserieitem);
                        }
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
                    if ($seriecomprobante->typecomprobante->sendsunat) {
                        $comprobante->facturableitems()->create([
                            'item' => $counter,
                            'descripcion' => $item->producto->name,
                            'code' => $item->producto->code,
                            'cantidad' => $item->cantidad,
                            'price' => number_format($item->price, 3, '.', ''),
                            'igv' => number_format($item->igv, 3, '.', ''),
                            'subtotaligv' => number_format($item->igv * $item->cantidad, 3, '.', ''),
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

                if (count($item->carshoopitems) > 0) {
                    // $countercombo = $counter;
                    foreach ($item->carshoopitems as $carshoopitem) {
                        $stockCombo = $carshoopitem->producto->almacens->find($item->almacen_id)->pivot->cantidad;

                        $subtotalItemCombo = number_format($item->cantidad * $carshoopitem->price, 3, '.', '');
                        $subtotalItemIGVCombo = number_format($item->cantidad * $carshoopitem->igv, 3, '.', '');
                        $totalItemCombo = number_format($subtotalItemCombo + $subtotalItemIGVCombo, 3, '.', '');
                        $totalIGVcomboGRA = $totalIGVcomboGRA + $subtotalItemIGVCombo;
                        $totalcomboGRA = $totalcomboGRA + $subtotalItemCombo;

                        $itemcombo = [
                            'date' => now('America/Lima'),
                            'cantidad' => $item->cantidad,
                            'pricebuy' => $carshoopitem->pricebuy,
                            // 'price' => number_format($carshoopitem->price, 3, '.', ''),
                            // 'igv' => number_format($carshoopitem->igv, 3, '.', ''),
                            // 'subtotaligv' => number_format($subtotalItemIGVCombo, 3, '.', ''),
                            // 'subtotal' => number_format($subtotalItemCombo, 3, '.', ''),
                            // 'total' => number_format($totalItemCombo, 3, '.', ''),
                            'price' => 0,
                            'igv' => 0,
                            'subtotaligv' => 0,
                            'subtotal' => 0,
                            'total' => 0,
                            'status' => 0,
                            'alterstock' => Almacen::DISMINUIR_STOCK,
                            'gratuito' => Tvitem::GRATUITO,
                            'increment' => 0,
                            'promocion_id' => $item->promocion_id,
                            'almacen_id' => $item->almacen_id,
                            'producto_id' => $carshoopitem->producto_id,
                            'user_id' => auth()->user()->id
                        ];

                        $tvitem = $venta->tvitems()->create($itemcombo);
                        if ($this->incluyeguia) {
                            $tvitemguia = $guia->tvitems()->create($itemcombo);
                        }

                        if (Module::isEnabled('Facturacion')) {
                            if ($seriecomprobante->typecomprobante->sendsunat) {
                                $comprobante->facturableitems()->create([
                                    'item' => $counter,
                                    'descripcion' => $carshoopitem->producto->name,
                                    'code' => $carshoopitem->producto->code,
                                    'cantidad' => $item->cantidad,
                                    // 'price' => number_format($carshoopitem->price, 3, '.', ''),
                                    // 'igv' => number_format($carshoopitem->igv, 3, '.', ''),
                                    // 'subtotaligv' => number_format($subtotalItemIGVCombo, 3, '.', ''),
                                    // 'subtotal' => number_format($subtotalItemCombo, 3, '.', ''),
                                    // 'total' => number_format($totalItemCombo, 3, '.', ''),
                                    'price' => 0,
                                    'igv' => 0,
                                    'subtotaligv' => 0,
                                    'subtotal' => 0,
                                    'total' => 0,
                                    'unit' => $item->producto->unit->code,
                                    'codetypeprice' => '02',
                                    'afectacion' => $item->igv > 0 ? '15' : '21',
                                    'codeafectacion' => '9996',
                                    'nameafectacion' => 'GRA',
                                    'typeafectacion' => 'FRE',
                                    'abreviatureafectacion' => 'Z',
                                    'percent' => $carshoopitem->igv > 0 ? $this->empresa->igv : 0,
                                ]);
                            }
                        }

                        // GUARDAMOS EN KARDEX YA QUE CUANDO AGREGAMOS AL CARRITO
                        // SOLAMENTE DESCUENTA STOCK MAS NO REGISTRA EN KARDEX
                        // LE AUMENTO EL STOCK EN KARDEX PORQUE SE SUPONE QUE HUBO 
                        // EN ALMACEN EL STOCK ACTUAL MAS LO DESCONTADO Y ESTOCK ES EL ACTUAL
                        $tvitem->saveKardex(
                            $carshoopitem->producto_id,
                            $item->almacen_id,
                            $stockCombo + $item->cantidad,
                            $stockCombo,
                            $item->cantidad,
                            Almacen::SALIDA_ALMACEN,
                            Kardex::SALIDA_COMBO_VENTA,
                            $seriecomprobante->serie . '-' . $numeracion,
                            $item->promocion_id,
                        );

                        $carshoopitem->delete();
                        $counter++;
                    }

                    if ($totalcomboGRA > 0 || $totalIGVcomboGRA > 0) {
                        $venta->gratuito = number_format($venta->gratuito + $totalcomboGRA, 4, '.', '');
                        $venta->igvgratuito = number_format($venta->igvgratuito + $totalIGVcomboGRA, 4, '.', '');
                        $venta->save();

                        if (Module::isEnabled('Facturacion')) {
                            if ($seriecomprobante->typecomprobante->sendsunat) {
                                $comprobante->gratuito = number_format($comprobante->gratuito + $totalcomboGRA, 4, '.', '');
                                $comprobante->igvgratuito = number_format($comprobante->igvgratuito + $totalIGVcomboGRA, 4, '.', '');
                                $comprobante->save();
                            }
                        }
                    }
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
            Carshoop::with(['carshoopseries'])->ventas()->where('user_id', auth()->user()->id)
                ->where('sucursal_id', auth()->user()->sucursal_id)->each(function ($carshoop) {
                    $carshoop->carshoopseries()->delete();
                    $carshoop->delete();
                });
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
                'modalidadtransporte'
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

    public function updategratis(Carshoop $carshoop)
    {
        $carshoop->gratuito = $carshoop->gratuito == 1 ? 0 : 1;
        $carshoop->save();
        $this->setTotal();
    }

    public function deleteserie(Carshoopserie $carshoopserie)
    {
        DB::beginTransaction();
        try {
            if ($carshoopserie->carshoop->isDiscountStock() || $carshoopserie->carshoop->isReservedStock()) {
                $carshoopserie->serie->dateout = null;
                $carshoopserie->serie->status = 0;
                $carshoopserie->serie->save();
                $carshoopserie->carshoop->cantidad = $carshoopserie->carshoop->cantidad - 1;
                $carshoopserie->carshoop->subtotal = $carshoopserie->carshoop->price * $carshoopserie->carshoop->cantidad;
                $carshoopserie->carshoop->total = $carshoopserie->carshoop->price * $carshoopserie->carshoop->cantidad;
                $carshoopserie->carshoop->save();

                $stock = $carshoopserie->carshoop->producto->almacens->find($carshoopserie->carshoop->almacen_id)->pivot->cantidad;
                $carshoopserie->carshoop->producto->almacens()->updateExistingPivot($carshoopserie->carshoop->almacen_id, [
                    'cantidad' => $stock + 1,
                ]);
            }

            if ($carshoopserie->carshoop->kardex) {
                $carshoopserie->carshoop->kardex->cantidad = $carshoopserie->carshoop->kardex->cantidad - 1;
                $carshoopserie->carshoop->kardex->newstock = $carshoopserie->carshoop->kardex->newstock + 1;
                $carshoopserie->carshoop->kardex->save();
            }

            $carshoopserie->delete();
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

    public function getGRE()
    {

        $this->searchgre = mb_strtoupper(trim($this->searchgre), "UTF-8");
        $this->validate([
            'searchgre' => ['required', 'string', 'min:6', 'max:13']
        ]);

        DB::beginTransaction();
        try {
            $guia = Guia::with(['tvitems', 'client', 'guiable'])->where('seriecompleta', $this->searchgre)
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

                    $tvitems = $guia->tvitems()->with(['producto', 'almacen', 'itemseries'])->where('alterstock', Almacen::DISMINUIR_STOCK)->get();
                    if (count($tvitems) > 0) {
                        $this->sincronizegre = true;
                        $this->guiaremision = $guia;
                        $this->document = $guia->client->document;
                        $this->name = $guia->client->name;
                        if (count($guia->client->direccions) > 0) {
                            $this->direccion = $guia->client->direccions()->first()->name;
                        }

                        $this->confirmaradditemsventa($tvitems);
                        $this->dispatchBrowserEvent('toast', toastJSON('Items de GRE agregados correctamente'));
                        $this->setTotal();
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

        foreach ($tvitems as $item) {
            $date = now('America/Lima');
            // $promocion = $item->producto->getPromocionDisponible();
            // $combo = $item->producto->getAmountCombo($promocion, $this->pricetype ?? null, $this->almacen_id);
            $pricesale = $item->producto->obtenerPrecioVenta($this->pricetype ?? null);

            if ($this->moneda->isDolar()) {
                $pricesale = convertMoneda($pricesale, 'USD', $this->empresa->tipocambio, 2);
            } else {
                $pricesale = formatDecimalOrInteger($pricesale, 2);
            }

            $carshoop = Carshoop::create([
                'date' => $date,
                'cantidad' =>  $item->cantidad,
                'pricebuy' => $item->producto->pricebuy,
                'price' => $pricesale,
                'igv' => 0,
                'subtotal' => $pricesale * $item->cantidad,
                'total' => $pricesale * $item->cantidad,
                'gratuito' => 0,
                'status' => 0,
                'producto_id' => $item->producto_id,
                'almacen_id' => $item->almacen_id,
                'moneda_id' => $this->moneda_id,
                'user_id' => auth()->user()->id,
                'sucursal_id' => $this->sucursal->id,
                'mode' => Almacen::NO_ALTERAR_STOCK,
                'cartable_type' => Venta::class,
            ]);

            if (count($item->itemseries) > 0) {
                foreach ($item->itemseries as $itemserie) {
                    if (Carshoopserie::where('serie_id', $itemserie->serie_id)->exists()) {
                        $mensaje =  response()->json([
                            'title' => 'SERIE YA SE ENCUENTRA AGREGADO EN LOS ITEMS !',
                            'text' => null //"La serie " . $itemserie->serie->serie . " ya se encuentra registrado en los items de la Guía."
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }

                    $carshoop->carshoopseries()->create([
                        'date' =>  $date,
                        'serie_id' => $itemserie->serie_id,
                        'user_id' => auth()->user()->id
                    ]);
                }
            }
        }
    }

    public function desvinculargre()
    {
        $this->reset(['searchgre', 'sincronizegre', 'guiaremision']);
    }

    public function getClient()
    {

        $this->document = trim($this->document);
        $this->validate([
            'document' => [
                'required',
                'numeric',
                new ValidateDocument,
                'regex:/^\d{8}(?:\d{3})?$/',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.document' : ''),
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->document);

        if ($response->getData()) {
            if ($response->getData()->success) {
                // dd($response->getData());
                $this->resetValidation(['client_id', 'document', 'name', 'direccion']);
                $this->name = $response->getData()->name;
                $this->client_id = $response->getData()->client_id;
                if (!empty($response->getData()->direccion)) {
                    $this->direccion = $response->getData()->direccion;
                }

                if ($this->empresa->usarLista()) {
                    if ($response->getData()->pricetype_id) {
                        $this->pricetypeasigned = $response->getData()->pricetypeasigned;
                        // $this->pricetype = $pricetype;
                        $this->pricetype_id = $response->getData()->pricetype_id;
                    }
                }

                if ($response->getData()->birthday) {
                    $this->dispatchBrowserEvent('birthday', $response->getData()->name);
                }
            } else {
                $this->resetValidation(['document']);
                $this->addError('document', $response->getData()->message);
            }
        }
    }

    public function getTransport()
    {

        $this->ructransport = trim($this->ructransport);
        $this->validate([
            'ructransport' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
                'numeric',
                'digits:11',
                'regex:/^\d{11}$/',
                $this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.document' : '',
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->ructransport);
        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->resetValidation(['ructransport', 'nametransport']);
                $this->nametransport = $response->getData()->name;
            } else {
                $this->resetValidation(['ructransport']);
                $this->addError('ructransport', $response->getData()->message);
            }
        } else {
            dd($response);
        }
    }

    public function getDestinatario()
    {

        $this->documentdestinatario = trim($this->documentdestinatario);
        $this->validate([
            'documentdestinatario' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia),
                'numeric',
                'regex:/^\d{8}(?:\d{3})?$/',
                $this->incluyeguia && $this->motivotraslado->code == '03' ? 'different:document' : '',
                // 'different:document',
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->documentdestinatario);
        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->resetValidation(['documentdestinatario', 'namedestinatario']);
                $this->namedestinatario = $response->getData()->name;
            } else {
                $this->resetValidation(['documentdestinatario']);
                $this->addError('documentdestinatario', $response->getData()->message);
            }
        } else {
            dd($response);
        }
    }

    public function getDriver()
    {

        $this->documentdriver = trim($this->documentdriver);
        $this->validate([
            'documentdriver' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02'),
                'numeric',
                'regex:/^\d{8}(?:\d{3})?$/'
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->documentdriver, false);
        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->resetValidation(['documentdriver', 'namedriver']);
                $this->namedriver = $response->getData()->name;
            } else {
                $this->resetValidation(['documentdriver']);
                $this->addError('documentdriver', $response->getData()->message);
            }
        } else {
            dd($response);
        }
    }
}
