<?php

namespace App\Http\Livewire\Admin\Cajas;

use App\Models\Caja;
use App\Models\Sucursal;
use App\Rules\CampoUnique;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCajas extends Component
{

    use WithPagination;

    public $open = false;
    public $caja;
    public $searchsucursal = '';

    protected $queryString = [
        'searchsucursal' => [
            'except' => '',
            'as' => 'sucursal'
        ]
    ];

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'caja.sucursal_id' => [
                'required', 'integer', 'min:1', 'exists:sucursals,id'
            ],
            'caja.name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('cajas', 'name', $this->caja->id, true, 'sucursal_id', $this->caja->sucursal_id),
            ],
        ];
    }

    public function mount()
    {
        $this->caja = new Caja();
    }

    public function render()
    {

        $cajas = Caja::withWhereHas('sucursal', function ($query) {
            $query->withTrashed();
            if (trim($this->searchsucursal) !== '') {
                $query->where('id', $this->searchsucursal);
            }
        });

        $cajas = $cajas->orderBy('name', 'asc')->paginate();
        $sucursals = Sucursal::withTrashed()->whereHas('cajas')->get();
        return view('livewire.admin.cajas.show-cajas', compact('cajas', 'sucursals'));
    }

    public function edit(Caja $caja)
    {
        $this->caja = $caja;
        $this->resetValidation();
        $this->open = true;
    }

    public function update()
    {
        $this->caja->name = trim($this->caja->name);
        $this->validate();
        $this->caja->save();
        $this->resetValidation();
        $this->reset(['open']);
        $this->dispatchBrowserEvent('updated');
    }

    public function delete(Caja $caja)
    {
        $caja->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-show-cajas');
    }
}
