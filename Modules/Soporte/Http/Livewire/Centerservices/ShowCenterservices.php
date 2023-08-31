<?php

namespace Modules\Soporte\Http\Livewire\Centerservices;

use Livewire\Component;
use Modules\Soporte\Entities\Centerservice;
use Livewire\WithPagination;

class ShowCenterservices extends Component
{

    use WithPagination;
    
    public function render()
    {
        $centerservices = Centerservice::where('delete', 0)->orderBy('name', 'asc')->paginate();
        return view('soporte::livewire.centerservices.show-centerservices', compact('centerservices'));
    }
}
