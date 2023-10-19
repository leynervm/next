<?php

namespace Modules\Ventas\Http\Livewire\Ventas;

use App\Helpers\GetPrice;
use App\Models\Carshoop;
use App\Models\Category;
use App\Models\Empresa;
use App\Models\Moneda;
use App\Models\Pricetype;
use App\Models\Serie;
use App\Rules\ValidateSerieRequerida;
use App\Rules\ValidateStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Almacen\Entities\Almacen;
use Modules\Almacen\Entities\Producto;
use Livewire\WithPagination;

class CreateVenta extends Component
{

    use WithPagination;

    public $empresa, $pricetype, $moneda;
    public $open = false;
    public $disponibles = 1;
    public $readyToLoad = false;
    public $producto_id, $serie_id, $almacen_id, $pricetype_id;

    public $search = '';
    public $searchserie = '';
    public $searchalmacen = '';
    public $searchcategory = '';
    public $cart = [];

    protected $listeners = ['render', 'setPricetypeId', 'setMoneda'];

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

    public function mount(Empresa $empresa, Pricetype $pricetype, Moneda $moneda)
    {
        $this->empresa = $empresa;
        $this->pricetype = $pricetype;
        $this->moneda = $moneda;
        $this->pricetype_id = $pricetype->id ?? null;
    }

