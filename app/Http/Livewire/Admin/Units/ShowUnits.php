<?php

namespace App\Http\Livewire\Admin\Units;

use App\Models\Unit;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ShowUnits extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    public $open = false;
    public $unit;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'unit.name' => [
                'required', 'string', 'min:2', 'max:100', new CampoUnique('units', 'name', $this->unit->id, true),
            ],
            'unit.code' => [
                'required', 'string', 'min:1', 'max:4', new CampoUnique('units', 'code', $this->unit->id, true)
            ]
        ];
    }

    public function mount()
    {
        $this->unit = new Unit();
    }

    public function render()
    {
        $units = Unit::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.units.show-units', compact('units'));
    }

    public function edit(Unit $unit)
    {
        $this->authorize('admin.administracion.units.edit');
        $this->unit = $unit;
        $this->open = true;
    }

    public function update()
    {
        $this->authorize('admin.administracion.units.edit');
        $this->unit->name = trim($this->unit->name);
        $this->unit->code = trim($this->unit->code);
        $this->validate();
        $this->unit->save();
        $this->reset(['open']);
    }

    public function delete(Unit $unit)
    {
        $this->authorize('admin.administracion.units.delete');
        $unit->delete();
        $this->dispatchBrowserEvent('deleted');
    }
}
