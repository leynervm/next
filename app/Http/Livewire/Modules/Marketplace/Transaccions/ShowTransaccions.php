<?php

namespace App\Http\Livewire\Modules\Marketplace\Transaccions;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Marketplace\Entities\Transaccion;

class ShowTransaccions extends Component
{

    use WithPagination;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'buscar'],
        'date' => ['except' => '', 'as' => 'fecha'],
        'dateto' => ['except' => '', 'as' => 'hasta'],
    ];


    public $search = '', $date = '', $dateto = '';

    public function render()
    {
        $transaccions = Transaccion::with(['order', 'user']);

        if ($this->search !== '') {
            $transaccions->where('transaction_id', 'ilike', '%' . $this->search . '%')
                ->orWhereHas('user', function ($query) {
                    $query->where('name', 'ilike', '%' . $this->search . '%')
                        ->orWhere('email', 'ilike', '%' . $this->search . '%');
                });
        }

        if ($this->date) {
            if ($this->dateto) {
                $transaccions->whereDateBetween('date', $this->date, $this->dateto);
            } else {
                $transaccions->whereDate('date', $this->date);
            }
        }

        $transaccions = $transaccions->orderBy('date', 'desc')->paginate();
        return view('livewire.modules.marketplace.transaccions.show-transaccions', compact('transaccions'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDate()
    {
        $this->resetPage();
    }

    public function updatedDateto()
    {
        $this->resetPage();
    }
}
