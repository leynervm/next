<?php

namespace App\Http\Livewire\Admin\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ShowClients extends Component
{

    use WithPagination;

    
    public $open = false;
    public $client;


    protected $listeners = ['render', 'delete'];

    public function render()
    {
        $clients = Client::orderBy('name', 'asc')->orderBy('document', 'asc')->paginate();
        return view('livewire.admin.clients.show-clients', compact('clients'));
    }
}
