<?php

namespace Modules\Soporte\Http\Livewire\Status;

use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use App\Rules\Letter;
use App\Rules\ValidateColor;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Soporte\Entities\Estate;

class ShowStatus extends Component
{

    use WithPagination;

    public $open = false;
    public $estate;

    protected $listeners = ['render', 'deleteStatus' => 'delete'];

    protected function rules()
    {
        return [
            'estate.name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('estates', 'name', $this->estate->id)
            ],
            'estate.descripcion' => [
                'nullable'
            ],
            'estate.color' => [
                'required', new ValidateColor
            ],
            'estate.finish' => [
                'nullable', 'max:1', new DefaultValue('estates', 'finish', $this->estate->id)
            ],
            'estate.default' => [
                'nullable', 'max:1', new DefaultValue('estates', 'default', $this->estate->id)
            ],
        ];
    }

    public function render()
    {
        $status = Estate::where('delete', 0)->orderBy('name', 'asc')->paginate();
        return view('soporte::livewire.status.show-status', compact('status'));
    }

    public function updatedOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
        }
    }

    public function edit(Estate $estate)
    {
        $this->estate = $estate;
        $this->open = true;
    }

    public function update()
    {
        $this->estate->name = trim($this->estate->name);
        $this->estate->descripcion = trim($this->estate->descripcion);
        $this->estate->color = trim($this->estate->color);
        $this->validate();

        $this->estate->save();
        $this->reset(['open']);
    }

    public function confirmDelete(Estate $estate)
    {
        $this->dispatchBrowserEvent('soporte::status.confirmDelete', $estate);
    }

    public function delete(Estate $estate)
    {
        $estate->delete = 1;
        $estate->default = 0;
        $estate->finish = 0;
        $estate->descripcion = null;
        $estate->save();
        $this->dispatchBrowserEvent('soporte::status.deleted');
    }
}
