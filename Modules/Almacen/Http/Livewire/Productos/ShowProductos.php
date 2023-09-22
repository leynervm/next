<?php

namespace Modules\Almacen\Http\Livewire\Productos;

use App\Models\Category;
use App\Models\Marca;
use App\Models\Unit;
use App\Rules\CampoUnique;
use Livewire\Component;
use Modules\Almacen\Entities\Producto;
use Livewire\WithPagination;
use Modules\Almacen\Entities\Almacen;
use Modules\Almacen\Entities\Almacenarea;
use Modules\Almacen\Entities\Estante;

class ShowProductos extends Component
{
    use WithPagination;

    public $open = false;
    public $producto;
    public $subcategories = [];
    public $search = '';
    public $searchmarca = [];
    public $searchcategory = [];
    public $searchsubcategory = [];
    public $searchalmacen = [];
    public $page = 1;

    protected $queryString = [

        'search'  => ['except' => ''],
        'searchmarca'  => ['except' => [], 'as' => 'marca'],
        'searchcategory'  => ['except' => [], 'as' => 'categoria'],
        'searchsubcategory'  => ['except' => [], 'as' => 'subcategoria'],
        'searchalmacen'  => ['except' => [], 'as' => 'almacen'],
        'page' => ['except' => 1],
    ];

    protected function rules()
    {
        return [
            'producto.name' => [
                'required', 'min:3', new CampoUnique('productos', 'name', $this->producto->id, true)
            ],
            'producto.marca_id' => ['required', 'exists:marcas,id'],
            'producto.modelo' => ['required'],
            'producto.unit_id' => ['required', 'exists:units,id'],
            'producto.pricebuy' => ['required', 'decimal:0,4', 'min:0'],
            'producto.pricesale' => ['required', 'decimal:0,4', 'min:0'],
            'producto.igv' => ['required', 'decimal:0,4', 'min:0'],
            'producto.category_id' => ['required', 'exists:categories,id'],
            'producto.subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'producto.almacenarea_id' => ['required', 'exists:almacenareas,id'],
            'producto.estante_id' => ['required', 'exists:estantes,id'],
        ];
    }

    public function mount()
    {
        $this->producto = new Producto();
    }

    public function render()
    {
        $queryProductos = Producto::whereNotNull('id');
        $units = Unit::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $marcas = Marca::orderBy('name', 'asc')->get();
        $almacenareas = Almacenarea::orderBy('name', 'asc')->get();
        $estantes = Estante::orderBy('name', 'asc')->get();

        if (trim($this->search) !== "") {
            $queryProductos->where('name', 'ilike', '%' . $this->search . '%');
        }

        if (count($this->searchmarca) > 0) {
            $queryProductos->whereIn('marca_id', $this->searchmarca);
        }

        if (count($this->searchcategory) > 0) {
            $queryProductos->whereIn('category_id', $this->searchcategory);
        }

        if (count($this->searchsubcategory) > 0) {
            $queryProductos->whereIn('subcategory_id', $this->searchsubcategory);
        }

        if (count($this->searchalmacen) > 0) {
            $queryProductos = Producto::whereHas('almacens', function ($query) {
                $query->whereIn('almacen_id', $this->searchalmacen);
            });
        }



        $productos = $queryProductos->orderBy('name', 'asc')->paginate();
        $marcaGroup = Producto::select('marca_id')->groupBy('marca_id')->get();
        $categoriaGroup = Producto::select('category_id')->groupBy('category_id')->get();
        $subcategoriaGroup = Producto::select('subcategory_id')->whereNotNull('subcategory_id')->groupBy('subcategory_id')->get();
        $almacenGroup = Almacen::whereHas('productos', function ($query) {
            // $query->select('almacen_id')->groupBy('almacen_id');
        })->get();

        return view('almacen::livewire.productos.show-productos', compact('productos', 'units', 'marcas', 'almacenareas', 'estantes', 'categories', 'marcaGroup', 'categoriaGroup', 'subcategoriaGroup', 'almacenGroup'));
    }

    public function updatedSearch($value)
    {
        $this->resetPage();
    }

    public function updatedProductoCategoryId($value)
    {
        $this->reset(['subcategories']);
        if (trim($value) !== "") {
            $this->subcategories  = Category::find($value)->subcategories;
        }
    }

    public function edit(Producto $producto)
    {
        $this->producto = $producto;
        $this->resetValidation();
        $this->subcategories  = Category::find($this->producto->category_id)->subcategories;
        $this->open = true;
    }

    public function update()
    {
        $this->validate();
        $this->producto->save();
        $this->dispatchBrowserEvent('updated');
        $this->open = false;
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-editproducto-select2');
    }
}
