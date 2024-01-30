<?php

namespace App\Http\Livewire\Admin\Methodpayments;

use App\Models\Cuenta;
use App\Models\Methodpayment;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowMethodpayments extends Component
{

    use WithPagination;

    public $methodpayment;
    public $open = false;
    public $selectedCuentas = [];

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'methodpayment.name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('methodpayments', 'name', $this->methodpayment->id, true),
            ],
            'methodpayment.default' => [
                'required', 'integer', 'min:0', 'max:1',
                new DefaultValue('methodpayments', 'default', $this->methodpayment->id, true)
            ]
        ];
    }

    public function mount()
    {
        $this->methodpayment = new Methodpayment();
    }

    public function render()
    {
        $methodpayments = Methodpayment::orderBy('name', 'asc')->paginate();
        $cuentas = Cuenta::orderBy('account', 'asc')->get();
        return view('livewire.admin.methodpayments.show-methodpayments',  compact('methodpayments', 'cuentas'));
    }

    public function edit(Methodpayment $methodpayment)
    {
        $this->selectedCuentas = $methodpayment->cuentas->pluck('id');
        $this->methodpayment = $methodpayment;
        $this->resetValidation();
        $this->open = true;
    }

    public function update()
    {
        try {
            DB::beginTransaction();
            $this->methodpayment->default = $this->methodpayment->default == 1 ? 1 : 0;
            $this->methodpayment->name = trim($this->methodpayment->name);
            $this->validate();
            $this->methodpayment->save();
            $this->methodpayment->cuentas()->sync($this->selectedCuentas);
            DB::commit();
            $this->dispatchBrowserEvent('updated');
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function confirmDelete(Methodpayment $methodpayment)
    {
        $this->dispatchBrowserEvent('methodpayments.confirmDelete', $methodpayment);
    }

    public function delete(Methodpayment $methodpayment)
    {
        $methodpayment->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
    }
}
