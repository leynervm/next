<?php

namespace App\Http\Livewire\Modules\Almacen\Productos;

use App\Models\Almacen;
use App\Models\Category;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Almacen\Entities\Almacenarea;
use Modules\Almacen\Entities\Estante;

class ShowProductos extends Component
{

    use WithPagination;

    public $subcategories = [];
    public $search = '';
    public $searchmarca = [];
    public $searchcategory = [];
    public $searchsubcategory = [];
    public $searchalmacen = [];
    public $page = 1;
    public $publicado = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'searchmarca' => ['except' => [], 'as' => 'marca'],
        'searchcategory' => ['except' => [], 'as' => 'categoria'],
        'searchsubcategory' => ['except' => [], 'as' => 'subcategoria'],
        'searchalmacen' => ['except' => [], 'as' => 'almacen'],
        'publicado' => ['except' => '', 'as' => 'publicado'],
        'page' => ['except' => 1],
    ];

    public function render()
    {

        $productos = Producto::with('compraitems')->whereHas('almacens', function ($query) {
            if (count($this->searchalmacen) > 0) {
                $query->whereIn('almacens.name', $this->searchalmacen);
            }
        })->whereHas('marca', function ($query) {
            if (count($this->searchmarca) > 0) {
                $query->whereIn('marcas.name', $this->searchmarca);
            }
        })->whereHas('category', function ($query) {
            if (count($this->searchcategory) > 0) {
                $query->whereIn('categories.name', $this->searchcategory);
            }
        })->whereHas('subcategory', function ($query) {
            if (count($this->searchsubcategory) > 0) {
                $query->whereIn('subcategories.name', $this->searchsubcategory);
            }
        });

        if ($this->search !== '') {
            $productos->where('name', 'ilike', '%' . $this->search . '%');
        }

        if ($this->publicado !== '') {
            $productos->where('publicado', $this->publicado);
        }

        $productos = $productos->orderBy('name', 'asc')->paginate();
        $units = Unit::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $marcas = Marca::orderBy('name', 'asc')->get();
        $almacenareas = Almacenarea::orderBy('name', 'asc')->get();
        $estantes = Estante::orderBy('name', 'asc')->get();
        $marcaGroup = Producto::select('marca_id')->groupBy('marca_id')->get();
        $categoriaGroup = Producto::select('category_id')->groupBy('category_id')->get();
        $subcategoriaGroup = Producto::select('subcategory_id')->whereNotNull('subcategory_id')->groupBy('subcategory_id')->paginate(10, ['*'], 'page-subcategory');
        $almacenGroup = Almacen::whereHas('productos')->get();

        return view('livewire.modules.almacen.productos.show-productos', compact('productos', 'units', 'marcas', 'almacenareas', 'estantes', 'categories', 'marcaGroup', 'categoriaGroup', 'subcategoriaGroup', 'almacenGroup'));
    }

    public function updatedSearch($value)
    {
        $this->resetPage();
    }

    public function updatedSearchalmacen($value)
    {
        $this->resetPage();
    }

    public function updatedSearchmarca($value)
    {
        $this->resetPage();
    }

    public function updatedSearchcategory($value)
    {
        $this->resetPage();
    }

    public function updatedSearchsubcategory($value)
    {
        $this->resetPage();
    }

    public function updatedProductoCategoryId($value)
    {
        $this->reset(['subcategories']);
        if (trim($value) !== "") {
            $this->subcategories = Category::find($value)->subcategories;
        }
    }

    public function updatedPublicado($value)
    {
        $this->resetPage();
    }
}
