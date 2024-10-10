<?php

namespace App\Http\Livewire\Modules\Marketplace\Productos;

use App\Models\Caracteristica;
use App\Models\Category;
use App\Models\Empresa;
use App\Models\Especificacion;
use App\Models\Marca;
use App\Models\Moneda;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Subcategory;
use Gloudemans\Shoppingcart\Facades\Cart;
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
        // 'selectedcategorias' => [
        //     'except' => '',
        //     'as' => 'categoria'
        // ],
        // 'selectedmarcas' => [
        //     'except' => [],
        //     'as' => 'marca'
        // ],
        'searchespecificaciones' => [
            'except' => '',
            'as' => 'especificaciones'
        ],
        'search' => [
            'except' => '',
            'as' => 'coincidencias'
        ],
        'sort' => [
            'except' => 'name',
            'as' => 'order'
        ],
        'direction' => [
            'except' => 'asc',
            'as' => 'by'
        ],
        'filterselected' => [
            'except' => 'name_asc',
            'as' => 'order-by'
        ]
    ];

    public Empresa $empresa;
    public Moneda $moneda;

    public $moneda_id, $pricetype, $pricetype_id;
    public $view = '';
    public $qty = 1;

    public $search = '', $searchcategorias = '', $searchsubcategorias = '',
        $searchmarcas = '', $searchespecificaciones = '', $orderBy = '',
        $sort = 'name', $direction = 'asc';
    public $selectedcategorias = [];
    public $selectedsubcategorias = [];
    public $selectedmarcas = [];
    public $selectedespecificacions = [];
    public $especificacions = [];
    public $stock_locals = [];
    public $matches = [];
    public $producto;
    public $subcategories = [];
    public $marcas = [];

    public $readyToLoad = false;

    public $orderfilters = [];
    public $filterselected = '';

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
            })->get();
        } else {
            $this->marcas = Marca::query()->select('id', 'name', 'slug')->whereHas('productos', function ($query) {
                $query->visibles()->publicados();
            })->get();
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
        if (!empty(request('order'))) {
            $this->sort = request('order');
        }
        if (!empty(request('by'))) {
            $this->direction = request('by');
        }
        if (empty(request('order-by'))) {
            $this->filterselected = 'name_asc';
        }

        if (count($this->selectedsubcategorias) > 0) {
            $this->marcas = Marca::query()->select('id', 'name', 'slug')->whereHas('productos', function ($query) {
                $query->whereHas('subcategory', function ($subcategoryQuery) {
                    $subcategoryQuery->whereIn('slug', $this->selectedsubcategorias);
                })->visibles()->publicados();
            })->get();

            $this->especificacions = Especificacion::withWhereHas('caracteristica', function ($query) {
                $query->filterweb()->orderBy('orden', 'asc');
            })->whereHas('productos', function ($query) {
                $query->whereHas('subcategory', function ($subQuery) {
                    $subQuery->whereIn('slug', $this->selectedsubcategorias);
                })->visibles()->publicados();
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
                })->get();
            } else {
                $this->marcas = Marca::query()->select('id', 'name', 'slug')->whereHas('productos', function ($query) {
                    $query->visibles()->publicados();
                })->get();
            }
        }

        $column_price = $this->empresa->usarLista() ? $this->pricetype->campo_table ?? 'pricesale' : 'pricesale';
        $this->orderfilters = [
            'precio_asc' => [
                'column' => $column_price,
                'order' => 'asc',
                'text' => 'DE MENOR A MAYOR PRECIO',
            ],
            'precio_desc' => [
                'column' => $column_price,
                'order' => 'desc',
                'text' => 'DE MAYOR A MENOR PRECIO',
            ],
            'name_asc' => [
                'column' => 'name',
                'order' => 'asc',
                'text' => 'POR NOMBRE ASCENDENTE',
            ],
            'name_desc' => [
                'column' => 'name',
                'order' => 'desc',
                'text' => 'POR NOMBRE DESCENDENTE',
            ],
        ];
    }

    public function render()
    {
        $categories = Category::query()->select('id', 'slug', 'name')->whereHas('productos', function ($query) {
            $query->visibles()->publicados();
        })->get();

        $productos = Producto::query()->select('id', 'name', 'slug', 'marca_id', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5')
            ->with(['almacens' => function ($query) {
                // $query->select('id, almacens.sucursal_id')->groupBy('id');
                // $query->wherePivot('cantidad', '>', 0);
            }])->addSelect(['image' => function ($query) {
                $query->select('url')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('default', 'desc')->limit(1);
            }])->addSelect(['image_2' => function ($query) {
                $query->select('url')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('default', 'desc')
                    ->offset(1)->limit(1);
            }])
            ->withWherehas('category', function ($query) {
                $query->whereNull('deleted_at');
                if (count($this->selectedcategorias) > 0) {
                    $query->whereIn('slug', $this->selectedcategorias);
                }
            })->withWherehas('subcategory', function ($query) {
                if (count($this->selectedsubcategorias) > 0) {
                    $query->whereIn('slug', $this->selectedsubcategorias);
                }
            })->withWhereHas('marca', function ($query) {
                $query->whereNull('deleted_at');
                if (count($this->selectedmarcas) > 0) {
                    $query->whereIn('slug', $this->selectedmarcas);
                }
            });

        if (count($this->selectedespecificacions) > 0) {
            $productos->whereHas('especificacions', function ($query) {
                $query->whereIn('especificacions.slug', $this->selectedespecificacions);
            });
        }

        $productos->with(['promocions' => function ($query) {
            $query->with(['itempromos.producto' => function ($query) {
                $query->with('unit')->addSelect(['image' => function ($q) {
                    $q->select('url')->from('images')
                        ->whereColumn('images.imageable_id', 'productos.id')
                        ->where('images.imageable_type', Producto::class)
                        ->orderBy('default', 'desc')->limit(1);
                }]);
            }])->disponibles()->take(1);
        }]);

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
                        });
                    // ->orWhereHas('especificacions', function ($q) use ($term) {
                    //     $q->where('especificacions.name', 'ilike', '%' . $term . '%');
                    // });
                }
            });
        }

        if (!isset($this->orderfilters[$this->filterselected])) {
            $this->filterselected = 'name_asc';
        }
        $column = !empty($this->filterselected) ? $this->orderfilters[$this->filterselected]['column'] : 'name';
        $order = !empty($this->filterselected) ? $this->orderfilters[$this->filterselected]['order'] : 'asc';
        
        $productos =  $this->readyToLoad ?
            $productos->visibles()->publicados()
            ->orderBy($column, $order)
            ->paginate(30)->through(function ($producto) {
                $producto->promocion = $producto->promocions->first();
                return $producto;
            })
            : [];

        // dd($productos);
        return view('livewire.modules.marketplace.productos.show-productos', compact('productos', 'categories',));
    }

    // public function order($sort, $direction)
    // {
    //     if ($sort == 'precio') {
    //         if ($this->pricetype) {
    //             $this->sort = $this->pricetype->campo_table;
    //         } else {
    //             $this->sort = 'pricesale';
    //         }
    //     } else {
    //         $this->sort = $sort;
    //     }
    //     $this->direction = $direction;
    // }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedcategorias()
    {
        $this->resetPage();
        $this->subcategories = Subcategory::whereHas('categories', function ($query) {
            $query->whereIn('categories.slug', $this->selectedcategorias);
        })->get();
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
            })->get();
        } else {
            $this->marcas = Marca::query()->select('id', 'name', 'slug')->whereHas('productos', function ($query) {
                $query->visibles()->publicados();
            })->get();
        }

        $this->selectedmarcas = array_filter($this->selectedmarcas, function ($selected) {
            return collect($this->marcas)->contains('slug', $selected);
        });
        $this->searchmarcas = implode(',', $this->selectedmarcas);
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
            })->get();
        } else {
            $this->marcas = Marca::query()->select('id', 'name', 'slug')->whereHas('productos', function ($query) {
                $query->whereHas('category', function ($categoryQuery) {
                    $categoryQuery->whereIn('slug', $this->selectedcategorias);
                })->visibles()->publicados();
            })->get();
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
    }

    public function updatedSelectedmarcas()
    {
        $this->resetPage();
        $this->searchmarcas = implode(',', $this->selectedmarcas);
    }

    public function updatedSelectedespecificacions()
    {
        $this->resetPage();
        $this->searchespecificaciones = implode(',', $this->selectedespecificacions);
    }

    public function add_to_cart(Producto $producto, $cantidad)
    {
        $promocion = $producto->getPromocionDisponible();
        $combo = $producto->getAmountCombo($promocion, $this->pricetype ?? null);
        $carshoopitems = !is_null($combo) ? $combo->products : [];
        $pricesale = $producto->obtenerPrecioVenta($this->pricetype ?? null);

        if ($promocion) {
            if ($promocion->limit > 0 && ($promocion->outs + $cantidad > $promocion->limit)) {
                $mensaje = response()->json([
                    'title' => 'CANTIDAD SUPERA LAS UNIDADES DISPONIBLES EN PROMOCIÓN',
                    'text' => 'Ingrese un monto menor o igual al stock de unidades disponibles.',
                    'type' => 'warning'
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
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
        $promocion = $producto->getPromocionDisponible();
        $combo = $producto->getAmountCombo($promocion, $this->pricetype);
        $carshoopitems = !is_null($combo) ? $combo->products : [];
        $pricesale = $producto->obtenerPrecioVenta($this->pricetype ?? null);

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
}
