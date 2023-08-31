<?php

namespace App\Http\Livewire\Admin\Aperturas;

use App\Models\Opencaja;
use Livewire\Component;
use Livewire\WithPagination;

class ShowAperturas extends Component
{

    use WithPagination;

    protected $listeners = ['render'];

    public function render()
    {
        $aperturas = Opencaja::orderBy('startdate', 'desc')->paginate();
        return view('livewire.admin.aperturas.show-aperturas', compact('aperturas'));
    }
}
