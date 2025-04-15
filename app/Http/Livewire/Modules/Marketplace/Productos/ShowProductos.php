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
    public $favoritos = [];

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
        $searchsubcat = '';
        $similaritysubcat = '';
        $ts_subcat = '';

        $categories = Category::query()->select('id', 'slug', 'name')->whereHas('productos', function ($query) {
            $query->visibles()->publicados();
            if ($this->empresa->viewOnlyDisponibles()) {
                $query->whereHas('almacens', function ($query) {
                    $query->where('cantidad', '>', 0);
                });
            }
        })->orderBy('orden', 'asc')->get();

        if (
            count($this->selectedcategorias) == 0 && count($this->selectedsubcategorias) == 0 &&
            count($this->selectedmarcas) == 0 && count($this->selectedespecificacions) == 0
        ) {
            $searchsubcat = "COALESCE(subcategories.name, '') || ' ' ||";
            $similaritysubcat = "OR similarity(subcategories.name, '" . $this->search . "') > 0.3";
            $ts_subcat = "|| ' ' || subcategories.name";
        }


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
                    $searchsubcat 
                    COALESCE(categories.name, '')
                ), plainto_tsquery('spanish', '" . $this->search . "')) AS rank"
            )
        )->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->leftJoin('subcategories', 'productos.subcategory_id', '=', 'subcategories.id')
            ->leftJoin('categories', 'productos.category_id', '=', 'categories.id')
            ->with(['unit', 'imagen', 'almacens' => function ($query) {
                $query->where('cantidad', '>', 0);
            }])->addSelect(['image_2' => function ($query) {
                $query->select('urlmobile')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('orden', 'asc')->orderBy('id', 'asc')->offset(1)->limit(1);
            }]);

        if ($this->empresa->viewOnlyDisponibles()) {
            $productos->whereHas('almacens', function ($query) {
                $query->where('cantidad', '>', 0);
            });
        }

        $productos->withCount(['almacens as stock' => function ($query) {
            $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)')); // Suma de la cantidad en la tabla pivote
        }]);

        if (trim($this->search) !== '') {
            $productos->where(function ($query) {
                $query->whereRaw("
                    to_tsvector('spanish',
                        COALESCE(productos.name, '') || ' ' ||
                        COALESCE(marcas.name, '') || ' ' ||
                        COALESCE(subcategories.name, '') || ' ' ||
                        COALESCE(categories.name, '')
                    ) @@ plainto_tsquery('spanish', ?)
                ", [$this->search])
                    ->orWhereRaw("
                    similarity(productos.name, ?) > 0.3
                    OR similarity(marcas.name, ?) > 0.3
                    OR similarity(subcategories.name, ?) > 0.3
                    OR similarity(categories.name, ?) > 0.3
                ", [
                        $this->search,
                        $this->search,
                        $this->search,
                        $this->search,
                    ]);
            });

            // $productos->whereRaw(
            //     "to_tsvector('spanish', 
            //     COALESCE(productos.name, '') || ' ' || 
            //     COALESCE(marcas.name, '') || ' ' || 
            //      $searchsubcat  
            //     COALESCE(categories.name, '')) @@ plainto_tsquery('spanish', '" . $this->search . "')",
            // )->orWhereRaw(
            //     "similarity(productos.name, '" . $this->search . "') > 0.1 
            //     OR similarity(marcas.name, '" . $this->search . "') > 0.3 
            //     $similaritysubcat 
            //     OR similarity(categories.name, '" . $this->search . "') > 0.5"
            // );
        }

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
            $query->with(['itempromos.producto' => function ($subq) {
                $subq->with(['imagen', 'almacens']);
            }])->availables()->disponibles();
        }])->visibles()->publicados();


        if (isset($this->orderfilters[$this->filterselected])) {
            $column = $this->orderfilters[$this->filterselected]['column'];
            $order = $this->orderfilters[$this->filterselected]['order'];
            $productos->orderBy($column, $order);
        }

        if (trim($this->search) !== '') {
            $productos->orderByDesc('rank')
                ->orderBy('categories.orden')
                ->orderByRaw("ts_rank(to_tsvector('spanish', productos.name || ' ' || marcas.name $ts_subcat || ' ' || categories.name), plainto_tsquery('spanish', ?)) DESC", [$this->search])
                ->orderByDesc(DB::raw("similarity(productos.name, '" . $this->search . "')"))
                ->orderBy('subcategories.orden')/* ->orderByDesc('novedad') */;
        } else {

            if (count($this->selectedmarcas) > 0) {
                $productos->orderByRaw("array_position(ARRAY[" . collect(array_reverse($this->selectedmarcas))->map(fn($slug) => "'$slug'")->join(',') . "]::text[], marcas.slug)");
            }

            if (count($this->selectedsubcategorias) == 0) {
                $productos->orderByDesc('novedad');
            }

            if (count($this->selectedcategorias) > 0) {
                $productos->orderByRaw("array_position(ARRAY[" . collect(array_reverse($this->selectedcategorias))->map(fn($slug) => "'$slug'")->join(',') . "]::text[], categories.slug)");
            } else {
                $productos->orderBy('categories.orden');
            }

            if (count($this->selectedsubcategorias) > 0) {
                $productos->orderByRaw("array_position(ARRAY[" . collect(array_reverse($this->selectedsubcategorias))->map(fn($slug) => "'$slug'")->join(',') . "]::text[], subcategories.slug)");
            } else {
                $productos->orderBy('subcategories.orden');
            }

            if (count($this->selectedsubcategorias) > 0) {
                $productos->orderByDesc('novedad');
            }
        }

        // dd($productos->toSql());
        if ($this->readyToLoad) {
            $productos = $productos->paginate(50)->through(function ($producto) {
                $producto->descuento = $producto->promocions->whereIn('type', [PromocionesEnum::DESCUENTO->value, PromocionesEnum::OFERTA->value])->first()->descuento ?? 0;
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
        $this->reset([
            'searchsubcategorias',
            'selectedsubcategorias',
            'searchmarcas',
            'selectedmarcas',
            'searchespecificaciones',
            'selectedespecificacions'
        ]);
    }

    public function resetfilters()
    {
        $this->resetExcept(['empresa', 'moneda', 'moneda_id', 'pricetype', 'pricetype_id', 'readyToLoad', 'orderfilters']);
        $this->resetPage();
        Self::resetfilterorder();
    }

    public function updatedSelectedcategorias()
    {
        $this->resetPage();
        $this->reset('search');
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
        $this->reset('search');
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
        $this->reset('search');
        $this->resetPage();
        $this->searchmarcas = implode(',', $this->selectedmarcas);
        Self::resetfilterorder();
    }

    public function updatedSelectedespecificacions()
    {
        $this->reset('search');
        $this->resetPage();
        $this->searchespecificaciones = implode(',', $this->selectedespecificacions);
        Self::resetfilterorder();
    }

    public function addfavoritos(Producto $producto)
    {
        if (!auth()->user()) {
            return redirect()->route('login')->with('activeForm', 'login');
        }

        $response = $producto->addfavorito();
        if ($response->getData()->success) {
            $this->dispatchBrowserEvent('updatewishlist', $response->getData()->counter);
            $this->dispatchBrowserEvent('toast', toastJSON($response->getData()->mensaje));
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
            // $this->reset(['filterselected']);
            $this->filterselected = 'name_asc';
        }
    }

    public function getcombos(Producto $producto)
    {
        $producto->load(['marca', 'category', 'subcategory', 'unit', 'imagen', 'promocions' => function ($query) {
            $query->with(['itempromos.producto' => function ($subQuery) {
                $subQuery->with(['unit', 'imagen', 'almacens']);
            }])->availables()->disponibles();
        }])->loadCount(['almacens as stock' => function ($query) {
            $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
        }]);
        $producto->descuento = $producto->promocions->whereIn('type', [PromocionesEnum::DESCUENTO->value, PromocionesEnum::OFERTA->value])->first()->descuento ?? 0;
        $producto->liquidacion = $producto->promocions->where('type', PromocionesEnum::LIQUIDACION->value)->count() > 0 ? true : false;
        $this->producto = $producto;
        $this->open =  true;
        // dd($producto);
    }

    public function hydrate()
    {
        if (auth()->check()) {
            Cart::instance('wishlist')->restore(auth()->id());
        }
        $this->favoritos = Cart::instance('wishlist')->content()->pluck('id')->toArray();
    }
}
