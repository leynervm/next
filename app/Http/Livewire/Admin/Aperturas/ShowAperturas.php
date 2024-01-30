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
    public $searchcaja = '';
    public $searchsucursal = '';

    protected $listeners = ['render', 'close'];

    protected function rules()
    {
        return [
            'opencaja.caja_id' => [
                'required', 'integer', 'min:1', 'exists:cajas,id',
            ],
            'opencaja.expiredate' => [
                'required', 'date', 'after:startdate', 'after_or_equal:' . now('America/Lima')->format('Y-m-d H:i')
            ],
            'opencaja.startmount' => [
                'required', 'numeric', 'min:0', 'decimal:0,4'
            ],
        ];
    }

    public function mount()
    {
        $this->opencaja = new Opencaja();
    }

    public function render()
    {
        $aperturas = Opencaja::withWhereHas('caja', function ($query) {
            $query->withTrashed()->withWhereHas('sucursal', function ($query) {
                $query->withTrashed();
                if (trim($this->searchsucursal !== '')) {
                    $query->where('id', $this->searchsucursal);
                } else {
                    $query->where('id', auth()->user()->sucursal_id);
                }
            });

            if (trim($this->searchcaja !== '')) {
                $query->where('id', $this->searchcaja);
            }
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
        $this->opencaja->closedate = now("America/Lima");
        $this->opencaja->status = 1;
        $this->opencaja->save();
        $this->dispatchBrowserEvent('updated');
    }
}
