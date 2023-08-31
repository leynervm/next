<?php

namespace App\Http\Livewire\Admin\Accountpayments;

use App\Models\Banco;
use App\Models\Cuenta;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateAccountpayment extends Component
{

    public $open = false;

    public $default = 0;
    public $account, $descripcion, $banco_id;

    protected function rules()
    {
        return [
            'account' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('cuentas', 'account', null, true)
            ],
            'descripcion' => [
                'required', 'min:3', 'max:100',
            ],
            'banco_id' => [
                'required', 'integer', 'min:1', 'exists:bancos,id',
            ],
            'default' => [
                'required', 'integer', 'min:0',
                new DefaultValue('cuentas', 'default', null, true)

            ]
        ];
    }

    public function render()
    {
        $bancos = Banco::orderBy('name', 'asc')->get();
        return view('livewire.admin.accountpayments.create-accountpayment', compact('bancos'));
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
        $this->account = trim($this->account);
        $this->descripcion = trim($this->descripcion);
        $this->default = $this->default == 1 ? 1 : 0;
        $this->validate();

        try {
            DB::beginTransaction();
            $cuenta = Cuenta::withTrashed()
                ->where('account', mb_strtoupper($this->account, "UTF-8"))->first();

            if ($cuenta) {
                $cuenta->descripcion = $this->descripcion;
                $cuenta->banco_id = $this->banco_id;
                $cuenta->default = $this->default;
                $cuenta->restore();
            } else {
                $cuenta = Cuenta::create([
                    'account' => $this->account,
                    'descripcion' => $this->descripcion,
                    'default' => $this->default,
                    'banco_id' => $this->banco_id,
                ]);
            }

            DB::commit();
            $this->emitTo('admin.accountpayments.show-accountpayments', 'render');
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-createaccount-select2');
    }
}
