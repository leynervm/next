<?php

namespace App\Http\Livewire\Modules\Almacen\Productos;

use App\Models\Almacen;
use App\Models\Category;
use App\Models\Marca;
use App\Models\Producto;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Nwidart\Modules\Facades\Module;

class ShowProductos extends Component
{

    use WithPagination, AuthorizesRequests;

    public $subcategories = [];
    public $search = '';
    public $searchmarca = '';
    public $searchcategory = '';
    public $searchsubcategory = '';
    public $searchalmacen = '';
    public $page = 1;
    public $checkall = false;
    public $ocultos = false;
    public $publicado = '';

    public $selectedproductos = [];

    protected $listeners = ['render'];

    protected $queryString = [
        'search' => ['except' => ''],
        'searchmarca' => ['except' => '', 'as' => 'marca'],
        'searchcategory' => ['except' => '', 'as' => 'categoria'],
        'searchsubcategory' => ['except' => '', 'as' => 'subcategoria'],
        'searchalmacen' => ['except' => '', 'as' => 'almacen'],
        'publicado' => ['except' => '', 'as' => 'publicado'],
        'ocultos'   =>  ['except' => false, 'as' => 'ver-ocultos'],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        if (trim($this->searchcategory) !== '') {
            $this->subcategories = Category::with('subcategories')->find($this->searchcategory)->subcategories;
        }
    }

