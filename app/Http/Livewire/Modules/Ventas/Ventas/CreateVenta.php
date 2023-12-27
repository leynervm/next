<?php

namespace App\Http\Livewire\Modules\Ventas\Ventas;

use App\Helpers\GetPrice;
use App\Models\Category;
use App\Models\Empresa;
use App\Models\Moneda;
use App\Models\Pricetype;
use App\Models\Serie;
use App\Models\Sucursal;
use App\Rules\ValidateSerieRequerida;
use App\Rules\ValidateStock;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Almacen;
use App\Models\Producto;
use Nwidart\Modules\Facades\Module;

class CreateVenta extends Component
{

    use WithPagination;

    public $empresa, $sucursal, $pricetype, $moneda;
    public $open = false;
    public $disponibles = 1;
    public $readyToLoad = false;
    public $almacendefault;
    public $producto_id, $serie_id, $almacen_id, $pricetype_id;

    public $search = '';
    public $searchserie = '';
    public $searchalmacen = '';
    public $searchcategory = '';
    public $cart = [];
    public $seriescarrito = [];

    protected $listeners = ['render'];

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'producto'],
        'searchcategory' => ['except' => '', 'as' => 'categoria'],
    ];

    protected $messages = [
        'cart.*.almacen_id.required' => 'Almacen del producto requerido',
        'cart.*.price.required' => 'Precio del producto requerido',
        'cart.*.price.min' => 'Precio del producto deber se mayor a 0.0001',
        'cart.*.cantidad.required' => 'Cantidad del producto requerido',
        'cart.*.cantidad.min' => 'Cantidad del producto debe ser mayor a 0',
        'cart.*.serie.required' => 'Serie del producto requerido',
    ];

    public function mount(Empresa $empresa, Sucursal $sucursal, Pricetype $pricetype, Moneda $moneda)
    {
        $this->empresa = $empresa;
        $this->sucursal = $sucursal;
        $this->pricetype = $pricetype;
        $this->moneda = $moneda;
        $this->pricetype_id = $pricetype->id ?? null;

        $carritoSesion = Session::get('carrito', []);
        if (!is_array($carritoSesion)) {
            $carritoSesion = json_decode($carritoSesion, true);
        }
        $this->seriescarrito = $this->getseriecarrito($carritoSesion);

        // if (Module::isEnabled('Almacen')) {
        if ($sucursal->almacens()->exists()) {
            if ($sucursal->almacens()->count() == 1) {
                $this->almacendefault = $sucursal->almacens()->first();
                $this->almacen_id = $this->almacendefault->id;
            } else {
                $this->almacen_id = auth()->user()->sucursalDefault()->first()->pivot->almacen_id ?? null;
                if ($this->almacen_id) {
                    $this->almacendefault = Almacen::find($this->almacen_id);
                }
            }
        }
        // }
    }

    public function loadProductos()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {

        $almacens = [];
        if ($this->almacen_id) {
            $almacens[] = $this->almacen_id;
        }

        if ($this->readyToLoad) {
            // $miproducto = Producto::with(['almacens' => function ($query) use ($almacens) {
            //     $query->whereIn('almacens.id', $almacens)
            //         ->where('cantidad', '>', 0);
            // }])->find(39);

            $productos = Producto::withWhereHas('almacens', function ($query) use ($almacens) {
                $query->whereIn('almacens.id', $almacens);
                if ($this->disponibles) {
                    $query->where('cantidad', '>', 0);
                }
            })->with('seriesdisponibles', function ($query) use ($almacens) {
                $query->whereIn('almacen_id', $almacens);
            });

            if (trim($this->search) !== "") {
                $productos->where('name', 'ilike', $this->search . '%');
            }

            if (trim($this->searchcategory) !== "") {
                $productos->where('category_id', $this->searchcategory);
            }

            $productos = $productos->orderBy('name', 'asc')->paginate();

            // dd($productos);

        } else {
            $productos = [];
            // $miproducto = Producto::find(39);
        }

        $categories = Category::orderBy('name', 'asc')->get();
        $pricetypes = Pricetype::orderBy('name', 'asc')->get();

        // if (Module::isEnabled('Almacen')) {
        $almacens = Almacen::orderBy('name', 'asc')->get();
        // } else {
        //     $almacens = [];
        // }

        return view('livewire.modules.ventas.ventas.create-venta', compact('productos', 'pricetypes', 'categories', 'almacens'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSearchcategory()
    {
        $this->resetPage();
        $this->resetValidation();
    }

    public function updatedAlmacenId($value)
    {
        $this->resetPage();
        $this->resetValidation();
        if ($value) {
            $this->almacendefault = Almacen::find($value);
        }
    }

    public function setPricetypeId(Pricetype $pricetype)
    {
        $this->pricetype = $pricetype;
        $this->pricetype_id = $pricetype->id;
    }

    public function setMoneda(Moneda $moneda)
    {
        $this->moneda = $moneda;
        $this->render();
    }

    public function getProductoBySerie()
    {

        $this->searchserie = trim($this->searchserie);
        $this->validate([
            'almacen_id' => ['required', 'integer', 'min:1', 'exists:almacens,id'],
            'searchserie' => ['required', 'string', 'min:4',
                Rule::exists('series', 'serie')->where('almacen_id', $this->almacen_id)->where('status', 0)
            ],
            'moneda.id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
        ]);

        $serie = Serie::where('serie', trim(mb_strtoupper($this->searchserie, "UTF-8")))
            ->where('almacen_id', $this->almacen_id)
            ->where('status', 0)->first();

        if ($serie) {
            $this->validate([
                'searchserie' => [
                    new ValidateStock($serie->producto_id, $this->almacen_id, 1)
                ],
            ]);

            $precios = GetPrice::getPriceProducto($serie->producto, $this->pricetype_id)->getData();

            if ($precios->success) {
                $nuevoItem = [
                    'producto_id' => $serie->producto_id,
                    'producto' => $serie->producto->name,
                    'cantidad' => 1,
                    'price' => $precios->pricewithdescount,
                    'pricebuy' => $serie->producto->pricebuy,
                    'importe' => $precios->pricewithdescount,
                    'sucursal_id' => $this->sucursal->id,
                    'sucursal' => $this->sucursal->name,
                    'user_id' => auth()->user()->id,
                    'almacen_id' => $serie->almacen_id,
                    'almacen' => $serie->almacen->name,
                    'unit' => $serie->producto->unit->name,
                    'moneda_id' => $this->moneda->id,
                    'simbolo' => $this->moneda->simbolo,
                    'moneda' => $this->moneda->currency,
                    'gratuito' => 0
                ];

                $nuevaSerie = [
                    'id' => $serie->id,
                    'serie' => $serie->serie
                ];

                $this->additemcarrito($nuevoItem, $nuevaSerie, true);
            } else {
                $this->addError('searchserie', $precios->error);
            }
        } else {
            $this->addError('searchserie', 'No encontrararon registros de series.');
        }
    }

    public function addtocar($formData, Producto $producto)
    {

        $price = $formData["price"] ?? "";
        $serie = $formData["serie"] ?? null;
        $cantidad = $formData["cantidad"] ?? 1;

        $this->cart[$producto->id]["price"] = $price;
        $this->cart[$producto->id]["almacen_id"] = $this->almacen_id;
        $this->cart[$producto->id]["serie"] = $serie;
        $this->cart[$producto->id]["cantidad"] = $cantidad;

        $validateDate = $this->validate([
            "cart.$producto->id.price" => [
                'required', 'numeric', 'decimal:0,4', 'min:0.0001'
            ],
            "cart.$producto->id.almacen_id" => [
                'required', 'integer', 'exists:almacens,id',
                new ValidateStock($producto->id, $this->almacen_id)
            ],
            "cart.$producto->id.cantidad" => [
                'required', 'numeric', 'min:1', 'integer',
                new ValidateStock($producto->id, $this->almacen_id, $cantidad)
            ],
            "cart.$producto->id.serie" => [
                'nullable',
                Rule::requiredIf($producto->seriesdisponibles()->where('almacen_id', $this->almacen_id)->exists()),
                'string', 'min:4',
                new ValidateSerieRequerida($producto->id, $this->almacen_id, $serie),
            ],
            "moneda.id" => [
                'required', 'integer', 'min:1', 'exists:monedas,id'
            ],
        ]);

        $nuevaSerie = [];
        $nuevoItem = [
            'producto_id' => $producto->id,
            'producto' => $producto->name,
            'cantidad' => $cantidad,
            'price' => $price,
            'pricebuy' => $producto->pricebuy,
            'importe' => $price * $cantidad,
            'sucursal_id' => $this->sucursal->id,
            'sucursal' => $this->sucursal->name,
            'user_id' => auth()->user()->id,
            'almacen_id' => $this->almacen_id,
            'almacen' => $this->almacendefault->name,
            'unit' => $producto->unit->name,
            'moneda_id' => $this->moneda->id,
            'simbolo' => $this->moneda->simbolo,
            'moneda' => $this->moneda->currency,
            'gratuito' => 0
        ];


        $serieProducto = Serie::where('serie', trim(mb_strtoupper($serie, "UTF-8")))->first();
        if ($serieProducto) {
            $nuevaSerie = [
                'id' => $serieProducto->id,
                'serie' => $serieProducto->serie
            ];
        }

        $this->additemcarrito($nuevoItem, $nuevaSerie);
    }

    public function additemcarrito($nuevoItem, $nuevaSerie = [], $byserie = false)
    {

        $carritoSesion = Session::get('carrito', []);
        if (!is_array($carritoSesion)) {
            $carritoSesion = json_decode($carritoSesion, true);
        }

        $producto_id = $nuevoItem['producto_id'];
        $id = rand();
        $nuevoItem['id'] = $id;
        $seriescarrito = $this->getseriecarrito($carritoSesion);
        $productExists = false;
        foreach ($carritoSesion as $key => $item) {
            if (
                $item['producto_id'] == $nuevoItem['producto_id'] && $item['almacen_id'] == $nuevoItem['almacen_id']
                && $item['sucursal_id'] == $nuevoItem['sucursal_id'] && $item['moneda_id'] == $nuevoItem['moneda_id']
                && $item['gratuito'] == $nuevoItem['gratuito']
            ) {

                $productExists = true;
                $carritoSesion[$key]['cantidad'] += $nuevoItem['cantidad'];
                $carritoSesion[$key]['importe'] = $carritoSesion[$key]['cantidad'] * $carritoSesion[$key]['price'];

                if (count($nuevaSerie) > 0) {
                    if (count($carritoSesion[$key]['series']) > 0) {
                        $serieExistente = in_array($nuevaSerie['id'], $seriescarrito);
                        if ($serieExistente) {
                            $this->addError($byserie ? "searchserie" : "cart.$producto_id.serie", 'La serie ya se encuentra agregado al carrito');
                            return false;
                        } else {
                            $carritoSesion[$key]['series'][] = $nuevaSerie;
                        }
                    } else {
                        $carritoSesion[$key]['series'][] = $nuevaSerie;
                    }
                }
            }
        }

        if (!$productExists) {
            count($nuevaSerie) > 0 ? $nuevoItem['series'][] = $nuevaSerie : $nuevoItem['series'] = $nuevaSerie;
            $carritoSesion[$id] = $nuevoItem;
        }

        $carritoJSON = response()->json($carritoSesion)->getContent();
        Session::put('carrito', $carritoJSON);
        $seriescarrito = $this->getseriecarrito($carritoSesion);
        $this->seriescarrito = $seriescarrito;

        $this->reset(['searchserie']);
        $this->resetValidation();
        // $this->emitTo('ventas::ventas.show-resumen-venta', 'render');
        $this->dispatchBrowserEvent('show-resumen-venta');
        $this->dispatchBrowserEvent('reset-form', $producto_id);
        $this->dispatchBrowserEvent('created');
    }

    public function getseriecarrito($carrito)
    {
        $arraySeries = [];

        if (count($carrito) > 0) {
            foreach ($carrito as $key => $item) {
                if (count($carrito[$key]['series']) > 0) {
                    foreach ($carrito[$key]['series'] as $serie) {
                        array_push($arraySeries, $serie['id']);
                    }
                }
            }
        }

        return $arraySeries;
    }
}
