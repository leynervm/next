<?php

namespace App\Http\Livewire\Admin\Accountpayments;

use App\Models\Banco;
use App\Models\Cuenta;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Livewire\Component;
use Livewire\WithPagination;

class ShowAccountpayments extends Component
{

    use WithPagination;

    public $open = false;
    public $cuenta;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'cuenta.account' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('cuentas', 'account', $this->cuenta->id, true)
            ],
            'cuenta.descripcion' => [
                'required', 'min:3', 'max:100',
            ],
            'cuenta.banco_id' => [
                'required', 'integer', 'min:1', 'exists:bancos,id',
            ],
            'cuenta.default' => [
                'required', 'integer', 'min:0',
                new DefaultValue('cuentas', 'default', $this->cuenta->id, true)

            ]
        ];
    }

    public function mount()
    {
        $this->cuenta = new Cuenta();
    }

    public function render()
    {
        $cuentas = Cuenta::orderBy('account', 'asc')->paginate();
        $bancos = Banco::orderBy('name', 'asc')->get();
        return view('livewire.admin.accountpayments.show-accountpayments', compact('cuentas', 'bancos'));
    }

    public function edit(Cuenta $cuenta)
    {
        $this->cuenta = $cuenta;
        $this->resetValidation();
        $this->open = true;
    }

    public function update()
    {
        $this->cuenta->account = trim($this->cuenta->account);
        $this->cuenta->descripcion = trim($this->cuenta->descripcion);
        $this->cuenta->default = $this->cuenta->default == 1 ? 1 : 0;
        $this->validate();
        $this->cuenta->save();
        $this->reset();
    }

    public function confirmDelete(Cuenta $cuenta)
    {
        $this->dispatchBrowserEvent('accountpayments.confirmDelete', $cuenta);
    }

    public function delete(Cuenta $cuenta)
    {
        $cuenta->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-editaccount-select2');
    }
}
