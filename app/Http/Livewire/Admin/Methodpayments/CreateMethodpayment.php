<?php

namespace App\Http\Livewire\Admin\Methodpayments;

use App\Models\Cuenta;
use App\Models\Methodpayment;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateMethodpayment extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $name, $type;
    public $default = 0;
    public $selectedCuentas = [];

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:100',
                new CampoUnique('methodpayments', 'name', null, true),
            ],
            'type' => [
                'required',
                'integer',
                'min:0',
                'max:1',
            ],
            'default' => [
                'required',
                'integer',
                'min:0',
                'max:1',
                new DefaultValue('methodpayments', 'default', null, true)
            ]
        ];
    }

    public function render()
    {
        return view('livewire.admin.methodpayments.create-methodpayment');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.cajas.methodpayments.create');
            $this->resetValidation();
            $this->reset();
        }
    }

    public function save($closemodal = false)
    {
        $this->authorize('admin.cajas.methodpayments.create');
        $this->name = trim($this->name);
        $this->default = $this->default == 1 ? 1 : 0;
        $this->validate();

        try {
            DB::beginTransaction();
            $methodpayment = Methodpayment::onlyTrashed()
                ->whereRaw('UPPER(name) = ?', [mb_strtoupper($this->name, "UTF-8")])
                ->first();

            if ($methodpayment) {
                $methodpayment->default = $this->default;
                $methodpayment->type = $this->type;
                $methodpayment->restore();
            } else {
                Methodpayment::create([
                    'name' => $this->name,
                    'type' => $this->type,
                    'default' => $this->default,
                ]);
            }

            DB::commit();
            $this->emitTo('admin.methodpayments.show-methodpayments', 'render');
            if ($closemodal) {
                $this->reset();
            } else {
                $this->resetExcept('open');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
