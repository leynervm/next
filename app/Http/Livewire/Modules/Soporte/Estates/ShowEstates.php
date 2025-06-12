<?php

namespace App\Http\Livewire\Modules\Soporte\Estates;

use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use App\Rules\Letter;
use App\Rules\ValidateColor;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Soporte\Entities\Estate;

class ShowEstates extends Component
{

    use WithPagination;

    public $open = false;
    public $estate;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'estate.name' => [
                'required',
                'min:3',
                'max:100',
                new Letter,
                new CampoUnique('estates', 'name', $this->estate->id)
            ],
            'estate.descripcion' => [
                'nullable'
            ],
            'estate.color' => [
                'required',
                new ValidateColor
            ],
            'estate.finish' => [
                'nullable',
                'max:1',
                new DefaultValue('estates', 'finish', $this->estate->id)
            ],
            'estate.default' => [
                'nullable',
                'max:1',
                new DefaultValue('estates', 'default', $this->estate->id)
            ],
        ];
    }

    public function mount()
    {
        $this->estate = new Estate();
    }

    public function render()
    {
        $estates = Estate::orderByDesc('default')->orderByDesc('name', 'asc')->paginate();
        return view('livewire.modules.soporte.estates.show-estates', compact('estates'));
    }

    public function edit(Estate $estate)
    {
        $this->resetExcept('estate');
        $this->resetValidation();
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

    public function delete($id)
    {
        if ($id) {
            $estate = Estate::withCount('tickets')->withCount('procesos')->find($id);
            if ($estate) {
                $estate->atencions()->detach();
                if ($estate->tickets_count > 0 || $estate->procesos_count > 0) {
                    $estate->delete();
                } else {
                    $estate->forceDelete();
                }
                return $this->dispatchBrowserEvent('deleted');
            }
        }

        return $this->dispatchBrowserEvent('toast', toastJSON('NO SE ENCONTRÓ EL REGISTRO', 'error'));
    }
}
