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
    public $producto, $pricetype;
    public $producto_id, $almacen_id, $cantidad;
    public $newprice, $priceold, $pricemanual;
    public $serie;
    public $pricebuy = 0;
    public $descuento = 0;
    public $importe = 0;
    public $pricebuysoles = 0;
    public $tipocambio = 0;
    public $pricesale;
    public $open = false;
    public $openprice = false;
    public $almacens = [];

    public $stock = 0;

    protected $listeners = ['updatecompraitem'];

    public function mount()
    {
        $this->producto = new Producto();
        $this->pricetype = new Pricetype();
        $this->tipocambio = $this->compra->tipocambio ?? 0;
    }

    public function render()
    {
        $productos = Producto::select('id', 'name')
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

    public function updatedProductoId($value)
    {
        $this->producto_id = $value == '' ? null : $value;
        $this->producto = Producto::with('almacens')->find($this->producto_id);
    }

    public function updatedAlmacens($value)
    {
        $this->stock = array_sum(array_column($this->almacens, 'cantidad')) ?? 0;
        $this->importe = ($this->pricebuy * $this->stock) - ($this->descuento * $this->stock);
    }

    public function updatedDescuento($value)
    {
        $this->descuento = trim($value) == '' ? 0 : $value;
        $this->stock = array_sum(array_column($this->almacens, 'cantidad')) ?? 0;
        $this->importe = ($this->pricebuy * $this->stock) - ($this->descuento * $this->stock);
    }

    public function updatedPricebuy($value)
    {
        $this->pricebuy = trim($value) == '' ? 0 : $value;
        $this->stock = array_sum(array_column($this->almacens, 'cantidad')) ?? 0;
        $this->importe = ($this->pricebuy * $this->stock) - ($this->descuento * $this->stock);
    }

    public function verifyproducto()
    {

        $stock = array_sum(array_column($this->almacens, 'cantidad'));

        if ($stock <= 0) {
            $this->addError('almacens', 'La sumatoria de las cantidades debe ser mayor que cero.');
            return false;
        }

        $validateData = $this->validate([
            'compra.id' => ['required', 'integer', 'min:1', 'exists:compras,id'],
            'producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            // 'almacen_id' => ['required', 'integer', 'min:1', 'exists:almacens,id'],
            'pricebuy' => ['required', 'numeric', 'decimal:0,4', 'gt:0'],
            'pricesale' => ['nullable', Rule::requiredIf($this->compra->sucursal->empresa->uselistprice == 0), 'numeric', 'decimal:0,4', 'gt:0'],
            // 'cantidad' => ['required', 'numeric', 'decimal:0,4', 'gt:0'],
            'almacens' => [
                'required', 'array', 'min:1',
            ],
            'almacens.*.cantidad' => [
                'nullable', 'numeric', 'min:0', 'decimal:0,2',
            ]
        ]);

        $importeitem =  number_format(($this->pricebuy * $stock) - ($stock * $this->descuento), 3, '.', '');
        $importecompra = $this->compra->compraitems()->sum('total') + $importeitem;

        if ($importecompra > $this->compra->total) {
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

        if ($importecompra >= $this->compra->total) {
            $this->open = false;
        }

        $this->compra->refresh();
        $this->dispatchBrowserEvent('created');
        $this->resetValidation();
        $this->reset(['producto_id', 'almacens', 'pricebuy', 'pricebuysoles', 'pricesale', 'descuento', 'importe', 'serie']);
    }

    public function addproducto($itemcompra)
    {
        DB::beginTransaction();
        try {
            $producto = Producto::find($this->producto_id);
            $productoAlmacen = $producto->almacens()
                ->where('almacen_id', $itemcompra->almacen_id)
                ->first();

            $compraitem = $this->compra->compraitems()->create([
                'oldstock' => $productoAlmacen->pivot->cantidad ?? 0,
                'oldpricebuy' => $producto->pricebuy ?? 0,
                'oldpricesale' => $producto->pricesale ?? 0,
                'cantidad' => $itemcompra->cantidad,
                'pricebuy' => $this->pricebuy,
                'igv' => 0,
                'descuento' => $this->descuento,
                'subtotal' => number_format($this->pricebuy * $itemcompra->cantidad, 3, '.', ''),
                'total' => number_format(($this->pricebuy * $itemcompra->cantidad) - ($this->descuento * $itemcompra->cantidad), 3, '.', ''),
                'producto_id' => $this->producto_id,
                'almacen_id' => $itemcompra->almacen_id,
                'user_id' => auth()->user()->id,
            ]);

            if ($this->compra->moneda->code == 'USD') {
                $producto->pricebuy = $this->pricebuy * $this->compra->tipocambio;
                $producto->priceusbuy = $this->pricebuy;
            } else {
                $producto->pricebuy = $this->pricebuy;
                $producto->priceusbuy = 0;
            }

            if ($this->compra->sucursal->empresa->uselistprice == 0) {
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
                    $compraitem->producto->priceusbuy = $this->pricebuy;
                } else {
                    $compraitem->producto->pricebuy = $this->pricebuy;
                    $compraitem->producto->priceusbuy = 0;
                }

                if ($this->compra->sucursal->empresa->uselistprice == 0) {
                    $compraitem->producto->pricesale = $this->pricesale;
                }

                $compraitem->producto->save();
            }

            $compraitem->updateKardex($compraitem->id, $cantidad);
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

                $compraitem->saveKardex(
                    $compraitem->producto_id,
                    $compraitem->almacen_id,
                    $productoAlmacen->pivot->cantidad,
                    $productoAlmacen->pivot->cantidad - $compraitem->cantidad,
                    $compraitem->cantidad,
                    '-',
                    Kardex::SALIDA_ANULACION_ITEMCOMPRA,
                    $this->compra->referencia
                );

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
        $this->resetValidation(['serie.*']);
        $this->serie[$compraitem->id]['serie'] = trim(mb_strtoupper($this->serie[$compraitem->id]['serie'] ?? '', 'UTF-8'));
        $this->serie[$compraitem->id]['compraitem_id'] = $compraitem->id ?? null;

        $this->validate([
            "serie.$compraitem->id.compraitem_id" => ['required', 'integer', 'min:1', 'exists:compraitems,id'],
            "serie.$compraitem->id.serie" => ['required', 'min:2', new CampoUnique('series', 'serie', null, true)],
        ]);

        $countSeries = $compraitem->series()->count();

        if ($countSeries >= $compraitem->cantidad) {
            $this->addError("serie.$compraitem->id.serie", 'Serie sobrepase la cantidad ingresada en compra.');
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

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-show-resumen-compra');
    }
}
