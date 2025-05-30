<?php

namespace App\Http\Livewire\Modules\Soporte\Conditions;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Soporte\Entities\Condition;

class ShowConditions extends Component
{

    use WithPagination;

    public $open = false;
    public $condition;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'condition.name' => [
                'required',
                'min:3',
                'max:100',
                new Letter,
                new CampoUnique('conditions', 'name', $this->condition->id),
            ],
            'condition.flagpagable' => ['nullable']
        ];
    }

    public function render()
    {
        $conditions = Condition::orderBy('name', 'asc')->paginate();
        return view('livewire.modules.soporte.conditions.show-conditions', compact('conditions'));
    }

    public function updatedOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
        }
    }

    public function edit(Condition $condition)
    {
        $this->condition = $condition;
        $this->open = true;
    }

    public function update()
    {
        $this->condition->name = trim($this->condition->name);
        $this->validate();
        $this->condition->save();
        $this->reset(['open']);
    }

    public function delete($id)
    {
        if ($id) {
            $condition = Condition::withCount('tickets')->find($id);
            if ($condition) {
                if ($condition->tickets_count > 0) {
                    $condition->delete();
                } else {
                    $condition->forceDelete();
                }
                return $this->dispatchBrowserEvent('deleted');
            }
        }

        return $this->dispatchBrowserEvent('toast', toastJSON('NO SE ENCONTRÓ EL REGISTRO', 'error'));
    }
}
