<?php

namespace App\Http\Livewire\Admin\Methodpayments;

use App\Models\Cuenta;
use App\Models\Methodpayment;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateMethodpayment extends Component
{

    public $open = false;
    public $name;
    public $default = 0;
    public $selectedCuentas = [];

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('methodpayments', 'name', null, true),
            ],
            'default' => [
                'required', 'integer', 'min:0', 'max:1',
                new DefaultValue('methodpayments', 'default', null, true)
            ]
        ];
    }

    public function render()
    {
        $cuentas = Cuenta::orderBy('account', 'asc')->get();
        return view('livewire.admin.methodpayments.create-methodpayment', compact('cuentas'));
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
        $this->name = trim($this->name);
        $this->default = $this->default == 1 ? 1 : 0;
        $this->validate();

        try {
            DB::beginTransaction();
            $methodpayment = Methodpayment::withTrashed()
                ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

            if ($methodpayment) {
                $methodpayment->default = $this->default;
                $methodpayment->type = 0;
                $methodpayment->restore();
            } else {
                $methodpayment = Methodpayment::create([
                    'name' => $this->name,
                    'type' => 0,
                    'default' => $this->default,
                ]);
            }

            $methodpayment->cuentas()->sync($this->selectedCuentas);

            DB::commit();
            $this->emitTo('admin.methodpayments.show-methodpayments', 'render');
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
