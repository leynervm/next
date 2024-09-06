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
            'rango.incremento' => [
                'required',
                'numeric',
                'min:0',
                'decimal:0,2',
            ]
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
        }])->orderBy('desde', 'asc')->paginate();
        $pricetypes = Pricetype::activos()->orderBy('id', 'asc')->get();
        return view('livewire.admin.rangos.show-rangos', compact('rangos', 'pricetypes'));
    }

    public function edit(Rango $rango)
    {
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
            $this->rango->save();
            // $pricetypes = Pricetype::pluck('id')->toArray();
            // $this->rango->pricetypes()->syncWithPivotValues(
            //     $pricetypes,
            //     [
            //         // 'ganancia' => $this->rango->incremento,
            //         'user_id' => Auth::user()->id,
            //         'created_at' => now('America/Lima'),
            //         'updated_at' => now('America/Lima')
            //     ]
            // );
            if ($this->rango->isDirty('incremento')) {
                $productos = Producto::whereRangoBetween($this->rango->desde, $this->rango->hasta)->get();
                if (count($productos) > 0) {
                    foreach ($productos as $item) {
                        $item->assignPriceProduct();
                    }
                }
            }
            DB::commit();
            $this->resetExcept(['rango']);
            $this->dispatchBrowserEvent('updated');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatepricerango(Rango $rango, Pricetype $pricetype, $ganancia)
    {
        // dd($rango, $pricetype, $ganancia);
        $this->authorize('admin.administracion.rangos.edit');

        if (empty($ganancia)) {
            $ganancia = 0;
            // $json = response()->json([
            //     'title' => 'INGRESE UN NÚMERO VÁLIDO !',
            //     'text' => 'el campo requiere un valor de tipo numérico'
            // ])->getData();
            // $this->dispatchBrowserEvent('validation', $json);
            // return false;
        }

        DB::beginTransaction();
        try {
            $gananciaOld = $rango->pricetypes()->find($pricetype)->pivot->ganancia;
            if ($gananciaOld <> $ganancia) {
                $rango->pricetypes()->updateExistingPivot(
                    $pricetype,
                    ['ganancia' => $ganancia]
                );

                $productos = Producto::whereRangoBetween($rango->desde, $rango->hasta)->get();
                if (count($productos) > 0) {
                    foreach ($productos as $item) {
                        $item->assignPriceProduct();
                    }
                }
                $this->dispatchBrowserEvent('updated');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
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
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function updatedCheckall()
    {
        if ($this->checkall) {
            $this->selectedrangos = Rango::all()->pluck('id');
        } else {
            $this->reset(['selectedrangos']);
        }
    }

    public function deleteall()
    {
        DB::beginTransaction();
        try {
            if (count($this->selectedrangos) > 0) {
                Rango::whereIn('id', $this->selectedrangos)->delete();
                DB::commit();
                $this->dispatchBrowserEvent('deleted');
                $this->reset(['selectedrangos', 'checkall']);
            }
        } catch (Exception $e) {
            DB::rollBack();
            $json = response()->json([
                'title' => 'Error al importar rangos de precios !',
                'text' => $e->getMessage()
            ])->getData();
            $this->dispatchBrowserEvent('validation', $json);
        }
    }
}
