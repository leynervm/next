<?php

namespace App\Http\Livewire\Admin\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ShowClients extends Component
{

    use WithPagination;

    public $search = '';
    protected $listeners = ['render'];
    protected $queryString = ['search' => ['except' => '']];


    public function render()
    {
        $clients = Client::with(['telephones', 'direccion.ubigeo', 'pricetype']);

        if (trim($this->search) !== '') {
            $clients->where('document', 'ilike', '%' . $this->search . '%')
                ->orWhere('name', 'ilike', '%' . $this->search . '%');
        }
        $clients = $clients->orderBy('name', 'asc')->orderBy('document', 'asc')->paginate(20);

        return view('livewire.admin.clients.show-clients', compact('clients'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}
