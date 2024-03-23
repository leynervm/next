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
        $clients = Client::orderBy('name', 'asc');

        if (trim($this->search) !== '') {
            $clients->where('document', 'ilike', '%' . $this->search . '%')
                ->orWhere('name', 'ilike', '%' . $this->search . '%');
        }
        $clients = $clients->orderBy('document', 'asc')->paginate();

        return view('livewire.admin.clients.show-clients', compact('clients'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}
