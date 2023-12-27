<?php

namespace App\Http\Livewire\Admin\Aperturas;

use App\Models\Opencaja;
use Livewire\Component;
use Livewire\WithPagination;

class ShowAperturas extends Component
{

    use WithPagination;

    public $open = false;
    public $opencaja;

    protected $listeners = ['render', 'close'];

    protected function rules()
    {
        return [
            'opencaja.caja_id' => [
                'required', 'integer', 'min:1', 'exists:cajas,id',
            ],
            'opencaja.startmount' => [
                'required', 'numeric', 'min:0', 'decimal:0,2'
            ],
        ];
    }

    public function mount()
    {
        $this->opencaja = new Opencaja();
    }

    public function render()
    {
        $sucursals = auth()->user()->sucursals()->select('sucursals.id')->pluck('sucursals.id');
        $aperturas = Opencaja::withWhereHas('caja', function ($query) use ($sucursals) {
            $query->whereIn('sucursal_id', $sucursals)->withTrashed();
        })->orderBy('startdate', 'desc')->paginate();
        return view('livewire.admin.aperturas.show-aperturas', compact('aperturas'));
    }

    public function edit(Opencaja $opencaja)
    {
        $this->resetValidation();
        $this->opencaja = $opencaja;
        $this->open = true;
    }

    public function update()
    {
        $this->validate();
        $this->opencaja->save();
        $this->resetValidation();
        $this->open = false;
        $this->dispatchBrowserEvent('updated');
    }

    public function close(Opencaja $opencaja)
    {
        $this->opencaja = $opencaja;
        $this->opencaja->expiredate = now("America/Lima");
        $this->opencaja->save();
        $this->dispatchBrowserEvent('updated');

    }
}
