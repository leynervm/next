<?php

namespace App\Http\Livewire\Admin\Proveedores;

use App\Models\Proveedor;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProveedores extends Component
{

    use WithPagination;


    public $open = false;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];

    public function render()
    {
        $proveedors = Proveedor::with(['proveedortype', 'telephones', 'ubigeo']);

        if (trim($this->search) !== '') {
            $proveedors->where('document', 'ilike', '%' . $this->search . '%')
                ->orWhere('name', 'ilike', '%' . $this->search . '%');
        }
        $proveedors = $proveedors->orderBy('name', 'asc')->orderBy('document', 'asc')->paginate(20);
        return view('livewire.admin.proveedores.show-proveedores', compact('proveedors'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}
