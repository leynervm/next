<?php

namespace App\Http\Livewire\Admin\Units;

use App\Models\Unit;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Livewire\WithPagination;

class ShowUnits extends Component
{

    use WithPagination;

    public $open = false;
    public $unit;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'unit.name' => [
                'required', 'min:2', 'max:100', new Letter,
                new CampoUnique('units', 'name', $this->unit->id, true),
            ],
            // 'unit.abreviatura' => [
            //     'required', 'min:2', 'max:4'
            // ],
            'unit.code' => [
                'required', 'min:1', 'max:4', new CampoUnique('units', 'code', $this->unit->id, true)
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
        $this->unit = $unit;
        $this->open = true;
    }

    public function update()
    {
        $this->unit->name = trim($this->unit->name);
        $this->unit->code = trim($this->unit->code);
        $this->validate();
        $this->unit->save();
        $this->reset(['open']);
    }

    public function delete(Unit $unit)
    {
        $unit->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
    }
}
