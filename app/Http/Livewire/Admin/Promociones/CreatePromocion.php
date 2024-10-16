<?php

namespace App\Http\Livewire\Admin\Promociones;

use App\Models\Producto;
use App\Models\Promocion;
use App\Rules\ValidatePrincipalCombo;
use App\Rules\ValidateSecondaryCombo;
use App\Rules\ValidateStockCombo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreatePromocion extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $agotarstock = false;

    public $producto;
    public $producto_id, $almacen_id, $type, $typecombo, $startdate, $expiredate;
    public $limit;

    public $pricebuy;
    public $limitstock = 0;
    public $limitstocksec = 0;
    public $descuento;

    public $productosec, $productosec_id, $cantidad;
    public $itempromos = [];

    protected function rules()
    {
        return [
            'producto_id' => [
                'required',
                'integer',
                'min:1',
                'exists:productos,id',
                new ValidatePrincipalCombo($this->producto_id, $this->type),
            ],
            'pricebuy' => [
                'required',
                'numeric',
                'decimal:0,4',
            ],
            'limit' => ['nullable', Rule::requiredIf(!$this->agotarstock), 'numeric', 'min:1',  'max:' . $this->limitstock, 'decimal:0,2',],
            'startdate' => [
                'nullable',
                'date',
                'after_or_equal:' . now('America/Lima')->format('Y-m-d')
            ],
            'expiredate' => [
                'nullable',
                Rule::requiredIf(!empty($this->startdate)),
                'date',
                'after_or_equal:' . now('America/Lima')->format('Y-m-d'),
                'after_or_equal:startdate'
            ],
            'type' => ['required', 'integer', 'min:0', 'max:2'],
            'itempromos' => ['required', 'array', 'min:1', new ValidateStockCombo($this->producto_id, $this->limit)],
        ];
    }

    public function mount()
    {
        // $this->productosec = new Producto();
    }

    public function render()
    {
        $productos = Producto::query()->select('id', 'name', 'slug')->whereHas('almacens')->orderBy('name', 'asc')->get();
        return view('livewire.admin.promociones.create-promocion', compact('productos'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->resetExcept(['open']);
            Session::forget('combo');
            $comboCollect = getCombo();

            $comboitems = collect($comboCollect->get('comboitems') ?? []);
            $newscomboitems = $comboitems->push([
                'producto_id' => 34,
                'name' => 'PRODUCTO GRATIS',
                'category' => 'XXX',
                'descuento' => null
            ]);

            // dd($newscomboitems->all());
            // $comboCollect = $comboCollect->merge([
            //     'comboitems' => $newscomboitems,
            // ]);

            // $comboCollect = $comboCollect->merge([
            //     'comboitems' => [
            //         ['producto_id' => 401],
            //     ]
            // ]);
            // dd($comboCollect);
            $comboJSON = response()->json($comboCollect)->getData();
            Session::put('combo', $comboJSON);
            $this->resetValidation();
        }
    }

    public function updatedProductoId($value)
    {
        $this->reset(['producto', 'pricebuy']);
        if (trim($value) !== '') {
            $this->producto = Producto::query()->select('id', 'name', 'pricesale', 'unit_id', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5')
                ->with(['almacens', 'unit'])->addSelect(['image' => function ($query) {
                    $query->select('url')->from('images')->whereColumn('images.imageable_id', 'productos.id')
                        ->where('images.imageable_type', Producto::class)
                        ->orderBy('default', 'desc')->limit(1);
                }])->withCount([
                    'almacens as stock' => function ($query) {
                        $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)'));
                    }
                ])->find($value)->toArray();
            $this->limitstock = $this->producto['stock'];
            $this->pricebuy = number_format(mi_empresa()->usarlista() ? $this->producto['pricebuy'] : $this->producto['pricesale'], 3, '.', '');
        } else {
            $this->reset(['limit', 'limitstock', 'pricebuy']);
        }
    }

    public function updatedProductosecId($value)
    {
        $this->reset(['productosec']);
        if (trim($value) !== '') {
            $this->productosec = Producto::query()->select('id', 'name', 'pricesale', 'unit_id', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5')
                ->with(['almacens', 'unit'])->addSelect(['image' => function ($query) {
                    $query->select('url')->from('images')->whereColumn('images.imageable_id', 'productos.id')
                        ->where('images.imageable_type', Producto::class)
                        ->orderBy('default', 'desc')->limit(1);
                }])->withCount([
                    'almacens as stock' => function ($query) {
                        $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)'));
                    }
                ])->find($value)->toArray();
        }
    }

    public function confirmar()
    {

        $this->authorize('admin.promociones.create');

        if (!empty($this->producto_id)) {
            $this->limitstock =  formatDecimalOrInteger(Producto::find($this->producto_id)->almacens()->sum('cantidad'));
            $this->limit = $this->agotarstock ? $this->limitstock : $this->limit;
        }

        $this->validate([
            'producto_id' => [
                'required',
                'integer',
                'min:1',
                'exists:productos,id',
                new ValidatePrincipalCombo($this->producto_id, $this->type),
            ],
            'pricebuy' => ['required', 'numeric', 'decimal:0,4'],
            'limit' => [
                'nullable',
                Rule::requiredIf(!$this->agotarstock),
                'numeric',
                'min:1',
                'max:' . $this->limitstock,
                'decimal:0,2'
            ],
            'limitstock' => ['required', 'numeric', 'min:0', 'gt:0'],
            'startdate' => [
                'nullable',
                'date',
                'after_or_equal:' . now('America/Lima')->format('Y-m-d')
            ],
            'expiredate' => [
                'nullable',
                Rule::requiredIf(!empty($this->startdate)),
                'date',
                'after_or_equal:' . now('America/Lima')->format('Y-m-d'),
                'after_or_equal:startdate'
            ],
            'type' => ['required', 'integer', 'min:0', 'max:2'],
            'descuento' => ['nullable', 'required_if:type,' . Promocion::DESCUENTO, 'numeric', 'min:0', 'gt:0', 'max:100', 'decimal:0,2'],
        ], ['descuento.required_if' => 'El campo :attribute es obligatorio cuando la promoción es un descuento.'],);

        $promocion = [
            'producto_id' => $this->producto_id,
            'pricebuy' => $this->pricebuy,
            'limit' => $this->limit,
            'descuento' => $this->descuento,
            'unit' => $this->producto['unit']['name'],
            'startdate' => !empty(trim($this->startdate)) ? $this->startdate :  null,
            'expiredate' => !empty(trim($this->expiredate)) ? $this->expiredate : null,
            'type' => $this->type,
            'comboitems' => [],
        ];

        if ($this->type == Promocion::COMBO) {
            $comboCollect = collect($promocion);
            $comboJSON = response()->json($comboCollect)->getData();
            Session::put('combo', $comboJSON);
        } else {
            $promocion = Promocion::create($promocion);
            $promocion->producto->load(['promocions' => function ($query) {
                $query->with(['itempromos.producto' => function ($query) {
                    $query->with('unit')->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderBy('default', 'desc')->limit(1);
                    }]);
                }])->availables()->disponibles()->take(1);
            }]);
            $promocion->producto->assignPrice();
            $this->reset();
            $this->dispatchBrowserEvent('created');
            $this->emitTo('admin.promociones.show-promociones', 'render');
        }
        $this->resetValidation();
    }

    public function save()
    {
        $this->authorize('admin.promociones.create');
        $this->itempromos = getCombo()->get('comboitems');
        $validateData = $this->validate();

        DB::beginTransaction();
        try {
            $promocion = Promocion::create($validateData);
            foreach ($this->itempromos as $item) {
                $promocion->itempromos()->create([
                    'producto_id' => $item->producto_id,
                    'descuento' => $item->descuento,
                    'typecombo' => $item->typecombo,
                ]);
            }
            DB::commit();
            $promocion->producto->load(['promocions' => function ($query) {
                $query->with(['itempromos.producto' => function ($query) {
                    $query->with('unit')->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderBy('default', 'desc')->limit(1);
                    }]);
                }])->availables()->disponibles()->take(1);
            }]);
            $promocion->producto->assignPrice();
            $this->emitTo('admin.promociones.show-promociones', 'render');
            $this->resetValidation();
            $this->dispatchBrowserEvent('created');
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function add()
    {
        $this->authorize('admin.promociones.create');

        if (!empty($this->producto_id)) {
            $this->limitstock = Producto::query()->select('id', 'name')->withCount([
                'almacens as stock' => function ($query) {
                    $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)'));
                }
            ])->find($this->producto_id)->stock;
        }

        if (!empty($this->productosec_id)) {
            $this->limitstocksec = Producto::query()->select('id', 'name')->withCount([
                'almacens as stock' => function ($query) {
                    $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)'));
                }
            ])->find($this->productosec_id)->stock;
        }

        $this->validate([
            'producto_id' => [
                'required',
                'integer',
                'min:1',
                'exists:productos,id',
                new ValidatePrincipalCombo($this->producto_id, $this->type)
            ],
            'productosec_id' => [
                'required',
                'integer',
                'min:1',
                'exists:productos,id',
                'different:producto_id',
                new ValidateSecondaryCombo()
            ],
            'limit' => ['nullable', Rule::requiredIf(!$this->agotarstock), 'numeric', 'min:1', 'max:' . $this->limitstock, 'decimal:0,2'],
            'limitstock' => ['required', 'numeric', 'min:0', 'gt:0'],
            'limitstocksec' => ['required', 'numeric', 'min:0', 'gt:0'],
            'typecombo' => ['required', 'numeric', 'integer', 'min:0', 'max:2'],
            'descuento' => ['nullable', 'required_if:typecombo,1', 'numeric', 'min:1', 'max:100', 'decimal:0,2']
        ]);

        if ($this->limit > 0) {
            $stockitem = Producto::query()->select('id', 'name')->withCount([
                'almacens as stock' => function ($query) {
                    $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)'));
                }
            ])->find($this->productosec_id)->stock;
            if ($stockitem < $this->limit) {
                $this->addError('productosec_id', 'Stock del producto no disponible [' . $stockitem . ' UND].');
                return false;
            }
        }

        // $producto = Producto::with('category')->find($this->productosec_id);
        $comboCollect = getCombo();
        $comboitems = collect($comboCollect->get('comboitems') ?? []);
        $filtered = $comboitems->filter(function ($item) {
            return $item->producto_id == $this->productosec_id;
        });

        if ($filtered->count() > 0) {
            $this->addError('productosec_id', 'Producto ya se encuentra agregado.');
            return false;
        }

        $newscomboitems = $comboitems->push([
            'producto_id' => $this->productosec_id,
            'name' => $this->productosec['name'],
            'image' => $this->productosec['image'],
            'descuento' => $this->descuento ?? null,
            'typecombo' => $this->typecombo
        ]);

        $comboCollect = $comboCollect->merge([
            'comboitems' => $newscomboitems,
        ]);
        // dd($comboCollect);

        $comboJSON = response()->json($comboCollect)->getData();
        Session::put('combo', $comboJSON);
        $this->resetValidation();
        $this->reset(['productosec_id', 'productosec', 'descuento', 'typecombo']);
    }

    public function deleteitem($id)
    {
        $this->authorize('admin.promociones.create');

        $comboCollect = getCombo();
        $comboitems = collect($comboCollect->get('comboitems') ?? []);
        $newscomboitems = $comboitems->reject(function ($item, $key) use ($id) {
            return $item->producto_id == $id;
        });

        $comboCollect = $comboCollect->merge([
            'comboitems' => $newscomboitems->values(),
        ]);

        // dd($comboCollect, $newscomboitems->values());
        $comboJSON = response()->json($comboCollect)->getData();
        Session::put('combo', $comboJSON);
    }

    public function  cancelcombo()
    {
        $this->authorize('admin.promociones.create');
        Session::forget('combo');
        $this->resetValidation();
        $this->resetExcept('open');
    }
}