    public function render()
    {

        $productos = Producto::with(['marca', 'category', 'subcategory', 'unit', 'almacens', 'compraitems.compra.proveedor'])
            ->withCount(['almacens as stock' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)')); // Suma de la cantidad en la tabla pivote
            }])->addSelect(['image' => function ($query) {
                $query->select('url')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('default', 'desc')->limit(1);
            }]);

        if (Module::isEnabled('Almacen')) {
            $productos->with(['almacenarea', 'estante']);
        }
        if (trim($this->search) !== '') {
            // $searchTerms = explode(' ', $this->search);
            $productos->where(function ($query) {
                // foreach ($searchTerms as $term) {
                $query->orWhere('name', 'ilike', '%' . $this->search . '%')
                    ->orWhereHas('marca', function ($q) {
                        $q->whereNull('deleted_at')->where('name', 'ilike', '%' . $this->search . '%');
                    })
                    ->orWhereHas('category', function ($q) {
                        $q->whereNull('deleted_at')->where('name', 'ilike', '%' . $this->search . '%');
                    })
                    ->orWhereHas('especificacions', function ($q) {
                        $q->where('especificacions.name', 'ilike', '%' . $this->search . '%');
                    });
                // }
            });
        }

        if (trim($this->searchalmacen) != '') {
            $productos->whereHas('almacens', function ($query) {
                $query->where('almacens.id', $this->searchalmacen);
            });
        }

        if (trim($this->searchmarca) != '') {
            $productos->where('marca_id', $this->searchmarca);
        }

        if (trim($this->searchcategory) != '') {
            $productos->where('category_id', $this->searchcategory);
        }

        if (trim($this->searchsubcategory) != '') {
            $productos->where('subcategory_id', $this->searchsubcategory);
        }

        if ($this->publicado !== '') {
            $productos->where('publicado', $this->publicado);
        }

        if ($this->ocultos) {
            $productos->ocultos();
        } else {
            $productos->visibles();
        }

        $productos = $productos->orderBy('name', 'asc')->paginate();
        $marcas = Marca::whereHas('productos')->orderBy('name', 'asc')->get();
        $categorias = Category::whereHas('productos')->orderBy('orden', 'asc')->get();
        $almacens = Almacen::whereHas('productos')->withWhereHas('sucursals', function ($query) {
            if (!auth()->user()->isAdmin()) {
                $query->where('sucursal_id', auth()->user()->sucursal_id);
            }
        })->get();

        return view('livewire.modules.almacen.productos.show-productos', compact('productos', 'marcas', 'categorias', 'almacens'));
    }

    public function updatedSearch()
    {
        $this->reseFilters();
    }

    public function updatedSearchalmacen()
    {
        $this->reseFilters();
    }

    public function updatedSearchmarca()
    {
        $this->reseFilters();
    }

    public function updatedSearchcategory($value)
    {
        $this->reseFilters();
        $this->reset(['subcategories', 'searchsubcategory']);
        if (trim($value) !== "") {
            $this->subcategories = Category::with('subcategories')->find($value)->subcategories;
        }
    }

    public function updatedSearchsubcategory()
    {
        $this->reseFilters();
    }

    public function updatedPublicado()
    {
        $this->reseFilters();
    }

    public function updatedPage()
    {
        $this->resetValidation();
        $this->reset(['checkall', 'selectedproductos']);
    }

    private function reseFilters()
    {
        $this->resetPage();
        $this->resetValidation();
        $this->reset(['checkall', 'selectedproductos']);
    }


    public function deleteall($selectedproductos = [])
    {
        $this->authorize('admin.almacen.productos.delete');

        if (count($selectedproductos) > 0) {
            $count = 0;
            foreach ($selectedproductos as $item) {
                $producto = Producto::with('images')->find($item);
                // $tvitems = $producto->tvitems()->count();
                // $compraitems = $producto->compraitems()->count();
                $producto->loadCount([
                    'tvitems as totaltvitems' => function ($query) {
                        $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
                    },
                    'compraitems as totaltvcompras' => function ($query) {
                        $query->select(DB::raw('COALESCE(COUNT(*),0)'));
                    },
                    'carshoops as totalcarshoops' => function ($query) {
                        $query->select(DB::raw('COALESCE(COUNT(*),0)'));
                    },
                    'series as totaloutseries' => function ($query) {
                        $query->salidas()->select(DB::raw('COALESCE(COUNT(*),0)'));
                    },
                ]);

                $cadena = extraerMensaje([
                    'Items_Venta' => $producto->totaltvitems,
                    'Items_Compra' => $producto->totaltvcompras,
                    'Items_Carrito_Venta' => $producto->totalcarshoops,
                    'Items_Series_Salientes' => $producto->totaloutseries,
                ]);

                if ($producto->totaltvitems > 0 || $producto->totaltvcompras > 0 || $producto->totalcarshoops > 0 || $producto->totaloutseries > 0) {
                    $mensaje = response()->json([
                        'title' => 'NO SE PUEDE ELIMINAR EL PRODUCTO ' . $producto->name,
                        'text' => "Existen registros vinculados $cadena, eliminarlo causaría un conflicto en la base de datos."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                } else {
                    DB::beginTransaction();
                    try {

                        $images = $producto->images;
                        $producto->kardexes()->delete();
                        $producto->images()->delete();
                        $producto->carshoops()->delete();
                        $producto->series()->forceDelete();
                        $producto->promocions()->forceDelete();
                        $producto->forceDelete();
                        DB::commit();
                        if (count($images) > 0) {
                            foreach ($images as $image) {
                                if (Storage::exists('productos/' . $image->url)) {
                                    Storage::delete('productos/' . $image->url);
                                }
                            }
                        }
                        $count++;
                    } catch (\Exception $e) {
                        DB::rollBack();
                        throw $e;
                    } catch (\Throwable $e) {
                        DB::rollBack();
                        throw $e;
                    }
                }
            }
            if ($count > 0) {
                $this->reset(['selectedproductos']);
                $this->dispatchBrowserEvent('toast', toastJSON("$count PRODUCTOS ELIMINADOS CORRECTAMENTE !"));
                return true;
            }
        }
    }

    public function hiddenproducto(Producto $producto)
    {
        $this->authorize('admin.almacen.productos.delete');
        $mensaje = $producto->isVisible() ? 'ocultado' : 'mostrado';
        $producto->visivility = $producto->isVisible() ? Producto::OCULTAR : Producto::MOSTRAR;
        $producto->save();
        $this->dispatchBrowserEvent('toast', toastJSON('Producto ' . $mensaje . ' corrrectamnente'));
    }
}
