<?php

namespace App\Http\Livewire\Modules\Ventas\Ventas;

use App\Helpers\GetPrice;
use App\Models\Category;
use App\Models\Moneda;
use App\Models\Pricetype;
use App\Models\Serie;
use App\Rules\ValidateStock;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Almacen;
use App\Models\Carshoop;
use App\Models\Carshoopserie;
use App\Models\Kardex;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Modules\Ventas\Entities\Venta;

class CreateVenta extends Component
{

    use WithPagination;

    public $empresa, $sucursal, $pricetype, $moneda, $producto;
    public $open = false;
    public $disponibles = 1;
    public $almacendefault;
    public $producto_id, $serie_id, $almacen_id, $pricetype_id;

    public $search = '';
    public $searchserie = '';
    public $searchalmacen = '';
    public $searchcategory = '';
    public $searchsubcategory = '';
    public $searchmarca = '';
    public $cart = [];
    public $seriescarrito = [];
    public $subcategories = [];

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'producto'],
        'searchcategory' => ['except' => '', 'as' => 'categoria'],
        'searchsubcategory' => ['except' => '', 'as' => 'subcategoria'],
        'searchmarca' => ['except' => '', 'as' => 'marca'],
    ];

    protected $messages = [
        'cart.*.almacen_id.required' => 'Almacen del producto requerido',
        'cart.*.price.required' => 'Precio del producto requerido',
        'cart.*.price.min' => 'Precio del producto deber se mayor a 0.0001',
        'cart.*.cantidad.required' => 'Cantidad del producto requerido',
        'cart.*.cantidad.min' => 'Cantidad del producto debe ser mayor a 0',
        'cart.*.serie.required' => 'Serie del producto requerido',
    ];

    public function mount($pricetype, Moneda $moneda)
    {
        $this->producto = new Producto();
        $this->sucursal = auth()->user()->sucursal;
        $this->empresa = auth()->user()->sucursal->empresa;
        $this->pricetype = $pricetype;
        $this->moneda = $moneda;
        $this->pricetype_id = $pricetype->id ?? null;

        if (trim($this->searchcategory) !== '') {
            $this->subcategories = Category::with('subcategories')->find($this->searchcategory)->subcategories()
                ->whereHas('productos')->orderBy('orden', 'asc')->orderBy('name', 'asc')->get();
        }

        if (count(auth()->user()->sucursal->almacens) > 0) {
            $this->almacendefault = auth()->user()->sucursal->almacens()->first();
            $this->almacen_id = $this->almacendefault->id;
        }
    }

    public function render()
    {

        $almacens = [];
        if ($this->almacen_id) {
            $almacens[] = $this->almacen_id;
        }

        // $miproducto = Producto::with(['almacens' => function ($query) use ($almacens) {
        //     $query->whereIn('almacens.id', $almacens)
        //         ->where('cantidad', '>', 0);
        // }])->find(39);

        $productos = Producto::withWhereHas('almacens', function ($query) use ($almacens) {
            $query->whereIn('almacens.id', $almacens);
            if ($this->disponibles) {
                $query->where('cantidad', '>', 0);
            }
        })->with('seriesdisponibles', function ($query) {
            // $query->whereIn('almacen_id', $almacens);
            $query->where('almacen_id', $this->almacen_id);
        })->with('promocions', function ($query) {
            $query->disponibles();
        })->with(['images', 'category', 'marca', 'unit']);

        if (trim($this->search) !== "") {
            $productos->where('name', 'ilike', $this->search . '%');
        }

        if (trim($this->searchmarca) !== "") {
            $productos->where('marca_id', $this->searchmarca);
        }

        if (trim($this->searchcategory) !== "") {
            $productos->where('category_id', $this->searchcategory);
        }

        if (trim($this->searchsubcategory) !== "") {
            $productos->where('subcategory_id', $this->searchsubcategory);
        }

        $productos = $productos->orderBy('name', 'asc')->paginate();
        $marcas = Marca::whereHas('productos')->orderBy('name', 'asc')->get();
        $categories = Category::whereHas('productos')->orderBy('orden', 'asc')->get();
        $pricetypes = Pricetype::orderBy('id', 'asc')->get();
        $almacens = Almacen::whereHas('productos')->orderBy('name', 'asc')->get();

        return view('livewire.modules.ventas.ventas.create-venta', compact('productos', 'pricetypes', 'categories', 'almacens', 'marcas'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSearchcategory($value)
    {
        $this->resetPage();
        $this->reset(['searchsubcategory', 'subcategories']);
        $this->resetValidation();
        if (trim($value) !== '') {
            $this->subcategories = Category::with('subcategories')->find($value)->subcategories()
                ->whereHas('productos')->orderBy('orden', 'asc')->orderBy('name', 'asc')->get();
        }
    }

    public function updatedSearchmarca()
    {
        $this->resetPage();
    }

    public function updatedSearchsubcategory()
    {
        $this->resetPage();
    }

    public function updatedAlmacenId($value)
    {
        $this->resetPage();
        $this->resetValidation();
        if ($value) {
            $this->almacendefault = Almacen::find($value);
        }
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

    public function getProductoBySerie()
    {

        $this->searchserie = trim($this->searchserie);
        $this->validate([
            'searchserie' => ['required', 'string', 'min:4'],
            'moneda.id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
        ]);

        $cantidad = 1;
        $existskardex = false;
        $date = now('America/Lima');
        $promocion_id = null;
        $carshoopitems = collect([]);

        DB::beginTransaction();
        try {

            $serieProducto = Serie::with('producto')->disponibles()->whereRaw('UPPER(serie) = ?', trim(mb_strtoupper($this->searchserie, "UTF-8")))
                ->whereIn('almacen_id', auth()->user()->sucursal->almacens()->pluck('almacen_id'))->first();

            if (empty($serieProducto)) {
                $mensaje =  response()->json([
                    'title' => 'SERIE NO SE ENCUENTRA DISPONIBLE !',
                    'text' => "La serie $this->searchserie no se encuentra disponible en la base de datos."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            if (Carshoopserie::where('serie_id', $serieProducto->id)->exists()) {
                $mensaje =  response()->json([
                    'title' => 'SERIE YA SE ENCUENTRA REGISTRADO EN EL CARRITO !',
                    'text' => "La serie $serieProducto->serie ya se encuentra registrado en los items de la venta."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            if ($serieProducto->isDisponible()) {
                $serieProducto->status = Serie::SALIDA;
                $serieProducto->dateout = $date;
                $serieProducto->save();
            } else {
                $mensaje =  response()->json([
                    'title' => 'SERIE NO SE ENCUENTRA DISPONIBLE !',
                    'text' => "La serie $serieProducto->serie ya no se encuentra disponible en este momento."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $this->validate([
                'searchserie' => [
                    new ValidateStock($serieProducto->producto_id, $serieProducto->almacen_id, $cantidad)
                ],
            ]);
            $stock = $serieProducto->producto->almacens->find($serieProducto->almacen_id)->pivot->cantidad;

            if ($stock && $stock > 0) {
                if (($stock - $cantidad) < 0) {
                    $mensaje =  response()->json([
                        'title' => 'CANTIDAD SUPERA EL STOCK DISPONIBLE DEL PRODUCTO !',
                        'text' => "La cantidad supera el stock disponible en almacén del producto seleccionado."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }
            }

            $precios = getPrecio($serieProducto->producto, $this->sucursal->empresa->usarLista() ? $this->pricetype_id : null,  $this->sucursal->empresa->tipocambio)->getData();

            if ($precios->success) {
                if ($this->sucursal->empresa->usarLista() && !$precios->existsrango) {
                    $mensaje =  response()->json([
                        'title' => 'RANGO DE PRECIO DEL PRODUCTO NO DISPONIBLE !',
                        'text' => "No se encontraron rangos de precio de compra del producto."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                $precioProducto = precio_producto($serieProducto->producto, $precios, $this->sucursal->empresa->tipocambio);
                if ($precioProducto->pricePEN > 0) {
                    $price = $this->moneda->code == 'USD' ? $precioProducto->priceUSD : $precioProducto->pricePEN;
                } else {
                    $mensaje =  response()->json([
                        'title' => 'NO SE PUDO OBTENER PRECIO DE VENTA DEL PRODUCTO !',
                        'text' => "No se encontraron registros de precios del producto seleccionado $precios->error"
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                $promocioncombos = $serieProducto->producto->promocions()->disponibles()->with('itempromos.producto');

                if ($promocioncombos->exists()) {
                    $sumatoriaComboProducto = 0;
                    $firstpromocion = $promocioncombos->first();

                    if ($firstpromocion->isDisponible() && $firstpromocion->isAvailable()) {
                        if ($firstpromocion->limit > 0 && $cantidad + $firstpromocion->outs > $firstpromocion->limit) {
                            $mensaje =  response()->json([
                                'title' => "CANTIDAD SOBREPASA EL STOCK DEL PRODUCTO EN PROMOCIÓN !",
                                'text' => "La cantidad sobrepasa el límite de unidades en promoción del producto [" . $firstpromocion->limit - $firstpromocion->outs . " " . $firstpromocion->producto->unit->name . " DISPONIBLES]."
                            ])->getData();
                            $this->dispatchBrowserEvent('validation', $mensaje);
                            return false;
                        }

                        if ($firstpromocion->limit > 0 && $firstpromocion->outs >= $firstpromocion->limit) {
                            $mensaje =  response()->json([
                                'title' => "STOCK DEL PRODUCTO EN PROMOCIÓN AGOTADO !",
                                'text' => "Límite de unidades en promoción del producto agotado."
                            ])->getData();
                            $this->dispatchBrowserEvent('validation', $mensaje);
                            return false;
                        }

                        if (count($firstpromocion->itempromos) > 0) {
                            foreach ($firstpromocion->itempromos as $itempromo) {
                                $priceItemCombo = 0;
                                $stockCombo = $itempromo->producto->almacens->find($this->almacen_id)->pivot->cantidad ?? 0;

                                if ($stockCombo && $stockCombo > 0) {
                                    if (($stockCombo - $cantidad) < 0) {
                                        $mensaje =  response()->json([
                                            'title' => "CANTIDAD DEL PRODUCTO EN COMBO SUPERA EL STOCK DISPONIBLE EN ALMACÉN !",
                                            'text' => "La cantidad del producto $itempromo->producto->name ,en combo supera el stock disponible en almacén seleccionado."
                                        ])->getData();
                                        $this->dispatchBrowserEvent('validation', $mensaje);
                                        return false;
                                    }
                                } else {
                                    $mensaje =  response()->json([
                                        'title' => "STOCK DEL PRODUCTO EN COMBO AGOTADO EN ALMACÉN !",
                                        'text' => "La cantidad del producto " . $itempromo->producto->name . ", en combo está agotado para almacén " . $this->almacendefault->name
                                    ])->getData();
                                    $this->dispatchBrowserEvent('validation', $mensaje);
                                    return false;
                                }

                                $preciosCombo = getPrecio($itempromo->producto, $this->sucursal->empresa->usarLista() ? $this->pricetype_id : null, $this->sucursal->empresa->tipocambio)->getData();
                                if ($preciosCombo->success) {
                                    $precioProducto = precio_producto($itempromo->producto, $preciosCombo, $this->sucursal->empresa->tipocambio);
                                    $priceItemCombo = $this->moneda->code == 'USD' ? $precioProducto->priceUSD : $precioProducto->pricePEN;
                                    if ($itempromo->isDescuento()) {
                                        $pricebuyCombo = $this->moneda->code == 'USD' ? $preciosCombo->pricebuyDolar : $preciosCombo->pricebuy;
                                        $descuentocombo = number_format((($priceItemCombo - $pricebuyCombo) * $itempromo->descuento) / 100, $preciosCombo->decimal, '.', '');
                                        $priceItemCombo = number_format($priceItemCombo - $descuentocombo, $preciosCombo->decimal, '.', '');
                                    }
                                    if ($itempromo->isGratuito()) {
                                        $priceItemCombo = $this->moneda->code == 'USD' ? $preciosCombo->pricebuyDolar : $preciosCombo->pricebuy;
                                    }
                                    $sumatoriaComboProducto = $sumatoriaComboProducto + $priceItemCombo;
                                }

                                $seriesdisponibles =  $itempromo->producto->series()->disponibles()
                                    ->where('almacen_id', $this->almacen_id)->exists();

                                $carshoopitems->push([
                                    'date' => now('America/Lima'),
                                    'pricebuy' => $itempromo->producto->pricebuy,
                                    'price' => $priceItemCombo,
                                    'stock' => $stockCombo,
                                    'requireserie' => $seriesdisponibles ? 1 : 0,
                                    'newstock' => $stockCombo - $cantidad,
                                    'producto_id' => $itempromo->producto_id
                                ]);

                                $itempromo->producto->almacens()->updateExistingPivot($this->almacen_id, [
                                    'cantidad' => $stockCombo - $cantidad,
                                ]);
                            }
                            // $sumatoriaComboProducto = get_sumatoria_combos($comboProducto, $this->sucursal->empresa->usarLista() ? $this->pricetype_id : null, $this->sucursal->empresa->tipocambio);
                            $price = number_format($price + $sumatoriaComboProducto, $precios->decimal, '.', '');
                        }

                        $promocion_id = $firstpromocion->id;
                        $firstpromocion->outs = $firstpromocion->outs + $cantidad;
                        $firstpromocion->save();
                    }
                }

                $existsInCart = Carshoop::where('producto_id', $serieProducto->producto_id)
                    ->where('almacen_id', $serieProducto->almacen_id)
                    ->where('moneda_id', $this->moneda->id)
                    ->where('gratuito', '0')
                    ->where('user_id', auth()->user()->id)
                    ->where('sucursal_id', auth()->user()->sucursal_id)
                    ->where('mode', Almacen::DISMINUIR_STOCK)
                    ->where('promocion_id', $promocion_id)
                    ->where('cartable_type', Venta::class);

                if ($existsInCart->exists()) {
                    $carshoop = $existsInCart->first();
                    $carshoop->cantidad = $carshoop->cantidad + $cantidad;
                    $carshoop->pricebuy = $serieProducto->producto->pricebuy;
                    $carshoop->price = $price;
                    $carshoop->subtotal =  $price * $carshoop->cantidad;
                    $carshoop->total = $price * $carshoop->cantidad;
                    $carshoop->save();

                    if ($carshoop->kardex) {
                        $existskardex = true;
                        $carshoop->kardex->cantidad = $carshoop->kardex->cantidad +  $cantidad;
                        $carshoop->kardex->newstock = $carshoop->kardex->newstock - $cantidad;
                        $carshoop->kardex->save();
                    }

                    if ($carshoopitems->count() > 0) {
                        if (count($carshoop->carshoopitems) == 0) {
                            foreach ($carshoopitems as $itemcombo) {
                                $carshoop->carshoopitems()->create([
                                    'pricebuy' => $itemcombo["pricebuy"],
                                    'price' => $itemcombo["price"],
                                    'requireserie' => $itemcombo["requireserie"],
                                    'producto_id' => $itemcombo["producto_id"]
                                ]);
                            }
                        }
                    }
                } else {
                    $carshoop = Carshoop::create([
                        'date' => $date,
                        'cantidad' => $cantidad,
                        'pricebuy' => $serieProducto->producto->pricebuy,
                        'price' => $price,
                        'igv' => 0,
                        'subtotal' => $price * $cantidad,
                        'total' => $price * $cantidad,
                        'gratuito' => 0,
                        'status' => 0,
                        'promocion_id' => $promocion_id,
                        'producto_id' => $serieProducto->producto_id,
                        'almacen_id' => $serieProducto->almacen_id,
                        'moneda_id' => $this->moneda->id,
                        'user_id' => auth()->user()->id,
                        'sucursal_id' => $this->sucursal->id,
                        'mode' => Almacen::DISMINUIR_STOCK,
                        'cartable_type' => Venta::class,
                    ]);

                    if ($carshoopitems->count() > 0) {
                        foreach ($carshoopitems as $itemcombo) {
                            $carshoop->carshoopitems()->create([
                                'pricebuy' => $itemcombo["pricebuy"],
                                'price' => $itemcombo["price"],
                                'requireserie' => $itemcombo["requireserie"],
                                'producto_id' => $itemcombo["producto_id"]
                            ]);
                        }
                    }
                }

                $carshoop->carshoopseries()->create([
                    'date' =>  $date,
                    'serie_id' => $serieProducto->id,
                    'user_id' => auth()->user()->id
                ]);

                if (!$existskardex) {
                    $carshoop->saveKardex(
                        $serieProducto->producto_id,
                        $serieProducto->almacen_id,
                        $stock,
                        $stock - $cantidad,
                        $cantidad,
                        Almacen::SALIDA_ALMACEN,
                        Kardex::ADD_VENTAS,
                        null
                    );
                }

                $serieProducto->producto->almacens()->updateExistingPivot($serieProducto->almacen_id, [
                    'cantidad' => $stock - $cantidad,
                ]);
            } else {
                $mensaje =  response()->json([
                    'title' => 'NO SE PUDO OBTENER PRECIO DE VENTA DEL PRODUCTO !',
                    'text' => "No se encontraron registros de precios del producto seleccionado $precios->error"
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
            DB::commit();
            $this->reset(['searchserie']);
            $this->resetValidation();
            $this->dispatchBrowserEvent('show-resumen-venta');
            $this->dispatchBrowserEvent('toast', toastJSON('Registrado en carrito de ventas'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function addtocar($formData, Producto $producto)
    {

        $price = $formData["price"] ?? "";
        $serie = $formData["serie"] ?? null;
        $cantidad = $formData["cantidad"] ?? 1;
        $promocion_id = null;
        $carshoopitems = collect([]);

        $this->cart[$producto->id]["price"] = $price;
        $this->cart[$producto->id]["almacen_id"] = $this->almacen_id;
        $this->cart[$producto->id]["serie"] = $serie;
        $this->cart[$producto->id]["cantidad"] = $cantidad;
        $validateDate = $this->validate([
            "cart.$producto->id.price" => [
                'required', 'numeric', 'min:0', 'decimal:0,4', 'gt:0',
            ],
            "cart.$producto->id.almacen_id" => [
                'required', 'integer', 'exists:almacens,id', new ValidateStock($producto->id, $this->almacen_id)
            ],
            "cart.$producto->id.cantidad" => [
                'required', 'numeric', 'min:1', 'integer',
                new ValidateStock($producto->id, $this->almacen_id, $cantidad)
            ],
            "cart.$producto->id.serie" => [
                'nullable', Rule::requiredIf($producto->seriesdisponibles()->where('almacen_id', $this->almacen_id)->exists()),
                'string', 'min:4',
                // new ValidateSerieRequerida($producto->id, $this->almacen_id, $serie),
            ],
            "moneda.id" => [
                'required', 'integer', 'min:1', 'exists:monedas,id'
            ],
        ]);

        $stock = $producto->almacens->find($this->almacen_id)->pivot->cantidad;

        if ($stock && $stock > 0) {
            if (($stock - $cantidad) < 0) {
                $mensaje =  response()->json([
                    'title' => 'CANTIDAD SUPERA EL STOCK DISPONIBLE DEL PRODUCTO !',
                    'text' => "La cantidad del producto seleccionado."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        } else {
            $mensaje =  response()->json([
                'title' => 'STOCK DEL PRODUCTO AGOTADO EN ALMACÉN !',
                'text' => "La cantidad el producto " . $producto->name . ", en combo está agotado para almacén " . $this->almacendefault->name
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        DB::beginTransaction();
        try {
            // $promodisponibles = $producto->promocions()->disponibles()->get();
            if (count($producto->promocions) > 0) {
                $firstpromocion = $producto->promocions->first();
                if ($firstpromocion->isDisponible() && $firstpromocion->isAvailable()) {
                    // $promocioncombos = $producto->promocions()->disponibles()->combos()->with('itempromos.producto');

                    if ($firstpromocion->limit > 0 && $cantidad + $firstpromocion->outs > $firstpromocion->limit) {
                        $mensaje =  response()->json([
                            'title' => "CANTIDAD SOBREPASA EL STOCK DEL PRODUCTO EN PROMOCIÓN !",
                            'text' => "La cantidad sobrepasa el límite de unidades en promoción del producto [" . $firstpromocion->limit - $firstpromocion->outs . " " . $producto->unit->name . " DISPONIBLES]."
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }

                    if ($firstpromocion->limit > 0 && $firstpromocion->outs >= $firstpromocion->limit) {
                        $mensaje =  response()->json([
                            'title' => "STOCK DEL PRODUCTO EN PROMOCIÓN AGOTADO !",
                            'text' => "Límite de unidades en promoción del producto agotado."
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }

                    if (count($firstpromocion->itempromos) > 0) {
                        foreach ($firstpromocion->itempromos as $itempromo) {
                            $priceItemCombo = 0;
                            $stockCombo = $itempromo->producto->almacens->find($this->almacen_id)->pivot->cantidad ?? 0;

                            if ($stockCombo && $stockCombo > 0) {
                                if (($stockCombo - $cantidad) < 0) {
                                    $mensaje =  response()->json([
                                        'title' => "CANTIDAD DEL PRODUCTO EN COMBO SUPERA EL STOCK DISPONIBLE EN ALMACÉN !",
                                        'text' => "La cantidad del producto $itempromo->producto->name ,en combo supera el stock disponible en almacén seleccionado."
                                    ])->getData();
                                    $this->dispatchBrowserEvent('validation', $mensaje);
                                    return false;
                                }
                            } else {
                                $mensaje =  response()->json([
                                    'title' => "STOCK DEL PRODUCTO EN COMBO AGOTADO EN ALMACÉN !",
                                    'text' => "La cantidad del producto " . $itempromo->producto->name . ", en combo está agotado para almacén " . $this->almacendefault->name
                                ])->getData();
                                $this->dispatchBrowserEvent('validation', $mensaje);
                                return false;
                            }

                            $preciosCombo = getPrecio($itempromo->producto, $this->sucursal->empresa->usarLista() ? $this->pricetype_id : null, $this->sucursal->empresa->tipocambio)->getData();
                            if ($preciosCombo->success) {
                                $precioProducto = precio_producto($itempromo->producto, $preciosCombo, $this->sucursal->empresa->tipocambio);
                                $priceItemCombo = $this->moneda->code == 'USD' ? $precioProducto->priceUSD : $precioProducto->pricePEN;
                                if ($itempromo->isDescuento()) {
                                    $pricebuyCombo = $this->moneda->code == 'USD' ? $preciosCombo->pricebuyDolar : $preciosCombo->pricebuy;
                                    $descuentocombo = number_format((($priceItemCombo - $pricebuyCombo) * $itempromo->descuento) / 100, $preciosCombo->decimal, '.', '');
                                    $priceItemCombo = number_format($priceItemCombo - $descuentocombo, $preciosCombo->decimal, '.', '');
                                }
                            }

                            $seriesdisponibles =  $itempromo->producto->series()->disponibles()
                                ->where('almacen_id', $this->almacen_id)->exists();

                            $carshoopitems->push([
                                'date' => now('America/Lima'),
                                'pricebuy' => $itempromo->producto->pricebuy,
                                'price' => $priceItemCombo,
                                'stock' => $stockCombo,
                                'requireserie' => $seriesdisponibles ? 1 : 0,
                                'newstock' => $stockCombo - $cantidad,
                                'producto_id' => $itempromo->producto_id
                            ]);

                            $itempromo->producto->almacens()->updateExistingPivot($this->almacen_id, [
                                'cantidad' => $stockCombo - $cantidad,
                            ]);
                        }
                    }

                    $promocion_id = $firstpromocion->id;
                    $firstpromocion->outs = $firstpromocion->outs + $cantidad;
                    $firstpromocion->save();
                }
            }

            $existskardex = false;
            $date = now('America/Lima');
            $existsInCart = Carshoop::where('producto_id', $producto->id)
                ->where('almacen_id', $this->almacen_id)
                ->where('moneda_id', $this->moneda->id)
                ->where('promocion_id', $promocion_id)
                ->where('gratuito', '0')
                ->where('user_id', auth()->user()->id)
                ->where('sucursal_id', auth()->user()->sucursal_id)
                ->where('mode', Almacen::DISMINUIR_STOCK)
                ->where('cartable_type', Venta::class);

            // dd($promocion_id);

            if ($existsInCart->exists()) {
                $carshoop = $existsInCart->first();
                $carshoop->cantidad = $carshoop->cantidad + $cantidad;
                $carshoop->pricebuy = $producto->pricebuy;
                $carshoop->price = $price;
                $carshoop->subtotal =  $price * $carshoop->cantidad;
                $carshoop->total = $price * $carshoop->cantidad;
                $carshoop->save();

                if ($carshoop->kardex) {
                    $existskardex = true;
                    $carshoop->kardex->cantidad = $carshoop->kardex->cantidad +  $cantidad;
                    $carshoop->kardex->newstock = $carshoop->kardex->newstock - $cantidad;
                    $carshoop->kardex->save();
                }

                if ($carshoopitems->count() > 0) {
                    if (count($carshoop->carshoopitems) == 0) {
                        foreach ($carshoopitems as $itemcombo) {
                            $carshoop->carshoopitems()->create([
                                'pricebuy' => $itemcombo["pricebuy"],
                                'price' => $itemcombo["price"],
                                'requireserie' => $itemcombo["requireserie"],
                                'producto_id' => $itemcombo["producto_id"]
                            ]);
                        }
                    }
                }
            } else {
                $carshoop = Carshoop::create([
                    'date' => $date,
                    'cantidad' => $cantidad,
                    'pricebuy' => $producto->pricebuy,
                    'price' => $price,
                    'igv' => 0,
                    'subtotal' => $price * $cantidad,
                    'total' => $price * $cantidad,
                    'gratuito' => 0,
                    'status' => 0,
                    'producto_id' => $producto->id,
                    'almacen_id' => $this->almacen_id,
                    'moneda_id' => $this->moneda->id,
                    'user_id' => auth()->user()->id,
                    'sucursal_id' => $this->sucursal->id,
                    'mode' => Almacen::DISMINUIR_STOCK,
                    'promocion_id' => $promocion_id,
                    'cartable_type' => Venta::class,
                ]);

                if ($carshoopitems->count() > 0) {
                    foreach ($carshoopitems as $itemcombo) {
                        $carshoop->carshoopitems()->create([
                            'pricebuy' => $itemcombo["pricebuy"],
                            'price' => $itemcombo["price"],
                            'requireserie' => $itemcombo["requireserie"],
                            'producto_id' => $itemcombo["producto_id"]
                        ]);
                    }
                }
            }

            if (!empty($serie)) {
                $serieProducto = Serie::disponibles()->where('serie', trim(mb_strtoupper($serie, "UTF-8")))->first();

                if ($serieProducto) {
                    if (Carshoopserie::where('serie_id', $serieProducto->id)->exists()) {
                        $mensaje =  response()->json([
                            'title' => 'SERIE YA SE ENCUENTRA REGISTRADO EN EL CARRITO !',
                            'text' => "La serie $serieProducto->serie ya se encuentra registrado en los items de la venta."
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }

                    if ($serieProducto->isDisponible()) {
                        $serieProducto->status = Serie::SALIDA;
                        $serieProducto->dateout = $date;
                        $serieProducto->save();
                    } else {
                        $mensaje =  response()->json([
                            'title' => 'SERIE NO SE ENCUENTRA DISPONIBLE !',
                            'text' => "La serie $serieProducto->serie ya no se encuentra disponible en este momento."
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }

                    $carshoop->carshoopseries()->create([
                        'date' =>  $date,
                        'serie_id' => $serieProducto->id,
                        'user_id' => auth()->user()->id
                    ]);
                } else {
                    $mensaje =  response()->json([
                        'title' => 'SERIE NO SE ENCUENTRA DISPONIBLE !',
                        'text' => "La serie $serie no se encuentra disponible en la base de datos."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }
            }

            if (!$existskardex) {
                $carshoop->saveKardex(
                    $producto->id,
                    $this->almacen_id,
                    $stock,
                    $stock - $cantidad,
                    $cantidad,
                    Almacen::SALIDA_ALMACEN,
                    Kardex::ADD_VENTAS,
                    null
                );
            }

            $producto->almacens()->updateExistingPivot($this->almacen_id, [
                'cantidad' => $stock - $cantidad,
            ]);
            DB::commit();
            $this->reset(['searchserie']);
            $this->resetValidation();
            $this->dispatchBrowserEvent('show-resumen-venta');
            $this->dispatchBrowserEvent('reset-form', $producto->id);
            $this->dispatchBrowserEvent('toast', toastJSON('Registrado en carrito de ventas'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
