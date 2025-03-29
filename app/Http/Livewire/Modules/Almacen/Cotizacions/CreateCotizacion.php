<?php

namespace App\Http\Livewire\Modules\Almacen\Cotizacions;

use App\Models\Almacen;
use App\Models\Cotizacion;
use App\Models\Empresa;
use App\Models\Moneda;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Tvitem;
use App\Models\Typegarantia;
use App\Models\Typepayment;
use App\Models\Ubigeo;
use App\Models\Unit;
use App\Rules\ValidateDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateCotizacion extends Component
{

    public $addadress = false;
    public $empresa;
    public $document = '', $name = '', $client_id, $validez;
    public $pricetype, $pricetype_id, $moneda_id, $ubigeo_id, $direccioninstalacion,
        $direccion, $producto, $producto_id, $timeentrega, $datecode, $detalle;
    public $pricesale, $cantidad, $afectacionigv, $typepayment_id;
    public $typegarantia_id, $timegarantia, $datecodegarantia;
    public $productofree_id, $cantidadfree;
    public $nameother, $marcaother, $cantidadother, $priceother;
    public $itemcotizacions = [], $garantias = [], $ofertas = [], $others = [];
    public $subtotal = 0, $igv = 0, $total = 0;

    protected function rules()
    {
        return [
            'document' => [
                'required',
                'numeric',
                new ValidateDocument,
                'regex:/^\d{8}(?:\d{3})?$/',
                'exists:clients,document'
            ],
            'name' => ['required', 'string', 'min:3',],
            'direccion' => ['required', 'string', 'min:6',],
            'ubigeo_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'afectacionigv' => ['required'],
            'timeentrega' => ['required', 'integer', 'min:1'],
            'datecode' => ['required', 'string', 'in:D,M,Y'],
            'validez' => ['required', 'date', 'date_format:Y-m-d'],
            'typepayment_id' => ['required', 'integer', 'min:1', 'exists:typepayments,id'],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'client_id' => ['required', 'integer', 'min:1', 'exists:clients,id'],
            'direccioninstalacion' => ['nullable', Rule::requiredIf($this->addadress), 'string', 'min:6'],
            'ubigeo_id' => ['nullable', Rule::requiredIf($this->addadress), 'integer', 'min:1', 'exists:ubigeos,id'],
            'itemcotizacions' => ['nullable', 'array',],
            'garantias' => ['nullable', 'array',],
            'ofertas' => ['nullable', 'array',],
            'others' => ['nullable', 'array',],
            'addadress' => ['nullable', 'boolean',],
            'detalle' => ['nullable', 'string',],
        ];
    }

    public function mount()
    {
        $this->datecode = 'D';
        $this->empresa = view()->shared('empresa');
        $this->typepayment_id = Typepayment::activos()->orderByDesc('default')
            ->orderBy('id', 'asc')->first()->id ?? null;

        if ($this->empresa->usarLista()) {
            $pricetype = Pricetype::activos()->orderByDesc('default')
                ->orderBy('id', 'asc')->first();

            if ($pricetype) {
                $this->pricetype = $pricetype;
                $this->pricetype_id = $pricetype->id;
            }
        }
    }

    public function render()
    {
        $monedas = Moneda::orderBy('id', 'asc')->get();
        $typegarantias = Typegarantia::orderBy('id', 'asc')->get();
        $pricetypes = Pricetype::activos()->orderBy('id', 'asc')->get();
        $typepayments = Typepayment::activos()->orderByDesc('default')
            ->orderBy('id', 'asc')->get();
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        $productos = Producto::query()->select(
            'productos.id',
            'productos.name',
            'marca_id',
            'category_id',
            'subcategory_id',
            'visivility',
            'novedad',
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
        )->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->leftJoin('subcategories', 'productos.subcategory_id', '=', 'subcategories.id')
            ->leftJoin('categories', 'productos.category_id', '=', 'categories.id')
            ->with(['almacens', 'imagen'])->visibles()->orderByDesc('novedad')
            ->orderBy('subcategories.orden', 'ASC')
            ->orderBy('categories.orden', 'ASC')->get();

        return view('livewire.modules.almacen.cotizacions.create-cotizacion', compact('productos', 'monedas', 'pricetypes', 'ubigeos', 'typepayments', 'typegarantias'));
    }

    public function searchcliente()
    {
        $this->resetValidation();
        $this->document = trim($this->document);
        $this->validate(['document' => ['required', 'numeric', new ValidateDocument, 'regex:/^\d{8}(?:\d{3})?$/',]]);

        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->document,
            'autosaved' =>  true,
            'savedireccions' =>  true,
            'searchbd' => true,
            'obtenerlista' => true,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());
            if (isset($cliente->success) && $cliente->success) {
                $this->name = $cliente->name;
                $this->direccion = $cliente->direccion;
                if ($this->empresa->usarLista() && $cliente->pricetype) {
                    $this->pricetype_id = $cliente->pricetype->id;
                    $this->pricetype = Pricetype::find($cliente->pricetype->id);
                }
                if (isset($cliente->id)) {
                    $this->client_id = $cliente->id;
                }
                if ($cliente->birthday) {
                    $this->dispatchBrowserEvent('birthday', $cliente->name);
                }
            } else {
                $this->name = '';
                $this->direccion = '';
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

    public function limpiarcliente()
    {
        $this->resetValidation();
        $this->reset(['client_id', 'document', 'name', 'direccion']);
    }

    public function getPricesale()
    {
        if (!empty($this->producto_id) && !empty($this->pricetype_id)) {
            $this->producto = Producto::with(['imagen', 'unit'])->find($this->producto_id);
            $this->pricesale = getPrecioventa($this->producto, $this->pricetype);
        } else {
            $this->pricesale = '';
        }
    }

    public function updatedPricetypeId($value)
    {
        if (!empty($value)) {
            $this->pricetype = Pricetype::find($value);
            Self::getPricesale();
        }
    }

    public function updatedProductoId()
    {
        Self::getPricesale();
    }

    public function addproducto()
    {
        $this->validate([
            'afectacionigv' => ['required', 'integer', 'in:0,1'],
            'pricesale' => ['required', 'numeric', 'min:1', 'decimal:0,4'],
            'cantidad' => ['required', 'numeric', 'min:1', 'decimal:0,2'],
            'producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
        ]);

        // if ($this->moneda->isDolar()) {
        //     $pricesale = convertMoneda($pricesale, 'USD', $this->empresa->tipocambio, 2);
        // } else {
        //     $pricesale = decimalOrInteger($pricesale, 2);
        // }

        $pricesale = $this->pricesale;
        $igvsale = 0;
        $subtotaligv = 0;

        if ($this->afectacionigv == '1') {
            $price_igv = getPriceIGV($pricesale, $this->empresa->igv);
            $pricesale = $price_igv->price;
            $igvsale = $price_igv->igv;
            $subtotaligv = number_format($igvsale * $this->cantidad, 2, '.', '');
        }

        $subtotal = number_format($pricesale * $this->cantidad, 2, '.', '');
        $total = ($pricesale + $igvsale) * $this->cantidad;

        $item = [
            'id' => uniqid(),
            // 'producto' => $this->producto,
            'producto_id' => $this->producto_id,
            'name' => $this->producto->name,
            'cantidad' => $this->cantidad,
            'pricebuy' => $this->producto->pricebuy,
            'pricesale' => $pricesale,
            'igv' => $igvsale,
            'subtotaligv' => $subtotaligv,
            'subtotal' => $subtotal,
            'total' => $total,
            'pricebuysoles' => 0,
            'imagen' => $this->producto->imagen->url ?? null,
            'unit' => $this->producto->unit->name ?? null,
        ];
        $this->itemcotizacions[] = $item;
        Self::resetproducto();
        Self::updateTotal();
    }

    public function edititem($uniqid)
    {
        $collect = collect($this->itemcotizacions ?? []);
        $item = $collect->firstWhere('id', $uniqid);

        if ($item) {
            $this->producto_id = $item['producto_id'];
            $this->pricesale = $item['pricesale'] + $item['igv'];
            $this->cantidad = $item['cantidad'];
            $this->producto = Producto::with(['imagen', 'unit'])->find($this->producto_id);
            $this->itemcotizacions = $collect->reject(fn($item) => $item['id'] == $uniqid)->values()->toArray();
            Self::updateTotal();
        }
        $this->resetValidation();
    }

    public function removeitem($uniqid)
    {
        $collect = collect($this->itemcotizacions ?? []);
        $item = $collect->firstWhere('id', $uniqid);

        if ($item) {
            $this->itemcotizacions = $collect->reject(fn($item) => $item['id'] == $uniqid)->values()->toArray();
            Self::updateTotal();
        }
        $this->resetValidation();
    }

    public function resetproducto()
    {
        $this->reset(['producto', 'producto_id', 'pricesale', 'cantidad']);
        $this->resetValidation();
    }

    public function addgarantia()
    {
        $this->validate([
            'timegarantia' => ['required', 'integer', 'min:1'],
            'datecodegarantia' => ['required', 'string', 'in:D,M,Y'],
            'typegarantia_id' => ['required', 'integer', 'min:1', 'exists:typegarantias,id'],
        ]);

        $collect = collect($this->garantias ?? []);
        $item = $collect->firstWhere('typegarantia_id', $this->typegarantia_id);

        if ($item) {
            $this->addError('typegarantia_id', 'El tipo de garantía ya se encuentra agregado.');
            return false;
        }

        $typegarantia = Typegarantia::find($this->typegarantia_id);
        $this->garantias[] = [
            'id' => uniqid(),
            'name' => $typegarantia->name,
            'time' => $this->timegarantia,
            'datecode' => $this->datecodegarantia,
            'typegarantia_id' => $this->typegarantia_id,
        ];
        Self::resetgarantia();
    }

    public function editgarantia($uniqid)
    {
        $collect = collect($this->garantias ?? []);
        $item = $collect->firstWhere('id', $uniqid);

        if ($item) {
            $this->typegarantia_id = $item['typegarantia_id'];
            $this->timegarantia = $item['time'];
            $this->datecodegarantia = $item['datecode'];
            $this->garantias = $collect->reject(fn($item) => $item['id'] == $uniqid)->values()->toArray();
        }
    }

    public function deletegarantia($uniqid)
    {
        $collect = collect($this->garantias ?? []);
        $item = $collect->firstWhere('id', $uniqid);

        if ($item) {
            $this->garantias = $collect->reject(fn($item) => $item['id'] == $uniqid)->values()->toArray();
        }
    }

    public function resetgarantia()
    {
        $this->reset(['typegarantia_id', 'timegarantia', 'datecodegarantia']);
        $this->resetValidation();
    }

    public function addproductofree()
    {
        $this->validate([
            'productofree_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'cantidadfree' => ['required', 'numeric', 'min:1', 'decimal:0,2'],
        ]);

        $producto = Producto::with(['imagen', 'unit'])->find($this->productofree_id);
        $item = [
            'id' => uniqid(),
            'producto_id' => $this->productofree_id,
            'name' => $producto->name,
            'cantidad' => $this->cantidadfree,
            'pricebuy' => $producto->pricebuy,
            'pricesale' => 0,
            'igv' => 0,
            'subtotaligv' => 0,
            'subtotal' => 0,
            'total' => 0,
            'pricebuysoles' => 0,
            'imagen' => $producto->imagen->url ?? null,
            'unit' => $producto->unit->name ?? null,
        ];
        $this->ofertas[] = $item;
        $this->reset(['productofree_id', 'cantidadfree']);
    }

    public function edititemfree($uniqid)
    {
        $collect = collect($this->ofertas ?? []);
        $item = $collect->firstWhere('id', $uniqid);

        if ($item) {
            $this->productofree_id = $item['producto_id'];
            $this->cantidadfree = $item['cantidad'];
            $this->ofertas = $collect->reject(fn($item) => $item['id'] == $uniqid)->values()->toArray();
        }
        $this->resetValidation();
    }

    public function removeitemfree($uniqid)
    {
        $collect = collect($this->ofertas ?? []);
        $item = $collect->firstWhere('id', $uniqid);

        if ($item) {
            $this->ofertas = $collect->reject(fn($item) => $item['id'] == $uniqid)->values()->toArray();
        }
        $this->resetValidation();
    }

    public function updateTotal()
    {
        $items = collect($this->itemcotizacions) ?? [];
        $igv = number_format($items->sum('subtotaligv'), 2, '.', '');
        $subtotal = number_format($items->sum('subtotal'), 2, '.', '');
        $total = number_format($items->sum('total'), 2, '.', '');

        $others = collect($this->others) ?? [];
        $igvothers = number_format($others->sum('subtotaligv'), 2, '.', '');
        $subtotalothers = number_format($others->sum('subtotal'), 2, '.', '');
        $totalothers = number_format($others->sum('total'), 2, '.', '');

        $this->igv = $igv + $igvothers;
        $this->subtotal = $subtotal + $subtotalothers;
        $this->total = $total + $totalothers;
    }

    public function save()
    {
        if (count($this->itemcotizacions) == 0 && count($this->others) == 0) {
            $mensaje = response()->json([
                'title' => 'NO SE HAN AGREGADO REGISTROS A COTIZAR'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        $validateData = $this->validate();

        DB::beginTransaction();
        try {
            // $collect = collect($this->itemcotizacions) ?? [];
            // $igv = number_format($collect->sum('subtotaligv'), 2, '.', '');
            // $subtotal = number_format($collect->sum('subtotal'), 2, '.', '');
            // $total = number_format($collect->sum('total'), 2, '.', '');
            // $total = number_format(array_sum(array_column($this->itemcotizacions, 'subtotalitem')), 2, '.', '');
            $max = 1 + Cotizacion::max('id');
            $sucursal = auth()->user()->sucursal;
            $serie = Cotizacion::SERIE . substr(trim($sucursal->codeanexo), -3);

            do {
                $seriecompleta = $serie .  '-' . now('America/Lima')->year . '-' . $max;
            } while (DB::table('cotizacions')->where('sucursal_id', $sucursal->id)
                ->where('seriecompleta', $seriecompleta)->exists()
            );

            $cotizacion = Cotizacion::create([
                'date' => now('America/Lima'),
                'seriecompleta' => $seriecompleta,
                'entrega' => $this->timeentrega,
                'datecode' => $this->datecode,
                'validez' => $this->validez,
                'direccion' => $this->direccion,
                'igv' => $this->igv,
                'subtotal' => $this->subtotal,
                'total' => $this->total,
                'afectacionigv' => $this->afectacionigv,
                'moneda_id' => $this->moneda_id,
                'client_id' => $this->client_id,
                'user_id' => auth()->user()->id,
                'sucursal_id' => auth()->user()->sucursal_id,
                'typepayment_id' => $this->typepayment_id,
                'detalle' => !empty($this->detalle) ? $this->detalle : null,
            ]);

            if ($this->addadress) {
                $lugar = $cotizacion->lugar()->firstOrCreate([
                    'name' => $this->direccioninstalacion,
                    'ubigeo_id' => $this->ubigeo_id,
                    // 'default' => $item['principal'] ? Direccion::DEFAULT : 0,
                ]);
            }

            if (count($this->garantias)) {
                foreach ($this->garantias as $item) {
                    $cotizacion->cotizaciongarantias()->create([
                        'time' => $item['time'],
                        'datecode' => $item['datecode'],
                        'typegarantia_id' => $item['typegarantia_id'],
                    ]);
                }
            }

            if (count($this->itemcotizacions)) {
                foreach ($this->itemcotizacions as $item) {
                    $cotizacion->tvitems()->create([
                        'date' => now('America/Lima'),
                        'pricebuy' => $item['pricebuy'],
                        'cantidad' => $item['cantidad'],
                        'price' => $item['pricesale'],
                        'igv' => $item['igv'],
                        'subtotaligv' => $item['subtotaligv'],
                        'subtotal' => $item['subtotal'],
                        'total' => $item['total'],
                        'alterstock' => Almacen::NO_ALTERAR_STOCK,
                        'gratuito' => Tvitem::NO_GRATUITO,
                        'user_id' => auth()->user()->id,
                        'producto_id' => $item['producto_id'],
                    ]);
                }
            }

            if (count($this->others)) {
                foreach ($this->others as $item) {
                    $cotizacion->otheritems()->create([
                        'date' => now('America/Lima'),
                        'name' => $item['name'],
                        'marca' => $item['marca'],
                        'pricebuy' => $item['pricebuy'],
                        'cantidad' => $item['cantidad'],
                        'price' => $item['pricesale'],
                        'igv' => $item['igv'],
                        'subtotaligv' => $item['subtotaligv'],
                        'subtotal' => $item['subtotal'],
                        'total' => $item['total'],
                        'gratuito' => Tvitem::NO_GRATUITO,
                        'user_id' => auth()->user()->id,
                        'unit_id' => $item['unit_id'],
                    ]);
                }
            }

            if (count($this->ofertas)) {
                foreach ($this->ofertas as $item) {
                    $cotizacion->tvitems()->create([
                        'date' => now('America/Lima'),
                        'pricebuy' => $item['pricebuy'],
                        'cantidad' => $item['cantidad'],
                        'price' => $item['pricesale'],
                        'igv' => $item['igv'],
                        'subtotaligv' => $item['subtotaligv'],
                        'subtotal' => $item['subtotal'],
                        'total' => $item['total'],
                        'alterstock' => Almacen::NO_ALTERAR_STOCK,
                        'gratuito' => Tvitem::GRATUITO,
                        'user_id' => auth()->user()->id,
                        'producto_id' => $item['producto_id'],
                    ]);
                }
            }

            DB::commit();
            $this->dispatchBrowserEvent('created');
            $this->resetExcept(['empresa', 'pricetype', 'pricetype_id']);
            $this->resetValidation();
            return redirect()->route('admin.cotizacions.edit', $cotizacion);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function addotherproducto()
    {
        $this->nameother = trim(mb_strtoupper($this->nameother, "UTF-8"));
        $this->marcaother = !empty($this->marcaother) ? trim(mb_strtoupper($this->marcaother, "UTF-8")) : null;
        $this->validate([
            'afectacionigv' => ['required', 'integer', 'in:0,1'],
            'nameother' => ['required', 'string', 'min:3',],
            'cantidadother' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'priceother' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
            'marcaother' => ['nullable', 'string', 'min:2',],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
        ]);

        $collect = collect($this->others ?? []);
        $item = $collect->firstWhere('name', $this->nameother);

        if ($item) {
            $this->addError('nameother', 'Item agregado con la misma descripción.');
            return false;
        }

        $pricesale = $this->priceother;
        $igvsale = 0;
        $subtotaligv = 0;

        if ($this->afectacionigv == '1') {
            $price_igv = getPriceIGV($pricesale, $this->empresa->igv);
            $pricesale = $price_igv->price;
            $igvsale = $price_igv->igv;
            $subtotaligv = number_format($igvsale * $this->cantidadother, 2, '.', '');
        }

        $subtotal = number_format($pricesale * $this->cantidadother, 2, '.', '');
        $total = ($pricesale + $igvsale) * $this->cantidadother;

        $item = [
            'id' => uniqid(),
            'name' => $this->nameother,
            'marca' => $this->marcaother,
            'cantidad' => $this->cantidadother,
            'pricebuy' => 0,
            'pricesale' => $pricesale,
            'igv' => $igvsale,
            'subtotaligv' => $subtotaligv,
            'subtotal' => $subtotal,
            'total' => $total,
            'pricebuysoles' => 0,
            'imagen' => null,
            'unit' => 'UND',
            'unit_id' => Unit::where('code', 'NIU')->first()->id,
        ];
        $this->others[] = $item;
        $this->resetValidation();
        $this->reset(['nameother', 'cantidadother', 'priceother', 'marcaother']);
        $this->dispatchBrowserEvent('toast', toastJSON('AGREGADO CORRECTAMENTE'));
        Self::updateTotal();
    }

    public function edititemother($uniqid)
    {
        $collect = collect($this->others ?? []);
        $item = $collect->firstWhere('id', $uniqid);

        if ($item) {
            $this->nameother = $item['name'];
            $this->cantidadother = $item['cantidad'];
            $this->marcaother = $item['marca'];
            $this->priceother = $item['pricesale'] + $item['igv'];
            $this->others = $collect->reject(fn($item) => $item['id'] == $uniqid)->values()->toArray();
            Self::updateTotal();
        }
        $this->resetValidation();
    }

    public function removeitemother($uniqid)
    {
        $collect = collect($this->others ?? []);
        $item = $collect->firstWhere('id', $uniqid);

        if ($item) {
            $this->others = $collect->reject(fn($item) => $item['id'] == $uniqid)->values()->toArray();
            $this->dispatchBrowserEvent('deleted');
            Self::updateTotal();
        }
        $this->resetValidation();
    }

    public function resetotherproducto()
    {
        $this->resetValidation();
        $this->reset(['nameother', 'cantidadother', 'priceother', 'marcaother']);
    }
}
