<?php

namespace App\Http\Livewire\Modules\Soporte\Priorities;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use App\Rules\ValidateColor;
use Livewire\Component;
use Modules\Soporte\Entities\Priority;

class CreatePriority extends Component
{

    public $open = false;
    public $default = 0;
    public $color = "#000000";
    public $name;

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:100',
                new Letter,
                new CampoUnique('priorities', 'name', null, true)
            ],
            'color' => [
                'required',
                new ValidateColor
            ],
            // 'default' => [
            //     'nullable', 'max:1', new DefaultValue('priorities', 'default')
            // ],
        ];
    }

    public function render()
    {
        return view('livewire.modules.soporte.priorities.create-priority');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name', 'color']);
        }
    }

    public function save($closemodal = false)
    {
        $this->name = toStrUppercase($this->name);
        $this->color = trim($this->color);
        $this->validate();
        $priority = Priority::withTrashed()->where('name', $this->name)->first();

        if ($priority) {
            if ($priority->trashed()) {
                $priority->restore();
            }
            $priority->color = $this->color;
            $priority->save();
        } else {
            Priority::create([
                'name' => $this->name,
                'color' => $this->color,
            ]);
        }
        $this->emitTo('modules.soporte.priorities.show-priorities', 'render');
        $this->dispatchBrowserEvent('created');
        if ($closemodal) {
            $this->reset();
        } else {
            $this->resetExcept('open');
        }
    }
}
