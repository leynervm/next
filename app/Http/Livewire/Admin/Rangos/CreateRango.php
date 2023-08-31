<?php

namespace App\Http\Livewire\Admin\Rangos;

use App\Models\Pricetype;
use App\Models\Rango;
use App\Rules\ValidateRango;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateRango extends Component
{

    public $open = false;

    public $desde;
    public $hasta;
    public $minHasta;
    public $incremento = 0;

    protected function rules()
    {
        return [
            'desde' => [
                'required', 'numeric', 'min:0', 'decimal:0,2', 'unique:rangos,desde',
                new ValidateRango($this->desde, $this->hasta),
            ],
            'hasta' => [
                'required', 'numeric', 'min:' . $this->minHasta, 'decimal:0,2',
                new ValidateRango($this->desde, $this->hasta),
            ],
            'incremento' => [
                'required', 'numeric', 'min:0', 'decimal:0,2',
            ]

        ];
    }

    public function render()
    {
        return view('livewire.admin.rangos.create-rango');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset();
        }
    }

    public function save()
    {
        $this->minHasta = $this->desde == 0 ? 1 : $this->desde + 0.1;
        $this->validate();

        try {
            DB::beginTransaction();

            $rango = Rango::create([
                'desde' => $this->desde,
                'hasta' => $this->hasta,
                'incremento' => $this->incremento,
                'user_id' => Auth::user()->id
            ]);

            $pricetypes = Pricetype::pluck('id')->toArray();
            $rango->pricetypes()->syncWithPivotValues(
                $pricetypes,
                [
                    'ganancia' => $rango->incremento,
                    'user_id' => Auth::user()->id,
                    'created_at' => now('America/Lima'),
                    'updated_at' => now('America/Lima')
                ]
            );
            DB::commit();
            $this->emitTo('admin.rangos.show-rangos', 'render');
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
