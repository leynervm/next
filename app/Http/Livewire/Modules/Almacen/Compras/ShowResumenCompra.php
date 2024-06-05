<?php

namespace App\Http\Livewire\Modules\Almacen\Compras;

use App\Helpers\GetPrice;
use App\Models\Kardex;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Serie;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Almacen\Entities\Compra;
use Modules\Almacen\Entities\Compraitem;

class ShowResumenCompra extends Component
{

    public Compra $compra;
    public $open = false;
    public $openprice = false;
    public $showigv = false;

    public $producto, $pricetype;
    public $producto_id, $almacen_id, $cantidad, $pricetype_id;
    public $newprice, $priceold, $pricemanual;
    public $stock = 0, $pricebuy = 0, $igv, $descuento = 0;
    public $subtotal = 0, $total = 0, $pricebuysoles = 0, $tipocambio = 0, $pricesale, $percent;
    public $almacens = [];
    public $serie = [];

    protected $listeners = ['updatecompraitem'];

    // protected $messages = [
    //     'serie.*.compraitem_id.required' => 'item de la compra es obligatorio',
    //     'serie.*.compraitem_id.integer' => 'item de la compra debe ser un número entero',
    //     'serie.*.compraitem_id.exists' => 'item de la compra debe existir en la tabla item de compras',
    //     'serie.*.serie.required' => 'serie del producto de compra es obligatorio',
    //     'serie.*.serie.min' => 'serie del producto de compra debe tener al mínimo',
    //     'serie.*.serie.unique' => 'serie del producto de compra debe ser unico en la tabla series',
    // ];


    public function mount()
    {
        $this->producto = new Producto();
        $this->pricetype = new Pricetype();
        $this->tipocambio = $this->compra->tipocambio ?? 0;
        $this->percent = $this->compra->sucursal->empresa->igv;

        if ($this->compra->sucursal->empresa->usarLista()) {
            $pricetypes = Pricetype::default()->orderBy('id', 'asc');
            if ($pricetypes->exists()) {
                $this->pricetype_id = $pricetypes->first()->id ?? null;
                $this->pricetype = $pricetypes->first();
            } else {
                $this->pricetype = Pricetype::orderBy('id', 'asc')->first();
                $this->pricetype_id = $this->pricetype->id ?? null;
            }
        }
    }

    public function render()
    {
        $productos = Producto::with(['promocions' => function ($query) {
            $query->disponibles();
        }])->select('id', 'name')
            ->withWhereHas('almacens')->orderBy('name', 'asc')->get();
        $pricetypes = Pricetype::orderBy('id', 'asc')->get();
        return view('livewire.modules.almacen.compras.show-resumen-compra', compact('productos', 'pricetypes'));
    }

    public function loadproducto($value)
    {
        $this->reset(['producto', 'almacen_id']);
        $this->producto = Producto::with('almacens')->find($value);
        $this->producto_id = $this->producto->id ?? null;
    }

    public function updatedAlmacens($value)
    {
        $this->stock = array_sum(array_column($this->almacens, 'cantidad')) ?? 0;
    }