    public function render()
    {
        $productos = Producto::select('id', 'name', 'pricebuy', 'priceusbuy', 'pricesale', 'category_id')->orderBy('name', 'asc');
        $categories = Category::orderBy('name', 'asc')->get();
        $pricetypes = Pricetype::orderBy('name', 'asc')->get();
        $almacens = Almacen::orderBy('name', 'asc')->get();

        // if (trim($this->searchalmacen) !== "") {
        //     $productos->whereHas('almacens', function ($query) {
        //         $query->where('almacen_id', $this->searchalmacen);
        //     })->with(['almacens' => function ($query) {
        //         $query->where('almacen_id', $this->searchalmacen);
        //     }]);
        // }

        $relations = ['almacens', 'category', 'images', 'seriesdisponibles', 'ofertasdisponibles', 'existStock'];

        if (trim($this->searchcategory) !== "") {
            $productos->where('category_id', $this->searchcategory);
        }

        if ($this->disponibles) {
            array_push($relations, 'disponiblealmacens');
            $productos->whereHas('disponiblealmacens');
            // ->with($relations);
        } else {
            array_push($relations, 'almacens');
        }

        if (trim($this->search) !== "") {
            $productos->where('name', 'ilike',  $this->search . '%');
        }


        if ($this->readyToLoad) {
            $productos = $productos->with($relations)->paginate();
        } else {
            $productos = [];
        }

        return view('ventas::livewire.ventas.create-venta', compact('productos', 'pricetypes', 'categories', 'almacens'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSearchcategory()
    {
        $this->resetPage();
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


    public function loadProductos()
    {
        $this->readyToLoad = true;
    }

    public function getProductoBySerie($value)
    {
        $this->searchserie = trim($this->searchserie);
        $this->validate([
            'searchserie' => [
                'required', 'min:3'
            ],
            'moneda.id' => [
                'required', 'integer', 'min:1', 'exists:monedas,id'
            ],
        ]);

        $serie = Serie::where('serie', trim(mb_strtoupper($value, "UTF-8")))
            ->where('status', 0)->first();
        if ($serie) {

            $precios = GetPrice::getPriceProducto($serie->producto, $this->pricetype_id)->getData();

            if ($precios->success) {
                DB::beginTransaction();

                try {

                    // dd($serie);
                    // dd($serie->producto->almacens()->find($serie->almacen_id)->pivot->cantidad);
                    $producto = $serie->producto->almacens()->find($serie->almacen_id);
                    if ($producto) {

                        $stock = $serie->producto->almacens()->find($serie->almacen_id)->pivot->cantidad;

                        if ($stock > 0) {

                            if (($stock - 1) >= 0) {

                                $carrito = Carshoop::Micarrito()->where('producto_id',  $serie->producto->id)
                                    ->where('almacen_id',  $serie->almacen_id)
                                    ->where('moneda_id',  $this->moneda->id)->first();

                                if ($carrito) {
                                    $carrito->cantidad = $carrito->cantidad + 1;
                                    $carrito->igv = $carrito->igv + $serie->producto->igv;
                                    $carrito->subtotal = $carrito->subtotal + $precios->pricewithdescount;
                                    $carrito->total = $carrito->total + $precios->pricewithdescount;
                                    $carrito->save();
                                } else {
                                    $carrito = Carshoop::create([
                                        'date' => Carbon::now('America/Lima'),
                                        'cantidad' => 1,
                                        'pricebuy' => $serie->producto->pricebuy,
                                        'price' => $precios->pricewithdescount,
                                        'igv' => $serie->producto->igv,
                                        'subtotal' => $precios->pricewithdescount,
                                        'total' => $precios->pricewithdescount,
                                        'status' => 0,
                                        'almacen_id' => $serie->almacen_id,
                                        'producto_id' => $serie->producto->id,
                                        'moneda_id' => $this->moneda->id,
                                        'user_id' => Auth::user()->id,
                                    ]);
                                }

                                $carrito->carshoopseries()->create([
                                    'date' => Carbon::now('America/Lima'),
                                    'serie_id' => $serie->id
                                ]);

                                $serie->update([
                                    'status' => 1,
                                    'dateout' => Carbon::now('America/Lima')
                                ]);

                                $serie->producto->almacens()->updateExistingPivot($serie->almacen_id, [
                                    'cantidad' => $stock - 1,
                                ]);

                                DB::commit();
                                $this->dispatchBrowserEvent('update-carrito');
                                $this->emitTo('ventas::ventas.show-resumen-venta', 'render');
                                $this->resetValidation();
                                $this->dispatchBrowserEvent('created');
                                $this->reset(['searchserie']);
                            } else {
                                $this->addError('searchserie', 'Cantidad supera al stock disponible.');
                            }
                        }
                    } else {
                        $this->addError('searchserie', 'No se pudo encontrar la serie del producto.');
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    DB::rollBack();
                    throw $e;
                }
            } else {
                $this->addError('searchserie', $precios->error);
            }
        } else {
            $this->addError('searchserie', 'Serie no encontrada');
        }
    }

    public function addtocar($formData, Producto $producto)
    {

        $this->resetValidation(["errorstocart.*"]);
        // $price = trim($formData["price"]);
        $price = isset($formData["price"]) ? trim($formData["price"]) : "";
        $almacen_id = isset($formData["almacen_$producto->id[]"]) ? trim($formData["almacen_$producto->id[]"]) : null;
        $serie = isset($formData["serie"]) ? trim($formData["serie"]) : "";
        $cantidad = isset($formData["cantidad"]) ? trim($formData["cantidad"]) : 1;

        $this->cart[$producto->id]["price"] = $price;
        $this->cart[$producto->id]["almacen_id"] = $almacen_id;
        $this->cart[$producto->id]["serie"] = $serie;
        $this->cart[$producto->id]["cantidad"] = $cantidad;

        $validateDate = $this->validate([
            "cart.$producto->id.price" => [
                'required', 'numeric', 'decimal:0,4', 'min:0.0001'
            ],
            "cart.$producto->id.almacen_id" => [
                'required', 'integer', 'exists:almacens,id',
                new ValidateStock($producto->id, $almacen_id)
            ],
            "cart.$producto->id.cantidad" => [
                'required', 'numeric', 'min:1', 'decimal:0,2'
            ],
            "cart.$producto->id.serie" => [
                Rule::requiredIf($producto->seriesdisponibles()->exists()),
                new ValidateSerieRequerida($producto->id, $almacen_id, $serie),
            ],
            "moneda.id" => [
                'required', 'integer', 'min:1', 'exists:monedas,id'
            ],
        ]);

        DB::beginTransaction();

        try {

            $stock = $producto->almacens->find($almacen_id)->pivot->cantidad;

            if ($stock > 0) {
                if (($stock - $cantidad) >= 0) {

                    $carrito = Carshoop::Micarrito()->where('producto_id',  $producto->id)
                        ->where('almacen_id',  $almacen_id)
                        ->where('moneda_id',  $this->moneda->id)->first();

                    if ($carrito) {
                        $carrito->cantidad = $carrito->cantidad + $cantidad;
                        $carrito->igv = $carrito->igv + ($cantidad * $producto->igv);
                        $carrito->subtotal = $carrito->subtotal + ($price * $cantidad);
                        $carrito->total = $carrito->total + ($price * $cantidad);
                        $carrito->save();
                    } else {
                        $carrito = Carshoop::create([
                            'date' => Carbon::now('America/Lima'),
                            'cantidad' => $cantidad,
                            'pricebuy' => $producto->pricebuy,
                            'price' => $price,
                            'igv' => $cantidad * $producto->igv,
                            'subtotal' => $price * $cantidad,
                            'total' => $price * $cantidad,
                            'status' => 0,
                            'almacen_id' => $almacen_id,
                            'producto_id' => $producto->id,
                            'moneda_id' => $this->moneda->id,
                            'user_id' => Auth::user()->id,
                        ]);
                    }

                    $existseries = $producto->seriesdisponibles()
                        ->where('almacen_id', $almacen_id)
                        ->exists();

                    $serieProducto = Serie::where('serie', trim(mb_strtoupper($serie, "UTF-8")))->first();

                    if ($existseries) {
                        if ($serieProducto) {
                            $carrito->carshoopseries()->create([
                                'date' => Carbon::now('America/Lima'),
                                'serie_id' => $serieProducto->id
                            ]);

                            $serieProducto->update([
                                'status' => 1,
                                'dateout' => Carbon::now('America/Lima')
                            ]);
                        }
                    }

                    $producto->almacens()->updateExistingPivot($almacen_id, [
                        'cantidad' => $stock - $cantidad,
                    ]);

                    DB::commit();
                    $this->resetValidation();
                    $this->emitTo('ventas::ventas.show-resumen-venta', 'render');
                    $this->dispatchBrowserEvent('update-carrito');
                    $this->dispatchBrowserEvent('reset-form', $producto->id);
                    $this->dispatchBrowserEvent('created');
                    // if ($this->typepayment->paycuotas) {
                    //     $this->calcular_cuotas();
                    // }
                } else {
                    $this->addError("cart.$producto->id.cantidad", 'Cantidad supera al stock disponible.');
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-create-venta');
    }
}
