<?php

namespace App\Http\Livewire\Modules\Almacen\Combos;

use App\Models\Combo;
use App\Models\Pricetype;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCombos extends Component
{

    use WithPagination;

    public $pricetype_id;

    protected $listeners = ['render'];

    public function mount()
    {
        $pricetypes = Pricetype::default();
        if (count($pricetypes->get()) > 0) {
            $this->pricetype_id = $pricetypes->first()->id;
        } else {
            $this->pricetype_id = Pricetype::orderBy('id', 'asc')->first()->id;
        }
    }

    public function render()
    {
        $pricetypes = Pricetype::orderBy('id', 'asc')->get();
        $combos = Combo::with(['producto', 'itemcombos'])->orderBy('id', 'desc')->paginate();
        return view('livewire.modules.almacen.combos.show-combos', compact('combos', 'pricetypes'));
    }

    public function delete(Combo $combo)
    {
        $combo->delete();
        $this->dispatchBrowserEvent('deleted');
    }

    public function desactivar(Combo $combo)
    {
        $combo->status = $combo->status == '1' ? 0 : 1;
        $combo->save();
        $this->dispatchBrowserEvent('updated');
    }
}
