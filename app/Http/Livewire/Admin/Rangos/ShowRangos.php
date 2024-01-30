<?php

namespace App\Http\Livewire\Admin\Rangos;

use App\Models\Pricetype;
use App\Models\Rango;
use App\Rules\ValidateRango;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowRangos extends Component
{

    use WithPagination;

    public $open = false;
    public $rango;
    public $minHasta;

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
    }

    public function render()
    {
        $rangos = Rango::with(['pricetypes' => function ($query) {
            $query->orderBy('id', 'asc');
        }])->orderBy('desde', 'asc')->paginate();
        $pricetypes = Pricetype::orderBy('id', 'asc')->paginate();
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

    public function updatepricerango(Rango $rango, Pricetype $pricetype, $cantidad)
    {
        // $listaRango = $rango->pricetypes()->where('pricetype_id', $pricetype->id)->firstOrFail();
        DB::beginTransaction();
        try {
            $rango->pricetypes()->updateExistingPivot(
                $pricetype,
                ['ganancia' => $cantidad]
            );

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
}