    public function verifyproducto()
    {

        $stock = array_sum(array_column($this->almacens, 'cantidad'));

        // if ($stock <= 0) {
        //     $this->addError('almacens', 'La sumatoria de las cantidades debe ser mayor que cero.');
        //     return false;
        // }

        $validateData = $this->validate([
            'compra.id' => ['required', 'integer', 'min:1', 'exists:compras,id'],
            'producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            // 'almacen_id' => ['required', 'integer', 'min:1', 'exists:almacens,id'],
            'pricebuy' => ['required', 'numeric', 'gt:0', 'decimal:0,4'],
            'total' => ['required', 'numeric', 'gt:0', 'decimal:0,4'],
            'descuento' => ['nullable', 'numeric', 'min:0', 'decimal:0,4'],
            'igv' => ['nullable', 'numeric', 'min:0', 'decimal:0,4'],
            'pricesale' => [
                'nullable',
                Rule::requiredIf(!$this->compra->sucursal->empresa->usarLista()),
                'numeric', 'decimal:0,4', 'gt:0'
            ],
            // 'cantidad' => ['required', 'numeric', 'decimal:0,4', 'gt:0'],
            'almacens' => [
                'required', 'array', 'min:1',
            ],
            'almacens.*.cantidad' => [
                'nullable', 'numeric', 'min:0', 'decimal:0,2',
            ]
        ]);

        $importeitem =  number_format((($this->pricebuy + $this->igv) * $stock) - ($this->descuento * $stock), 3, '.', '');
        $importecompra = $this->compra->compraitems()->sum('total') + $importeitem;

        if (number_format($importecompra, 3, '.', '') > number_format($this->compra->total, 3, '.', '')) {
            $mensaje =  response()->json([
                'title' => 'MONTO DE ITEMS DE COMPRA SUPERA AL MONTO TOTAL DE COMPRA !',
                'text' => "El monto total de los items de la compra superan el monto total de la compra realizada."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        foreach ($this->almacens as $almacen => $value) {
            if ($value['cantidad'] > 0) {
                $compraitem = $this->compra->compraitems()
                    ->where('producto_id', $this->producto_id)
                    ->where('almacen_id', $almacen);

                if ($compraitem->exists()) {
                    $this->updatecompraitem($compraitem->first(), $value['cantidad']);
                    // $this->dispatchBrowserEvent('confirm-agregate-compra', $compraitem->first());
                } else {
                    $itemcompra = collect([
                        'almacen_id' => $almacen,
                        'cantidad' => $value['cantidad'],
                    ]);
                    $this->addproducto(response()->json($itemcompra)->getData());
                }
            }
        }

        if (number_format($importecompra, 3, '.', '') >= number_format($this->compra->total, 3, '.', '')) {
            $this->open = false;
        }

        $this->compra->refresh();
        $this->dispatchBrowserEvent('created');
        $this->resetValidation();
        $this->reset(['producto_id', 'almacens', 'pricebuy', 'pricebuysoles', 'pricesale', 'descuento', 'serie']);
    }

    public function addproducto($itemcompra)
    {
        DB::beginTransaction();
        try {
            $producto = Producto::find($this->producto_id);
            $productoAlmacen = $producto->almacens()
                ->where('almacen_id', $itemcompra->almacen_id)
                ->first();

            $preciosindsct = number_format($this->pricebuy + $this->descuento, 3, '.', '');
            $preciosindsct = number_format($this->compra->moneda->code == 'USD' ? $preciosindsct * $this->compra->tipocambio  : $preciosindsct, 3, '.', '');

            $preciocompra = number_format(($this->pricebuy + $this->igv) - $this->descuento, 3, '.', '');
            // $preciocompra =  $this->compra->moneda->code == 'USD' ? ($preciocompra * $this->compra->tipocambio) : $preciounitario;
            // $igv = $this->compra->moneda->code == 'USD' ? $this->igv * $this->compra->tipocambio  : $this->igv;
            // $descuento = $this->compra->moneda->code == 'USD' ? $this->descuento * $this->compra->tipocambio  : $this->descuento;

            $compraitem = $this->compra->compraitems()->create([
                'oldstock' => $productoAlmacen->pivot->cantidad ?? 0,
                'oldpricebuy' => $producto->pricebuy ?? 0,
                'oldpricesale' => $producto->pricesale ?? 0,
                'cantidad' => $itemcompra->cantidad,
                'pricebuy' => number_format($preciocompra, 3, '.', ''),
                'igv' => number_format($this->igv, 3, '.', ''),
                'descuento' => number_format($this->descuento, 3, '.', ''),
                'subtotal' => number_format($preciosindsct * $itemcompra->cantidad, 3, '.', ''),
                'total' => number_format($preciocompra * $itemcompra->cantidad, 3, '.', ''),
                'producto_id' => $this->producto_id,
                'almacen_id' => $itemcompra->almacen_id,
                'user_id' => auth()->user()->id,
            ]);

            // dd($compraitem);
            $producto->pricebuy =  $this->compra->moneda->code == 'USD' ? ($preciocompra * $this->compra->tipocambio) : $preciocompra;
            // if ($this->compra->moneda->code == 'USD') {
            //     $producto->pricebuy = $this->pricebuy * $this->compra->tipocambio;
            // } else {
            //     $producto->pricebuy = $this->pricebuy;
            // }

            if (!$this->compra->sucursal->empresa->usarLista()) {
                $producto->pricesale = $this->pricesale;
            }

            $producto->save();
            $compraitem->saveKardex(
                $producto->id,
                $itemcompra->almacen_id,
                $productoAlmacen->pivot->cantidad,
                $productoAlmacen->pivot->cantidad + $itemcompra->cantidad,
                $itemcompra->cantidad,
                '+',
                Kardex::ENTRADA_ALMACEN,
                $this->compra->referencia
            );
            $producto->almacens()->updateExistingPivot($itemcompra->almacen_id, [
                'cantidad' => $productoAlmacen->pivot->cantidad + $itemcompra->cantidad,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatecompraitem(Compraitem $compraitem, $cantidad)
    {
        DB::beginTransaction();
        try {
            $productoAlmacen = $compraitem->producto
                ->almacens()->where('almacen_id', $compraitem->almacen_id)
                ->first();

            $compraitem->descuento = $this->descuento;
            $compraitem->pricebuy = $this->pricebuy;
            $compraitem->cantidad = $compraitem->cantidad + (float)$cantidad;
            $compraitem->subtotal = number_format($this->pricebuy * $compraitem->cantidad, 3, '.', '');
            $compraitem->total = number_format(($this->pricebuy * $compraitem->cantidad) - ($this->descuento * $compraitem->cantidad), 3, '.', '');
            $compraitem->save();

            if ($compraitem->producto) {
                if ($this->compra->moneda->code == 'USD') {
                    $compraitem->producto->pricebuy = $this->pricebuy * $this->compra->tipocambio;
                    // $compraitem->producto->priceusbuy = $this->pricebuy;
                } else {
                    $compraitem->producto->pricebuy = $this->pricebuy;
                    // $compraitem->producto->priceusbuy = 0;
                }

                if ($this->compra->sucursal->empresa->uselistprice == 0) {
                    $compraitem->producto->pricesale = $this->pricesale;
                }

                $compraitem->producto->save();
            }

            if ($compraitem->kardex) {
                $compraitem->kardex->cantidad = $compraitem->kardex->cantidad + $cantidad;
                $compraitem->kardex->newstock = $compraitem->kardex->newstock + $cantidad;
                $compraitem->kardex->save();
            }

            $compraitem->producto->almacens()->updateExistingPivot($compraitem->almacen_id, [
                'cantidad' => $productoAlmacen->pivot->cantidad + $cantidad,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteitemcompra(Compraitem $compraitem)
    {
        $outseries = $compraitem->series()->whereNotNull('dateout')->count();
        if ($outseries) {
            $message = response()->json([
                'title' => 'No se puede eliminar el registro seleccionado !',
                'text' => 'Existen series relacionados en salidas, eliminarlo causará conflictos en los datos',
            ])->getData();

            $this->dispatchBrowserEvent('validation', $message);
            return false;
        } else {
            DB::beginTransaction();
            try {
                $productoAlmacen = $compraitem->producto->almacens()
                    ->where('almacen_id', $compraitem->almacen_id)->first();

                if ($compraitem->kardex) {
                    $compraitem->kardex()->delete();
                }

                $compraitem->producto->almacens()->updateExistingPivot($compraitem->almacen_id, [
                    'cantidad' => $productoAlmacen->pivot->cantidad - $compraitem->cantidad,
                ]);
                $compraitem->producto->pricebuy = $compraitem->oldpricebuy;
                $compraitem->producto->pricesale = $compraitem->oldpricesale;
                $compraitem->producto->save();
                $compraitem->series()->forceDelete();
                $compraitem->forceDelete();
                DB::commit();
                $this->resetValidation();
                $this->compra->refresh();
                $this->dispatchBrowserEvent('deleted');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function saveserie(Compraitem $compraitem)
    {
        $this->resetValidation(['serie']);
        $this->serie[$compraitem->id]['serie'] = trim(mb_strtoupper($this->serie[$compraitem->id]['serie'] ?? '', 'UTF-8'));
        $this->serie[$compraitem->id]['compraitem_id'] = $compraitem->id ?? null;

        $this->validate([
            "serie.*.compraitem_id" => ['required', 'integer', 'min:1', 'exists:compraitems,id'],
            "serie.*.serie" => [
                'required', 'min:4',
                new CampoUnique('series', 'serie', null, true)
            ],
        ]);

        $countSeries = $compraitem->series()->count();

        if ($countSeries >= $compraitem->cantidad) {
            $this->addError("serie.$compraitem->id.serie", 'el campo serie sobrepase la cantidad ingresada en compra.');
        } else {
            DB::beginTransaction();
            try {
                $compraitem->series()->create([
                    'serie' => $this->serie[$compraitem->id]['serie'],
                    'almacen_id' => $compraitem->almacen_id,
                    'producto_id' => $compraitem->producto_id,
                    'user_id' => auth()->user()->id,
                ]);

                DB::commit();
                $this->compra->refresh();
                $this->dispatchBrowserEvent('created');
                $this->reset(['serie']);
                $this->resetValidation(['serie.*']);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function deleteserie(Serie $serie)
    {
        if ($serie->dateout) {
            $message = response()
                ->json([
                    'title' => 'No se puede eliminar la serie ' . $serie->serie,
                    'text' => 'La serie se encuentra vinculado a un registro saliente, eliminarlo causará conflictos en los datos',
                ])->getData();

            $this->dispatchBrowserEvent('validation', $message);
        } else {
            $serie->forceDelete();
            $this->compra->refresh();
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function openmodalprice(Producto $producto, Pricetype $pricetype)
    {
        $this->reset(['newprice', 'priceold', 'pricetype', 'producto', 'pricemanual']);
        $this->resetValidation();
        $this->pricetype = $pricetype;
        $this->producto = $producto;
        $prices = GetPrice::getPriceProducto($producto, $pricetype->id)->getData();
        $this->priceold = $prices->oldPrice;
        $this->newprice = $prices->pricemanual ?? $prices->pricesale;
        $this->pricemanual = $producto
            ->pricetypes()
            ->where('pricetype_id', $pricetype->id)
            ->first();
        $this->openprice = true;
    }

    public function saveprecioventa()
    {
        $this->validate([
            'pricetype.id' => ['required', 'integer', 'min:1', 'exists:pricetypes,id'],
            'producto.id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'newprice' => ['required', 'decimal:0,4', 'min:0.1'],
        ]);

        $this->producto->pricetypes()->syncWithoutDetaching([
            $this->pricetype->id => [
                'price' => $this->newprice,
            ],
        ]);

        $this->resetValidation();
        $this->reset(['openprice', 'newprice', 'priceold', 'pricemanual', 'producto', 'producto_id']);
        $this->producto = new Producto();
    }

    public function deletepricemanual()
    {
        if ($this->pricemanual) {
            $this->producto->pricetypes()->detach($this->pricetype->id);
            $this->resetValidation();
            $this->reset(['openprice', 'newprice', 'priceold', 'producto', 'producto_id', 'pricemanual']);
            $this->producto = new Producto();
        }
    }

    public function updatedPricetypeId($value)
    {
        if ($value) {
            $this->pricetype = Pricetype::find($value);
            $this->pricetype_id = $this->pricetype->id;
        }
    }
}
