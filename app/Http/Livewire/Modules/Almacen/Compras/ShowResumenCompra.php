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
    public $pricebuysoles = 0;
    public $tipocambio = 0;
    public $pricesale;
    public $open = false;
    public $openprice = false;

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
            ->withWhereHas('almacens', function ($query) {
                $query->where('sucursal_id', $this->compra->sucursal_id);
            })->orderBy('name', 'asc')->get();
        $pricetypes = Pricetype::orderBy('id', 'asc')->get();
        return view('livewire.modules.almacen.compras.show-resumen-compra', compact('productos', 'pricetypes'));
    }

    public function loadproducto($value)
    {
        $this->reset(['producto', 'almacen_id']);
        $this->producto = Producto::with([
            'almacens' => function ($query) {
                $query->where('sucursal_id', $this->compra->sucursal_id);
            }
        ])->find($value);
        $this->producto_id = $this->producto->id ?? null;
    }

    public function verifyproducto()
    {
        $validateData = $this->validate([
            'compra.id' => ['required', 'integer', 'min:1', 'exists:compras,id'],
            'producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'almacen_id' => ['required', 'integer', 'min:1', 'exists:almacens,id'],
            'pricebuy' => ['required', 'numeric', 'decimal:0,4', 'gt:0'],
            'pricesale' => ['nullable', Rule::requiredIf($this->compra->sucursal->empresa->uselistprice == 0), 'numeric', 'decimal:0,4', 'gt:0'],
            'cantidad' => ['required', 'numeric', 'decimal:0,4', 'gt:0'],
        ]);

        $compraitem = $this->compra->compraitems()
            ->where('producto_id', $this->producto_id)
            ->where('almacen_id', $this->almacen_id);

        if ($compraitem->exists()) {
            $this->dispatchBrowserEvent('confirm-agregate-compra', $compraitem->first());
        } else {
            $this->addproducto();
        }
    }

    public function addproducto()
    {
        $validateData = $this->validate([
            'compra.id' => ['required', 'integer', 'min:1', 'exists:compras,id'],
            'producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'almacen_id' => ['required', 'integer', 'min:1', 'exists:almacens,id'],
            'pricebuy' => ['required', 'numeric', 'decimal:0,4', 'gt:0'],
            'pricesale' => [
                'nullable', Rule::requiredIf($this->compra->sucursal->empresa->uselistprice == 0),
                'numeric', 'decimal:0,4', 'gt:0'
            ],
            'cantidad' => ['required', 'numeric', 'decimal:0,4', 'gt:0'],
        ]);

        DB::beginTransaction();
        try {
            $producto = Producto::find($this->producto_id);
            $productoAlmacen = $producto
                ->almacens()
                ->where('almacen_id', $this->almacen_id)
                ->first();

            $compraitem = $this->compra->compraitems()->create([
                'oldstock' => $productoAlmacen->pivot->cantidad ?? 0,
                'oldpricebuy' => $producto->pricebuy ?? 0,
                'oldpricesale' => $producto->pricesale ?? 0,
                'cantidad' => $this->cantidad,
                'pricebuy' => $this->pricebuy,
                'igv' => 0,
                'subtotal' => number_format($this->pricebuy * $this->cantidad, 2, '.', ''),
                'producto_id' => $this->producto_id,
                'almacen_id' => $this->almacen_id,
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
            $compraitem->saveKardex($this->compra->sucursal_id, $producto->id, $this->almacen_id, $productoAlmacen->pivot->cantidad, $productoAlmacen->pivot->cantidad + $this->cantidad, $this->cantidad, '+', Kardex::ENTRADA_ALMACEN, $this->compra->referencia);
            $producto->almacens()->updateExistingPivot($this->almacen_id, [
                'cantidad' => $productoAlmacen->pivot->cantidad + $this->cantidad,
            ]);

            $this->compra->counter = $this->compra->compraitems()->sum('cantidad') ?? 0;
            $this->compra->save();
            DB::commit();
            $this->open = false;
            $this->compra->refresh();
            // $this->emitTo('modules.almacen.compras.show-compra', 'refresh');
            $this->dispatchBrowserEvent('created');
            $this->resetValidation();
            $this->reset(['producto_id', 'almacen_id', 'pricebuy', 'pricebuysoles', 'pricesale', 'cantidad', 'serie']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatecompraitem(Compraitem $compraitem)
    {
        DB::beginTransaction();
        try {
            $productoAlmacen = $compraitem->producto
                ->almacens()
                ->where('almacen_id', $compraitem->almacen_id)
                ->first();

            $compraitem->pricebuy = $this->pricebuy;
            $compraitem->cantidad = $compraitem->cantidad + $this->cantidad;
            $compraitem->subtotal = number_format($compraitem->pricebuy * $compraitem->cantidad, 2, '.', '');
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

            $compraitem->updateKardex($compraitem->id, $this->cantidad);

            $compraitem->producto->almacens()->updateExistingPivot($compraitem->almacen_id, [
                'cantidad' => $productoAlmacen->pivot->cantidad + $this->cantidad,
            ]);

            $this->compra->counter = $this->compra->compraitems()->sum('cantidad') ?? 0;
            $this->compra->save();

            DB::commit();
            $this->open = false;
            $this->compra->refresh();
            // $this->emitTo('modules.almacen.compras.show-compra', 'refresh');
            $this->dispatchBrowserEvent('updated');
            $this->resetValidation();
            $this->reset(['producto_id', 'almacen_id', 'pricebuy', 'pricebuysoles', 'pricesale', 'cantidad', 'serie']);
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

                // $compraitem->deleteKardex($compraitem->id);
                $compraitem->saveKardex(
                    $this->compra->sucursal_id,
                    $compraitem->producto_id,
                    $compraitem->almacen_id,
                    $productoAlmacen->pivot->cantidad,
                    $productoAlmacen->pivot->cantidad - $compraitem->cantidad,
                    $compraitem->cantidad,
                    '-',
                    Kardex::SALIDA_ANULACION_COMPRA,
                    $this->compra->referencia
                );

                $compraitem->producto->almacens()->updateExistingPivot($compraitem->almacen_id, [
                    'cantidad' => $productoAlmacen->pivot->cantidad - $compraitem->cantidad,
                ]);
                $this->compra->counter = $this->compra->counter - $compraitem->cantidad;
                $this->compra->save();
                $compraitem->producto->pricebuy = $compraitem->oldpricebuy;
                $compraitem->producto->pricesale = $compraitem->oldpricesale;
                $compraitem->producto->save();
                $compraitem->series()->forceDelete();
                $compraitem->forceDelete();
                DB::commit();
                $this->resetValidation();
                $this->compra->refresh();
                // $this->emitTo('modules.almacen.compras.show-compra', 'refresh');
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
