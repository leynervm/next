<?php

namespace App\Http\Livewire\Modules\Marketplace\Productos;

use App\Enums\PromocionesEnum;
use App\Models\Category;
use App\Models\Empresa;
use App\Models\Especificacion;
use App\Models\Marca;
use App\Models\Moneda;
use App\Models\Producto;
use App\Models\Subcategory;
use CodersFree\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProductos extends Component
{

    use WithPagination;

    protected $queryString = [
        'searchcategorias' => [
            'except' => '',
            'as' => 'categorias'
        ],
        'searchsubcategorias' => [
            'except' => '',
            'as' => 'subcategorias'
        ],
        'searchmarcas' => [
            'except' => '',
            'as' => 'marcas'
        ],
        'searchespecificaciones' => [
            'except' => '',
            'as' => 'especificaciones'
        ],
        'search' => [
            'except' => '',
            'as' => 'coincidencias'
        ],
        'filterselected' => [
            'except' => 'name_asc',
            'as' => 'ordenar'
        ]
    ];

    public Empresa $empresa;
    public Moneda $moneda;

    public $moneda_id, $pricetype, $pricetype_id;
    public $view = '';
    public $qty = 1;

    public $producto;
    public $search = '', $searchcategorias = '', $searchsubcategorias = '',
        $searchmarcas = '', $searchespecificaciones = '';
    public $selectedcategorias = [];
    public $selectedsubcategorias = [];
    public $selectedmarcas = [];
    public $selectedespecificacions = [];
    public $especificacions = [];
    public $stock_locals = [];
    public $matches = [];
    public $subcategories = [];
    public $marcas = [];

    public $readyToLoad = false;

    public $orderfilters = [];
    public $filterselected = '';
    public $open = false;

    public function loadProductos()
    {
        $this->readyToLoad = true;
    }

    public function mount($pricetype = null)
    {
        $this->pricetype = $pricetype;
        $this->producto = new Producto();
        if (!empty(request('categorias'))) {
            $this->selectedcategorias = explode(',', request('categorias'));
            $this->subcategories = Subcategory::whereHas('categories', function ($query) {
                $query->whereIn('categories.slug', $this->selectedcategorias);
            })->orderBy('orden', 'asc')->get();
        } else {
            $this->marcas = Marca::query()->select('id', 'name', 'slug')->whereHas('productos', function ($query) {
                $query->visibles()->publicados();
                if ($this->empresa->viewOnlyDisponibles()) {
                    $query->whereHas('almacens', function ($query) {
                        $query->where('cantidad', '>', 0);
                    });
                }
            })->orderBy('name', 'asc')->get();
        }
        if (!empty(request('subcategorias'))) {
            $this->selectedsubcategorias = explode(',', request('subcategorias'));
        }
        if (!empty(request('marcas'))) {
            $this->selectedmarcas = explode(',', request('marcas'));
        }
        if (!empty(request('especificaciones'))) {
            $this->selectedespecificacions = explode(',', request('especificaciones'));
        }
        if (!empty(request('coincidencias'))) {
            $this->search = request('coincidencias');
        }
        if (empty(request('order-by'))) {
            $this->filterselected = 'name_asc';
        }

        if (count($this->selectedsubcategorias) > 0) {
            $this->marcas = Marca::query()->select('id', 'name', 'slug')->whereHas('productos', function ($query) {
                $query->whereHas('subcategory', function ($subcategoryQuery) {
                    $subcategoryQuery->whereIn('slug', $this->selectedsubcategorias);
                })->visibles()->publicados();
                if ($this->empresa->viewOnlyDisponibles()) {
                    $query->whereHas('almacens', function ($query) {
                        $query->where('cantidad', '>', 0);
                    });
                }
            })->orderBy('name', 'asc')->get();

            $this->especificacions = Especificacion::withWhereHas('caracteristica', function ($query) {
                $query->filterweb()->orderBy('orden', 'asc');
            })->whereHas('productos', function ($query) {
                $query->whereHas('subcategory', function ($subQuery) {
                    $subQuery->whereIn('slug', $this->selectedsubcategorias);
                })->visibles()->publicados();
                if ($this->empresa->viewOnlyDisponibles()) {
                    $query->whereHas('almacens', function ($query) {
                        $query->where('cantidad', '>', 0);
                    });
                }
            })->get()->groupBy('caracteristica.name')->map(function ($especificaciones) {
                return $especificaciones->mapWithKeys(function ($especificacion) {
                    return [
                        $especificacion->slug => [
                            'slug' => $especificacion->slug,
                            'name'  => $especificacion->name
                        ]
                    ];
                });
            })->toArray();
        } else {
            if (count($this->selectedcategorias) > 0) {
                $this->marcas = Marca::query()->select('id', 'name', 'slug')->whereHas('productos', function ($query) {
                    $query->whereHas('category', function ($categoryQuery) {
                        $categoryQuery->whereIn('slug', $this->selectedcategorias);
                    })->visibles()->publicados();
                    if ($this->empresa->viewOnlyDisponibles()) {
                        $query->whereHas('almacens', function ($query) {
                            $query->where('cantidad', '>', 0);
                        });
                    }
                })->orderBy('name', 'asc')->get();
            } else {
                $this->marcas = Marca::query()->select('id', 'name', 'slug')->whereHas('productos', function ($query) {
                    $query->visibles()->publicados();
                    if ($this->empresa->viewOnlyDisponibles()) {
                        $query->whereHas('almacens', function ($query) {
                            $query->where('cantidad', '>', 0);
                        });
                    }
                })->orderBy('name', 'asc')->get();
            }
        }

        $column_price = $this->empresa->usarLista() ? $this->pricetype->campo_table ?? 'pricesale' : 'pricesale';
        $this->orderfilters = [
            'precio_asc' => [
                'column' => $column_price,
                'order' => 'asc',
                'text' => 'DE MENOR A MAYOR PRECIO',
                'visible' => true
            ],
            'precio_desc' => [
                'column' => $column_price,
                'order' => 'desc',
                'text' => 'DE MAYOR A MENOR PRECIO',
                'visible' => true
            ]
        ];
    }

    public function render()
    {
        $categories = Category::query()->select('id', 'slug', 'name')->whereHas('productos', function ($query) {
            $query->visibles()->publicados();
            if ($this->empresa->viewOnlyDisponibles()) {
                $query->whereHas('almacens', function ($query) {
                    $query->where('cantidad', '>', 0);
                });
            }
        })->orderBy('orden', 'asc')->get();

        $productos = Producto::query()->select(
            'productos.id',
            'productos.name',
            'productos.slug',
            'marca_id',
            'category_id',
            'subcategory_id',
            'unit_id',
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
            ->with(['unit', 'almacens' => function ($query) {
                $query->where('cantidad', '>', 0);
            }]);

        if ($this->empresa->viewOnlyDisponibles()) {
            $productos->whereHas('almacens', function ($query) {
                $query->where('cantidad', '>', 0);
            });
        }

        $productos->withCount(['almacens as stock' => function ($query) {
            $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)')); // Suma de la cantidad en la tabla pivote
        }])->addSelect(['image' => function ($query) {
            $query->select('url')->from('images')
                ->whereColumn('images.imageable_id', 'productos.id')
                ->where('images.imageable_type', Producto::class)
                ->orderByDesc('default')->limit(1);
        }])->addSelect(['image_2' => function ($query) {
            $query->select('url')->from('images')
                ->whereColumn('images.imageable_id', 'productos.id')
                ->where('images.imageable_type', Producto::class)
                ->orderByDesc('default')->offset(1)->limit(1);
        }]);

        if (count($this->selectedcategorias) > 0) {
            $productos->whereIn('categories.slug', $this->selectedcategorias);
        }

        if (count($this->selectedsubcategorias) > 0) {
            $productos->whereIn('subcategories.slug', $this->selectedsubcategorias);
        }

        if (count($this->selectedmarcas) > 0) {
            $productos->whereIn('marcas.slug', $this->selectedmarcas);
        }

        if (count($this->selectedespecificacions) > 0) {
            $productos->whereHas('especificacions', function ($query) {
                $query->whereIn('especificacions.slug', $this->selectedespecificacions);
            });
        }

        $productos->with(['promocions' => function ($query) {
            $query->with(['itempromos.producto' => function ($subQuery) {
                $subQuery->with('unit')->addSelect(['image' => function ($q) {
                    $q->select('url')->from('images')
                        ->whereColumn('images.imageable_id', 'productos.id')
                        ->where('images.imageable_type', Producto::class)
                        ->orderByDesc('default')->limit(1);
                }]);
            }])->availables()->disponibles();
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

        $productos->visibles()->publicados();

        if (isset($this->orderfilters[$this->filterselected])) {
            $column = $this->orderfilters[$this->filterselected]['column'];
            $order = $this->orderfilters[$this->filterselected]['order'];
            $productos->orderBy($column, $order);
        } else {
            $this->filterselected = 'name_asc';
            $column = 'name';
            $order = 'asc';
            if (trim($this->search) == '') {
                $productos->orderBy('novedad', 'desc')->orderBy('subcategories.orden', 'ASC')
                    ->orderBy('categories.orden', 'ASC');
            }
        }

        // dd($productos->toSql());
        if ($this->readyToLoad) {
            $productos = $productos->paginate(50)->through(function ($producto) {
                $producto->descuento = $producto->promocions->where('type', PromocionesEnum::DESCUENTO->value)->first()->descuento ?? 0;
                $producto->liquidacion = $producto->promocions->where('type', PromocionesEnum::LIQUIDACION->value)->count() > 0 ? true : false;
                return $producto;
            });
        } else {
            $productos = [];
        }
        // dd($productos);
        return view('livewire.modules.marketplace.productos.show-productos', compact('productos', 'categories',));
    }

    public function updatedSearch()
    {
        $this->resetPage();
        Self::resetfilterorder();
    }

    public function updatedSelectedcategorias()
    {
        $this->resetPage();
        $this->subcategories = Subcategory::whereHas('categories', function ($query) {
            $query->whereIn('categories.slug', $this->selectedcategorias);
        })->orderBy('orden', 'asc')->get();
        $this->selectedsubcategorias = array_filter($this->selectedsubcategorias, function ($selected) {
            return collect($this->subcategories)->contains('slug', $selected);
        });
        $this->searchsubcategorias = implode(',', $this->selectedsubcategorias);

        $this->searchcategorias = implode(',', $this->selectedcategorias);

        if (count($this->selectedcategorias) > 0) {
            $this->marcas = Marca::query()->select('id', 'name', 'slug')->whereHas('productos', function ($query) {
                $query->whereHas('category', function ($categoryQuery) {
                    $categoryQuery->whereIn('slug', $this->selectedcategorias);
                })->visibles()->publicados();
                if ($this->empresa->viewOnlyDisponibles()) {
                    $query->whereHas('almacens', function ($query) {
                        $query->where('cantidad', '>', 0);
                    });
                }
            })->orderBy('name', 'asc')->get();
        } else {
            $this->marcas = Marca::query()->select('id', 'name', 'slug')->whereHas('productos', function ($query) {
                $query->visibles()->publicados();
                if ($this->empresa->viewOnlyDisponibles()) {
                    $query->whereHas('almacens', function ($query) {
                        $query->where('cantidad', '>', 0);
                    });
                }
            })->orderBy('name', 'asc')->get();
        }

        $this->selectedmarcas = array_filter($this->selectedmarcas, function ($selected) {
            return collect($this->marcas)->contains('slug', $selected);
        });
        $this->searchmarcas = implode(',', $this->selectedmarcas);
        Self::resetfilterorder();
    }

    public function updatedSelectedsubcategorias()
    {
        $this->resetPage();
        $this->searchsubcategorias = implode(',', $this->selectedsubcategorias);

        if (count($this->selectedsubcategorias) > 0) {
            $this->marcas = Marca::query()->select('id', 'name', 'slug')->whereHas('productos', function ($query) {
                $query->whereHas('subcategory', function ($subcategoryQuery) {
                    $subcategoryQuery->whereIn('slug', $this->selectedsubcategorias);
                })->visibles()->publicados();
                if ($this->empresa->viewOnlyDisponibles()) {
                    $query->whereHas('almacens', function ($query) {
                        $query->where('cantidad', '>', 0);
                    });
                }
            })->orderBy('name', 'asc')->get();
        } else {
            $this->marcas = Marca::query()->select('id', 'name', 'slug')->whereHas('productos', function ($query) {
                $query->whereHas('category', function ($categoryQuery) {
                    $categoryQuery->whereIn('slug', $this->selectedcategorias);
                })->visibles()->publicados();
                if ($this->empresa->viewOnlyDisponibles()) {
                    $query->whereHas('almacens', function ($query) {
                        $query->where('cantidad', '>', 0);
                    });
                }
            })->orderBy('name', 'asc')->get();
        }

        $this->selectedmarcas = array_filter($this->selectedmarcas, function ($selected) {
            return collect($this->marcas)->contains('slug', $selected);
        });
        $this->searchmarcas = implode(',', $this->selectedmarcas);

        if (count($this->selectedsubcategorias) > 0) {
            $this->especificacions = Especificacion::withWhereHas('caracteristica', function ($query) {
                $query->filterweb()->orderBy('orden', 'asc');
            })->whereHas('productos', function ($query) {
                $query->whereHas('subcategory', function ($subQuery) {
                    $subQuery->whereIn('slug', $this->selectedsubcategorias);
                })->visibles()->publicados();
                if ($this->empresa->viewOnlyDisponibles()) {
                    $query->whereHas('almacens', function ($query) {
                        $query->where('cantidad', '>', 0);
                    });
                }
            })->get()->groupBy('caracteristica.name')->map(function ($especificaciones) {
                return $especificaciones->mapWithKeys(function ($especificacion) {
                    return [
                        $especificacion->slug => [
                            'slug' => $especificacion->slug,
                            'name'  => $especificacion->name
                        ]
                    ];
                });
            })->toArray();

            $this->selectedespecificacions = array_filter($this->selectedespecificacions, function ($selected) {
                foreach ($this->especificacions as $caracteristica) {
                    foreach ($caracteristica as $slug => $especificacion) {
                        if ($slug === $selected) {
                            return true; // Si encontramos el slug, lo mantenemos
                        }
                    }
                }
                return false;
            });
        } else {
            $this->selectedespecificacions = [];
            $this->especificacions = [];
        }

        $this->searchespecificaciones = implode(',', $this->selectedespecificacions);
        Self::resetfilterorder();
    }

    public function updatedSelectedmarcas()
    {
        $this->resetPage();
        $this->searchmarcas = implode(',', $this->selectedmarcas);
        Self::resetfilterorder();
    }

    public function updatedSelectedespecificacions()
    {
        $this->resetPage();
        $this->searchespecificaciones = implode(',', $this->selectedespecificacions);
        Self::resetfilterorder();
    }

    public function add_to_cart(Producto $producto, $cantidad)
    {
        $producto->load(['promocions' => function ($query) {
            $query->with(['itempromos.producto' => function ($query) {
                $query->with('unit');
            }])->availables()->disponibles()->take(1);
        }])->loadCount(['almacens as stock' => function ($query) {
            $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
        }]);

        $cart = Cart::instance('shopping')->content()->firstWhere('id', $producto->id);
        $qtyexistente = !empty($cart) ? $cart->qty : 0;
        $promocion = verifyPromocion($producto->promocions->first());
        $combo = getAmountCombo($promocion, $this->pricetype ?? null);
        $carshoopitems = !is_null($combo) ? $combo->products : [];
        $pricesale = $producto->getPrecioVentaDefault($this->pricetype ?? null);

        if ($promocion) {
            if ($promocion->limit > 0 && (($promocion->outs + $cantidad + $qtyexistente) > $promocion->limit)) {
                $mensaje = response()->json([
                    'title' => 'CANTIDAD SUPERA LAS UNIDADES DISPONIBLES EN PROMOCIÓN',
                    'text' => null,
                    'type' => 'warning'
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        }

        if ($producto->stock <= 0 || $producto->stock < ($cantidad + $qtyexistente)) {
            $mensaje = response()->json([
                'title' => 'LÍMITE DE STOCK EN PRODUCTO ALCANZADO !',
                'text' => null,
                'icon' => 'warning'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        if ($pricesale > 0) {
            Cart::instance('shopping')->add([
                'id' => $producto->id,
                'name' => $producto->name,
                'qty' => $cantidad,
                'price' => number_format($pricesale, 2, '.', ''),
                'options' => [
                    // 'pricebuy' => number_format($this->empresa->usarLista() ? $producto->precio_real_compra : $producto->pricebuy, 2, '.', ''),
                    'moneda_id' => $this->moneda->id,
                    'currency' => $this->moneda->currency,
                    'simbolo' => $this->moneda->simbolo,
                    'modo_precios' => $this->empresa->usarlista() ? $this->pricetype->name : 'PRECIO MANUAL',
                    'carshoopitems' => $carshoopitems,
                    'promocion_id' => $promocion ?  $promocion->id : null,
                    'igv' => 0,
                    'subtotaligv' => 0
                ]
            ])->associate(Producto::class);

            $this->dispatchBrowserEvent('updatecart', Cart::instance('shopping')->count());
            if (auth()->check()) {
                Cart::instance('shopping')->store(auth()->id());
            }
            $this->dispatchBrowserEvent('toast', toastJSON('AGREGADO CORRECTAMENTE'));
        } else {
            $mensaje = response()->json([
                'title' => 'CONFIGURAR PRECIOS DE VENTA PARA TIENDA VIRTUAL !',
                'text' => 'No se pudo obtener el precio de venta, configurar correctamente el modo de precios de los productos.',
                'type' => 'warning'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }
    }

    public function add_to_wishlist(Producto $producto, $cantidad)
    {
        if (!auth()->user()) {
            return redirect()->route('login')->with('activeForm', 'login');
        }
        $producto->load(['promocions' => function ($query) {
            $query->with(['itempromos.producto' => function ($query) {
                $query->with('unit');
            }])->availables()->disponibles()->take(1);
        }]);

        $promocion = verifyPromocion($producto->promocions->first());
        $combo = getAmountCombo($promocion, $this->pricetype);
        $carshoopitems = !is_null($combo) ? $combo->products : [];
        $pricesale = $producto->getPrecioVentaDefault($this->pricetype ?? null);

        if ($pricesale > 0) {
            Cart::instance('wishlist')->add([
                'id' => $producto->id,
                'name' => $producto->name,
                'qty' => $cantidad,
                'price' => number_format($pricesale, 2, '.', ''),
                'options' => [
                    'moneda_id' => $this->moneda->id,
                    'currency' => $this->moneda->currency,
                    'simbolo' => $this->moneda->simbolo,
                    'modo_precios' => $this->empresa->usarLista() ? $this->pricetype->name : 'DEFAUL PRICESALE',
                    'carshoopitems' => $carshoopitems,
                    'promocion_id' => $promocion ?  $promocion->id : null,
                    'igv' => 0,
                    'subtotaligv' => 0
                ]
            ])->associate(Producto::class);

            $this->dispatchBrowserEvent('updatewishlist', Cart::instance('wishlist')->count());

            if (auth()->check()) {
                Cart::instance('wishlist')->store(auth()->id());
            }
            $this->dispatchBrowserEvent('toast', toastJSON('AGREGADO CORRECTAMENTE'));
        } else {
            $mensaje = response()->json([
                'title' => 'CONFIGURAR PRECIOS DE VENTA PARA TIENDA VIRTUAL !',
                'text' => 'No se pudo obtener el precio de venta, configurar correctamente el modo de precios de los productos.',
                'type' => 'warning'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }
    }

    public function resetfilterorder()
    {
        if (
            count($this->selectedcategorias) == 0 &&
            count($this->selectedsubcategorias) == 0 &&
            count($this->selectedmarcas) == 0 &&
            count($this->selectedespecificacions) == 0
        ) {
            $this->reset(['filterselected']);
            // $this->filterselected = 'name_asc';
        }
    }

    public function getcombos(Producto $producto)
    {
        $producto->load(['marca', 'category', 'subcategory', 'unit', 'images' => function ($query) {
            $query->orderByDesc('default');
        }, 'promocions' => function ($query) {
            $query->with(['itempromos.producto' => function ($subQuery) {
                $subQuery->with(['unit', 'almacens'])->addSelect(['image' => function ($q) {
                    $q->select('url')->from('images')
                        ->whereColumn('images.imageable_id', 'productos.id')
                        ->where('images.imageable_type', Producto::class)
                        ->orderByDesc('default')->limit(1);
                }]);
            }])->availables()->disponibles();
        }])->loadCount(['almacens as stock' => function ($query) {
            $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
        }]);
        $producto->descuento = $producto->promocions->where('type', PromocionesEnum::DESCUENTO->value)->first()->descuento ?? 0;
        $producto->liquidacion = $producto->promocions->where('type', PromocionesEnum::LIQUIDACION->value)->count() > 0 ? true : false;
        $this->producto = $producto;
        $this->open =  true;
        // dd($producto);
    }
}
