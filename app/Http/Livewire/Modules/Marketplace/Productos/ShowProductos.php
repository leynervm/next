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
        ]
    ];

    public Empresa $empresa;
    public Moneda $moneda;

    public $moneda_id, $pricetype, $pricetype_id;
    public $view = '';
    public $qty = 1;

    public $searchcategorias = '', $searchsubcategorias = '', $searchmarcas = '', $searchespecificaciones = '';
    public $selectedcategorias = [];
    public $selectedsubcategorias = [];
    public $selectedmarcas = [];
    public $especificacions = [];
    public $stock_locals = [];
    public $producto;

    public function mount($pricetype = null)
    {
        $this->pricetype = $pricetype;
        $this->producto = new Producto();
        if (!empty(request('categorias'))) {
            $this->selectedcategorias = explode(',', request('categorias'));
        }
        if (!empty(request('subcategorias'))) {
            $this->selectedsubcategorias = explode(',', request('subcategorias'));
        }
        if (!empty(request('marcas'))) {
            $this->selectedmarcas = explode(',', request('marcas'));
        }
        if (!empty(request('especificaciones'))) {
            $this->especificacions = explode(',', request('especificaciones'));
        }
    }

    public function render()
    {
        $categories = Category::whereHas('productos')->get();
        $subcategories = Subcategory::whereHas('productos')->get();
        $marcas = Marca::whereHas('productos')->get();
        $caracteristicas = Caracteristica::withWhereHas('especificacions', function ($query) {
            $query->whereHas('productos');
        })->get();


        $productos = Producto::with(['subcategory'])->with(['almacens' => function ($query) {
            // $query->select('id, almacens.sucursal_id')->groupBy('id');
            // $query->wherePivot('cantidad', '>', 0);
        }])->withWherehas('category', function ($query) {
            $query->whereNull('deleted_at');
            if (count($this->selectedcategorias) > 0) {
                $query->whereIn('slug', $this->selectedcategorias);
            }
        })->withWherehas('subcategory', function ($query) {
            if (count($this->selectedsubcategorias) > 0) {
                $query->whereIn('slug', $this->selectedsubcategorias);
            }
        })->withWherehas('marca', function ($query) {
            $query->whereNull('deleted_at');
            if (count($this->selectedmarcas) > 0) {
                $query->whereIn('slug', $this->selectedmarcas);
            }
        })->with('promocions', function ($query) {
            $query->disponibles();
        });

        if (count($this->especificacions) > 0) {
            $productos->wherehas('especificacions', function ($query) {
                $query->whereIn('especificacions.slug', $this->especificacions);
            });
        }

        $productos = $productos->publicados()->orderBy('name', 'asc')->paginate();
        // dd($productos);
        return view('livewire.modules.marketplace.productos.show-productos', compact('productos', 'categories', 'subcategories', 'marcas', 'caracteristicas'));
    }

    public function updatedSelectedcategorias()
    {
        $this->searchcategorias = implode(',', $this->selectedcategorias);
    }

    public function updatedSelectedsubcategorias()
    {
        $this->searchsubcategorias = implode(',', $this->selectedsubcategorias);
    }

    public function updatedSelectedmarcas()
    {
        $this->searchmarcas = implode(',', $this->selectedmarcas);
    }

    public function updatedEspecificacions()
    {
        $this->searchespecificaciones = implode(',', $this->especificacions);
    }

    public function add_to_cart(Producto $producto, $cantidad)
    {

        $promocion = $producto->getPromocionDisponible();
        $descuento = $producto->getPorcentajeDescuento($promocion);
        $combo = $producto->getAmountCombo($promocion, $this->pricetype);
        $priceCombo = $combo ? $combo->total : 0;
        $carshoopitems = (!is_null($combo) && count($combo->products) > 0) ? $combo->products : [];

        if ($this->empresa->usarLista()) {
            if ($this->pricetype) {
                $price = $producto->calcularPrecioVentaLista($this->pricetype);
                $price = !is_null($promocion) && $promocion->isRemate()
                    ? $producto->precio_real_compra
                    : $price;
                $pricesale = $descuento > 0 ? $producto->getPrecioDescuento($price, $descuento, 0, $this->pricetype) : $price;
            }
        } else {
            $price = $producto->pricesale;
            $price = !is_null($promocion) && $promocion->isRemate() ? $producto->pricebuy : $price;
            $pricesale = $descuento > 0 ? $producto->getPrecioDescuento($price, $descuento, 0) : $price;
        }

        if (isset($price)) {
            $price = $price + $priceCombo;
            $pricesale = $pricesale + $priceCombo;
            // dd($price, $pricesale);
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
                    'modo_precios' => $this->empresa->usarLista() ? $this->pricetype->name : 'DEFAUL PRICESALE',
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
            $this->dispatchBrowserEvent('toast', toastJSON('Agregado al carrito'));
        } else {
            $mensaje = response()->json([
                'title' => 'CONFIGURAR LISTA DE PRECIOS PARA TIENDA VIRTUAL !',
                'text' => 'No se pudo obtener el precio de venta, configurar correctamente el modo de precios de los productos.',
                'type' => 'warning'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }
    }

    public function add_to_wishlist(Producto $producto, $cantidad)
    {

        $promocion = $producto->getPromocionDisponible();
        $descuento = $producto->getPorcentajeDescuento($promocion);
        $combo = $producto->getAmountCombo($promocion, $this->pricetype);
        $priceCombo = $combo ? $combo->total : 0;
        $carshoopitems = (!is_null($combo) && count($combo->products) > 0) ? $combo->products : [];

        if ($this->empresa->usarLista()) {
            if ($this->pricetype) {
                $price = $producto->calcularPrecioVentaLista($this->pricetype);
                $price = !is_null($promocion) && $promocion->isRemate()
                    ? $producto->precio_real_compra
                    : $price;
                $pricesale = $descuento > 0 ? $producto->getPrecioDescuento($price, $descuento, 0, $this->pricetype) : $price;
            }
        } else {
            $price = $producto->pricesale;
            $price = !is_null($promocion) && $promocion->isRemate() ? $producto->pricebuy : $price;
            $pricesale = $descuento > 0 ? $producto->getPrecioDescuento($price, $descuento, 0) : $price;
        }

        if (isset($price)) {
            $price = $price + $priceCombo;
            $pricesale = $pricesale + $priceCombo;
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
            $this->dispatchBrowserEvent('toast', toastJSON('Agregado a lista de deseos'));
        } else {
            $mensaje = response()->json([
                'title' => 'CONFIGURAR LISTA DE PRECIOS PARA TIENDA VIRTUAL !',
                'text' => 'No se pudo obtener el precio de venta, configurar correctamente el modo de precios de los productos.',
                'type' => 'warning'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }
    }
}
