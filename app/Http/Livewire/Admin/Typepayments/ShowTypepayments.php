<?php

namespace App\Http\Livewire\Admin\Typepayments;

use App\Models\Typepayment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ShowTypepayments extends Component
{

    use WithPagination, AuthorizesRequests;

    public function render()
    {
        $typepayments = Typepayment::orderBy('default', 'desc')->paginate();
        return view('livewire.admin.typepayments.show-typepayments', compact('typepayments'));
    }

    public function update(Typepayment $typepayment)
    {
        $this->authorize('admin.administracion.typepayments.edit');
        if ($typepayment->isActivo()) {
            $typepayment->status = Typepayment::DISABLED;
            if ($typepayment->isDefault()) {
                $typepayment->status = 0;
            }
        } else {
            $typepayment->status = Typepayment::ACTIVO;
        }
        $typepayment->save();
        $this->dispatchBrowserEvent('updated');
    }
}
