<?php

namespace App\Http\Livewire\Admin\Methodpayments;

use App\Models\Cuenta;
use App\Models\Methodpayment;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowMethodpayments extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    public $methodpayment;
    public $open = false;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'methodpayment.name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('methodpayments', 'name', $this->methodpayment->id, true),
            ],
            'methodpayment.type' => [
                'required', 'integer', 'min:0', 'max:1',
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
        return view('livewire.admin.methodpayments.show-methodpayments',  compact('methodpayments'));
    }

    public function edit(Methodpayment $methodpayment)
    {
        $this->authorize('admin.cajas.methodpayments.edit');
        $this->resetValidation();
        $this->methodpayment = $methodpayment;
        $this->open = true;
    }

    public function update()
    {
        try {
            $this->authorize('admin.cajas.methodpayments.edit');
            DB::beginTransaction();
            $this->methodpayment->default = $this->methodpayment->default == 1 ? 1 : 0;
            $this->methodpayment->name = trim($this->methodpayment->name);
            $this->validate();
            $this->methodpayment->save();
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

    public function delete(Methodpayment $methodpayment)
    {
        $this->authorize('admin.cajas.methodpayments.delete');
        $methodpayment->delete();
        $this->dispatchBrowserEvent('deleted');
    }
}
