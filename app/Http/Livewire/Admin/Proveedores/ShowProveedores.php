<?php

namespace App\Http\Livewire\Admin\Proveedores;

use App\Models\Proveedor;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProveedores extends Component
{

    use WithPagination;


    public $open = false;

    public function render()
    {
        $proveedors = Proveedor::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.proveedores.show-proveedores', compact('proveedors'));
    }
}
