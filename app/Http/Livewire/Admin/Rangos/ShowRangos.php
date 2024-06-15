<?php

namespace App\Http\Livewire\Admin\Rangos;

use App\Imports\RangoImport;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Rango;
use App\Rules\ValidateRango;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ShowRangos extends Component
{

    use WithPagination;
    use AuthorizesRequests;
    use WithFileUploads;


    public $open = false;
    public $rango;
    public $minHasta;
    public $file, $identificador;

    public $checkall = false;
    public $selectedrangos = [];

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'rango.desde' => [
                'required', 'numeric', 'min:0', 'decimal:0,2', 'unique:rangos,desde,' . $this->rango->id,
                new ValidateRango($this->rango->desde, $this->rango->hasta, $this->rango->id),
            ],
            'rango.hasta' => [
                'required', 'numeric', 'min:' . $this->minHasta, 'decimal:0,2',
                new ValidateRango($this->rango->desde, $this->rango->hasta, $this->rango->id),
            ],
            'rango.incremento' => [
                'required', 'numeric', 'min:0', 'decimal:0,2',
            ]
        ];
    }

    public function mount()
    {
        $this->rango = new Rango();
        $this->identificador = rand();
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
            $productos = Producto::whereRangoBetween($this->rango->desde, $this->rango->hasta)->get();
            if (count($productos) > 0) {
                foreach ($productos as $item) {
                    // if (mi_empresa()->usarlista()) {
                    $item->assignPriceProduct();
                    // }
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
            $json = response()->json([
                'title' => 'INGRESE UN NÚMERO VÁLIDO !',
                'text' => 'el campo requiere un valor de tipo numérico'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $json);
            return false;
        }

        DB::beginTransaction();
        try {
            $rango->pricetypes()->updateExistingPivot(
                $pricetype,
                ['ganancia' => $ganancia]
            );

            $productos = Producto::whereRangoBetween($rango->desde, $rango->hasta)->get();
            if (count($productos) > 0) {
                foreach ($productos as $item) {
                    // if (mi_empresa()->usarlista()) {
                    $item->assignPriceProduct();
                    // }
                }
            }
            $this->dispatchBrowserEvent('updated');
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

    public function import()
    {
        $this->validate([
            'file' => ['required', 'mimes:xlsx,csv,txt']
        ]);

        try {
            Excel::import(new RangoImport, $this->file);

            $pricetypes = Pricetype::orderBy('id', 'asc')->pluck('id')->toArray();
            $rangos = Rango::orderBy('desde', 'asc')->get();
            $rangos->each(function ($rango) use ($pricetypes) {
                $rango->pricetypes()->syncWithPivotValues($pricetypes, [
                    'ganancia' => 0
                ]);
            });

            $this->dispatchBrowserEvent('toast', toastJSON('Importado correctamente'));
            $this->reset(['file']);
        } catch (Exception $e) {
            $json = response()->json([
                'title' => 'Error al importar rangos de precios !',
                'text' => $e->getMessage()
            ])->getData();
            $this->dispatchBrowserEvent('validation', $json);
        }
    }

    public function resetFile()
    {
        $this->reset(['file']);
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
