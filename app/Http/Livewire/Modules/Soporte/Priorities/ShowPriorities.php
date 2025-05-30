<?php

namespace App\Http\Livewire\Modules\Soporte\Priorities;

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

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'priority.name' => [
                'required',
                'min:3',
                'max:100',
                new Letter,
                new CampoUnique('priorities', 'name', $this->priority->id)
            ],
            'priority.color' => [
                'required',
                new ValidateColor
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
        $priorities = Priority::orderBy('name', 'asc')->paginate();
        return view('livewire.modules.soporte.priorities.show-priorities', compact('priorities'));
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
        $this->priority->color = trim($this->priority->color);
        $this->validate();
        $this->priority->save();
        $this->reset(['open']);
        $this->dispatchBrowserEvent('updated');
    }

    public function delete($priority_id)
    {
        if ($priority_id) {
            $priority = Priority::withCount('tickets')->find($priority_id);
            if ($priority) {
                if ($priority->tickets_count > 0) {
                    $priority->delete();
                } else {
                    $priority->forceDelete();
                }
                return $this->dispatchBrowserEvent('deleted');
            }
        }

        return $this->dispatchBrowserEvent('toast', toastJSON('NO SE ENCONTRÓ EL REGISTRO', 'error'));
    }
}
