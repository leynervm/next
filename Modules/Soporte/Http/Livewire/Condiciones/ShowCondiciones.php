<?php


namespace Modules\Soporte\Http\Livewire\Condiciones;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Modules\Soporte\Entities\Condition;
use Livewire\WithPagination;

class ShowCondiciones extends Component
{

    use WithPagination;

    public $open = false;
    public $condition;

    protected $listeners = ['render', 'deleteCondicion' => 'delete'];

    protected function rules()
    {
        return [
            'condition.name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('conditions', 'name', $this->condition->id),
            ],
            'condition.flagpagable' => ['nullable']
        ];
    }

    public function render()
    {
        $conditions = Condition::where('delete', 0)->orderBy('name', 'asc')->paginate(15, ['*'], 'PageCondicion');
        return view('soporte::livewire.condiciones.show-condiciones', compact('conditions'));
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

    public function confirmDelete(Condition $condition)
    {
        $this->dispatchBrowserEvent('soporte::condiciones.confirmDelete', $condition);
    }

    public function delete(Condition $condition)
    {
        $condition->delete = 1;
        $condition->save();
        $this->dispatchBrowserEvent('soporte::condiciones.deleted');
    }
}
