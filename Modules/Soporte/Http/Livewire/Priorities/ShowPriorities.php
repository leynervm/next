<?php

namespace Modules\Soporte\Http\Livewire\Priorities;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use App\Rules\ValidateColor;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Soporte\Entities\Priority;

class ShowPriorities extends Component
{

    use WithPagination;

    public $open = false;
    public $priority;

    protected $listeners = ['render', 'deletePriority' => 'delete'];

    protected function rules()
    {
        return [
            'priority.name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('priorities', 'name', $this->priority->id)
            ],
            'priority.color' => [
                'required', new ValidateColor
            ],
            // 'priority.default' => [
            //     'nullable', 'max:1', new DefaultValue('priorities', 'default')
            // ],
        ];
    }

    public function mount()
    {
        $this->priority = new Priority();
    }

    public function render()
    {
        $priorities = Priority::where('delete', 0)->orderBy('name', 'asc')->paginate();
        return view('soporte::livewire.priorities.show-priorities', compact('priorities'));
    }

    public function updatedOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
        }
    }

    public function edit(Priority $priority)
    {
        $this->priority = $priority;
        $this->open = true;
    }

    public function update()
    {
        $this->priority->name = trim($this->priority->name);
        // $this->priority->default = trim($this->priority->default);
        $this->priority->color = trim($this->priority->color);
        $this->validate();

        $this->priority->save();
        $this->reset(['open']);
    }

    public function confirmDelete(Priority $priority)
    {
        $this->dispatchBrowserEvent('soporte::priorities.confirmDelete', $priority);
    }

    public function delete(Priority $priority)
    {
        $priority->delete = 1;
        // $priority->default = 0;
        $priority->save();
        $this->dispatchBrowserEvent('soporte::priorities.deleted');
    }
}
