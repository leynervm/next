<?php

namespace Modules\Soporte\Http\Livewire\Priorities;

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
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('priorities', 'name')
            ],
            'color' => [
                'required', new ValidateColor
            ],
            // 'default' => [
            //     'nullable', 'max:1', new DefaultValue('priorities', 'default')
            // ],
        ];
    }

    public function render()
    {
        return view('soporte::livewire.priorities.create-priority');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name', 'color']);
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        // $this->default = trim($this->default);
        $this->color = trim($this->color);
        $this->validate();

        $priority = Priority::where('name', mb_strtoupper($this->name, "UTF-8"))
            ->where('delete', 1)->first();

        if ($priority) {
            $priority->delete = 0;
            $priority->color = $this->color;
            // $priority->default = $this->default;
            $priority->save();
        } else {
            Priority::create([
                'name' => $this->name,
                'color' => $this->color,
                // 'default' => $this->default,
            ]);
        }
        $this->emitTo('soporte::priorities.show-priorities', 'render');
        $this->reset('name', 'color', 'open');
    }
}
