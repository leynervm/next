<?php

namespace Modules\Almacen\Http\Livewire\Ofertas;

use App\Models\Empresa;
use App\Models\Moneda;
use App\Models\Pricetype;
use App\Models\Rango;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Oferta;
use App\Models\Producto;

class ShowOfertas extends Component
{

    use WithPagination;

    public $empresa, $moneda, $oferta;
    public $open = false;
    public $max = 0;
    public $minlimit = 0;
    public $maxlimit = 0;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'oferta.descuento' => [
                'required',
                'decimal:0,2',
                'min:1',
            ],
            'oferta.dateexpire' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:datestart',
            ],
            'oferta.limit' => [
                'required',
                'decimal:0,2',
                'min:' . $this->minlimit,
                'max:' . $this->maxlimit
            ]
        ];
    }

    public function mount(Empresa $empresa, Moneda $moneda)
    {
        $this->oferta = new Oferta();
        $this->empresa = $empresa;
        $this->moneda = $moneda;
    }

    public function render()
    {
        $ofertas = Oferta::OfertasDisponibles()->paginate();
        $pricetypes = Pricetype::orderBy('id', 'asc')->get();
        $monedas = Moneda::orderBy('id', 'asc')->get();
        return view('almacen::livewire.ofertas.show-ofertas', compact('ofertas', 'pricetypes', 'monedas'));
    }

    public function updatedMax($value)
    {

        $this->resetValidation(['max']);
        $this->reset(['max']);

        if ($value == 1) {
            if ($this->oferta->almacen_id) {
                if ($this->oferta->producto_id) {

                    $this->oferta->limit = Producto::findOrFail($this->oferta->producto_id)->almacens
                        ->find($this->oferta->almacen_id)->pivot->cantidad;
                    $this->resetValidation(['max']);
                    $this->max = 1;
                } else {
                    $this->addError('max', 'Seleccione el campo producto_');
                }
            } else {
                $this->addError('max', 'seleccione el campo almacén');
            }
        }
    }

    public function edit(Oferta $oferta)
    {
        $this->oferta = $oferta;
        $this->minlimit = $oferta->vendidos == 0 ? 1 : $oferta->vendidos;
        $this->oferta->dateexpire = Carbon::parse($this->oferta->dateexpire)->format('Y-m-d');
        $this->maxlimit = Producto::findOrFail($oferta->producto_id)->almacens
            ->find($oferta->almacen_id)->pivot->cantidad;
        $this->resetValidation();
        $this->open = true;
    }

    public function update()
    {
        $this->validate();
        $this->oferta->disponible = $this->oferta->limit - $this->oferta->vendidos;
        $this->oferta->save();
        $this->resetValidation();
        $this->reset(['open', 'max']);
        $this->dispatchBrowserEvent('updated');
    }

    public function delete(Oferta $oferta)
    {
        $oferta->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
    }
}
