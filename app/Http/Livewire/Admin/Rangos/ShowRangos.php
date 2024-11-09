<?php

namespace App\Http\Livewire\Admin\Rangos;

use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Rango;
use App\Rules\ValidateRango;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;


class ShowRangos extends Component
{

    use WithPagination;
    use AuthorizesRequests;
    use WithFileUploads;


    public $open = false;
    public $rango;
    public $minHasta;

    public $checkall = false;
    public $selectedrangos = [];

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'rango.desde' => [
                'required',
                'numeric',
                'min:0',
                'decimal:0,2',
                'unique:rangos,desde,' . $this->rango->id,
                new ValidateRango($this->rango->desde, $this->rango->hasta, $this->rango->id),
            ],
            'rango.hasta' => [
                'required',
                'numeric',
                'min:' . $this->minHasta,
                'decimal:0,2',
                new ValidateRango($this->rango->desde, $this->rango->hasta, $this->rango->id),
            ],
            'rango.incremento' => ['required', 'numeric', 'min:0', 'decimal:0,2']
        ];
    }

    public function mount()
    {
        $this->rango = new Rango();
    }

    public function render()
    {
        $rangos = Rango::with(['pricetypes' => function ($query) {
            $query->activos()->orderBy('pricetypes.id', 'asc');
        }])->orderBy('desde', 'asc')->paginate(10, ['*'], 'rangosPage');
        $pricetypes = Pricetype::activos()->orderBy('id', 'asc')->get();
        return view('livewire.admin.rangos.show-rangos', compact('rangos', 'pricetypes'));
    }

    public function updatedRangosPage()
    {
        $this->reset(['checkall', 'selectedrangos']);
    }

    public function edit(Rango $rango)
    {
        $this->authorize('admin.administracion.rangos.edit');
        $this->resetValidation();
        $this->rango = $rango;
        $this->minHasta = $this->rango->desde == 0 ? 1 : $this->rango->desde + 0.1;
        $this->open = true;
    }

    public function update()
    {

        $this->authorize('admin.administracion.rangos.edit');
        $this->validate();
        DB::beginTransaction();
        try {
            if ($this->rango->isDirty('incremento')) {
                $this->rango->load(['pricetypes' => function ($query) {
                    $query->activos()->orderBy('pricetypes.id', 'asc');
                }]);

                if (count($this->rango->pricetypes) > 0) {
                    $productos = Producto::query()->select('id', 'name', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5', 'unit_id')
                        ->with(['promocions' => function ($query) {
                            $query->with(['itempromos.producto' => function ($subQuery) {
                                $subQuery->with('unit')->addSelect(['image' => function ($q) {
                                    $q->select('url')->from('images')
                                        ->whereColumn('images.imageable_id', 'productos.id')
                                        ->where('images.imageable_type', Producto::class)
                                        ->orderBy('default', 'desc')->limit(1);
                                }]);
                            }])->availables()->disponibles();
                        }])->whereRangoBetween($this->rango->desde, $this->rango->hasta)->get();

                    if (count($productos) > 0) {
                        foreach ($productos as $producto) {
                            $firstPrm = count($producto->promocions) > 0 ? $producto->promocions->first() : null;
                            $promocion = verifyPromocion($firstPrm);

                            foreach ($this->rango->pricetypes as $lista) {
                                $precio_venta = getPriceDinamic(
                                    $producto->pricebuy,
                                    $lista->pivot->ganancia ?? 0,
                                    $this->rango->incremento,
                                    $lista->rounded,
                                    $lista->decimals,
                                    $promocion
                                );

                                $producto->{$lista->campo_table} = $precio_venta;
                                $producto->save();
                                // dd($producto, $item->incremento, $lista->ganancia, $precio_venta);
                            }
                        }
                    }
                }
            }
            $this->rango->save();
            DB::commit();
            $this->reset(['minHasta', 'open']);
            $this->dispatchBrowserEvent('updated');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // public function updatepricerango(Rango $rango, Pricetype $pricetype, $ganancia)
    // {
    //     $this->authorize('admin.administracion.rangos.edit');

    //     if (empty($ganancia)) {
    //         $ganancia = 0;
    //         // $json = response()->json([
    //         //     'title' => 'INGRESE UN NÚMERO VÁLIDO !',
    //         //     'text' => 'el campo requiere un valor de tipo numérico'
    //         // ])->getData();
    //         // $this->dispatchBrowserEvent('validation', $json);
    //         // return false;
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $gananciaOld = $rango->pricetypes()->find($pricetype)->pivot->ganancia;
    //         if ($gananciaOld <> $ganancia) {
    //             $rango->pricetypes()->updateExistingPivot($pricetype, [
    //                 'ganancia' => $ganancia
    //             ]);

    //             $productos = Producto::query()->select('id', 'name', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5', 'unit_id')
    //                 ->with(['promocions' => function ($query) {
    //                     $query->with(['itempromos.producto' => function ($subQuery) {
    //                         $subQuery->with('unit');
    //                     }])->availables()->disponibles();
    //                 }])->whereRangoBetween($rango->desde, $rango->hasta)->get();
    //             if (count($productos) > 0) {
    //                 foreach ($productos as $producto) {
    //                     // $firstPrm = count($producto->promocions) > 0 ? $producto->promocions->first() : null;
    //                     // $promocion = verifyPromocion($firstPrm);
    //                     // $precio_venta = getPriceDinamic(
    //                     //     $producto->pricebuy,
    //                     //     $ganancia,
    //                     //     $rango->incremento,
    //                     //     $pricetype->rounded,
    //                     //     $pricetype->decimals,
    //                     //     $promocion
    //                     // );

    //                     // $producto->{$pricetype->campo_table} = $precio_venta;
    //                     // $producto->save();
    //                     $producto->assignPrice();
    //                 }
    //             }
    //             $this->dispatchBrowserEvent('updated');
    //         }
    //         DB::commit();
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         throw $e;
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }

    public function updateganacias($ganancias = [])
    {
        if (count($ganancias) > 0) {
            DB::beginTransaction();
            try {
                foreach ($ganancias as $item) {
                    $rango = Rango::with(['pricetypes' => function ($query) use ($item) {
                        $query->where('pricetypes.id', $item['pricetype_id']);
                    }])->find($item['rango_id']);

                    if ($rango->pricetypes && $rango->pricetypes->first()->pivot->ganancia <> $item['ganancia']) {
                        $rango->pricetypes()->updateExistingPivot($item['pricetype_id'], [
                            'ganancia' => $item['ganancia']
                        ]);

                        $productos = Producto::query()->select('id', 'name', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5', 'unit_id')
                            ->with(['promocions' => function ($query) {
                                $query->with(['itempromos.producto' => function ($subQuery) {
                                    $subQuery->with('unit');
                                }])->availables()->disponibles();
                            }])->whereRangoBetween($rango->desde, $rango->hasta)->get();
                        if (count($productos) > 0) {
                            foreach ($productos as $producto) {
                                $producto->assignPrice();
                            }
                        }
                    }
                }
                DB::commit();
                $this->dispatchBrowserEvent('toast', toastJSON('LISTA DE PRECIOS ACTUALIZADO CORRECTAMENTE'));
                return true;
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function delete(Rango $rango)
    {
        $this->authorize('admin.administracion.rangos.delete');
        DB::beginTransaction();
        try {
            $rango->pricetypes()->detach();
            $rango->delete();
            DB::commit();
            $this->resetPage('rangosPage');
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function syncprices($selectedrangos = [])
    {
        $this->authorize('admin.administracion.rangos.sync');
        DB::beginTransaction();
        try {
            $rangos = Rango::query()->with(['pricetypes' => function ($query) {
                $query->select('pricetypes.id', 'rounded', 'decimals', 'campo_table')
                    ->addSelect('pricetype_rango.ganancia');;
            }])->whereIn('id', $selectedrangos)->get();

            foreach ($rangos as $item) {
                if (count($item->pricetypes) > 0) {
                    $productos = Producto::query()->select('id', 'name', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5', 'unit_id')
                        ->with(['promocions' => function ($query) {
                            $query->with(['itempromos.producto' => function ($subQuery) {
                                $subQuery->with('unit')->addSelect(['image' => function ($q) {
                                    $q->select('url')->from('images')
                                        ->whereColumn('images.imageable_id', 'productos.id')
                                        ->where('images.imageable_type', Producto::class)
                                        ->orderBy('default', 'desc')->limit(1);
                                }]);
                            }])->availables()->disponibles();
                        }])->whereRangoBetween($item->desde, $item->hasta)->get();

                    if (count($productos) > 0) {
                        foreach ($productos as $producto) {
                            $firstPrm = count($producto->promocions) > 0 ? $producto->promocions->first() : null;
                            $promocion = verifyPromocion($firstPrm);

                            foreach ($item->pricetypes as $lista) {
                                $precio_venta = getPriceDinamic(
                                    $producto->pricebuy,
                                    $lista->ganancia,
                                    $item->incremento,
                                    $lista->rounded,
                                    $lista->decimals,
                                    $promocion
                                );

                                $producto->{$lista->campo_table} = $precio_venta;
                                $producto->save();
                                // dd($producto, $item->incremento, $lista->ganancia, $precio_venta);
                            }
                        }
                    }
                }
            }
            DB::commit();
            $this->reset(['checkall', 'selectedrangos']);
            $this->dispatchBrowserEvent('toast', toastJSON('SINCRONIZADO CORRECTAMENTE'));
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            $json = response()->json([
                'title' => 'ERROR AL ACTUALIZAR PRECIOS DE VENTA EN PRODUCTOS',
                'text' => $e->getMessage()
            ])->getData();
            $this->dispatchBrowserEvent('validation', $json);
        }
    }

    public function deleteall($rangos = [])
    {
        $this->authorize('admin.administracion.rangos.delete');
        if (count($rangos)) {
            DB::beginTransaction();
            try {
                Rango::whereIn('id', $this->selectedrangos)->delete();
                DB::commit();
                $this->dispatchBrowserEvent('deleted');
                $this->reset(['selectedrangos', 'checkall']);
                $this->resetPage('rangosPage');
                return true;
            } catch (Exception $e) {
                DB::rollBack();
                $json = response()->json([
                    'title' => 'ERROR AL ELIMINAR',
                    'text' => $e->getMessage()
                ])->getData();
                $this->dispatchBrowserEvent('validation', $json);
            }
        } else {
            $json = response()->json([
                'title' => 'SELECCIONAR RANGOS A ELIMINAR',
                'text' => null,
            ])->getData();
            $this->dispatchBrowserEvent('validation', $json);
        }
    }
}
