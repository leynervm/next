<?php

namespace App\Http\Livewire\Modules\Almacen\Productos;

use App\Models\Almacen;
use App\Models\Category;
use App\Models\Marca;
use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProductos extends Component
{

    use WithPagination;

    public $subcategories = [];
    public $search = '';
    public $searchmarca = '';
    public $searchcategory = '';
    public $searchsubcategory = '';
    public $searchalmacen = '';
    public $page = 1;
    public $publicado = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'searchmarca' => ['except' => '', 'as' => 'marca'],
        'searchcategory' => ['except' => '', 'as' => 'categoria'],
        'searchsubcategory' => ['except' => '', 'as' => 'subcategoria'],
        'searchalmacen' => ['except' => '', 'as' => 'almacen'],
        'publicado' => ['except' => '', 'as' => 'publicado'],
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

        $productos = Producto::with(['marca', 'category', 'subcategory', 'unit', 'almacens', 'compraitems']);

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

        if ($this->search !== '') {
            $productos->where('name', 'ilike', '%' . $this->search . '%');
        }

        if ($this->publicado !== '') {
            $productos->where('publicado', $this->publicado);
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
        $this->reset(['subcategories']);
        if (trim($value) !== "") {
            $this->subcategories = Category::with('subcategories')->find($value)->subcategories;
        }
    }

    public function updatedSearchsubcategory($value)
    {
        $this->resetPage();
    }

    public function updatedPublicado($value)
    {
        $this->resetPage();
    }
}
