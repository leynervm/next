<?php

namespace App\Http\Livewire\Admin\Rangos;

use App\Models\Pricetype;
use App\Models\Rango;
use App\Rules\ValidateRango;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateRango extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $desde;
    public $hasta;
    public $minHasta;
    public $incremento = 0;

    protected function rules()
    {
        return [
            'desde' => [
                'required',
                'numeric',
                'min:0',
                'decimal:0,2',
                'unique:rangos,desde',
                new ValidateRango($this->desde, $this->hasta),
            ],
            'hasta' => [
                'required',
                'numeric',
                'min:' . $this->minHasta,
                'decimal:0,2',
                new ValidateRango($this->desde, $this->hasta),
            ],
            'incremento' => ['required', 'numeric', 'min:0', 'decimal:0,2',]

        ];
    }

    public function render()
    {
        return view('livewire.admin.rangos.create-rango');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.administracion.rangos.create');
            $this->resetValidation();
            $this->reset();
        }
    }

    public function save($closemodal = false)
    {

        $this->authorize('admin.administracion.rangos.create');
        $this->minHasta = $this->desde == 0 ? 1 : $this->desde + 0.1;
        $this->validate();

        try {
            DB::beginTransaction();
            $rango = Rango::create([
                'desde' => $this->desde,
                'hasta' => $this->hasta,
                'incremento' => $this->incremento,
            ]);

            $pricetypes = Pricetype::pluck('id')->toArray();
            $rango->pricetypes()->syncWithPivotValues(
                $pricetypes,
                ['ganancia' => 0]
            );
            DB::commit();
            $this->resetValidation();
            if ($closemodal) {
                $this->reset();
            } else {
                $this->resetExcept(['open']);
            }
            $this->emitTo('admin.rangos.show-rangos', 'render');
            $this->dispatchBrowserEvent('created');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
