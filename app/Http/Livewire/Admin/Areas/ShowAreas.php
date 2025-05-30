<?php

namespace App\Http\Livewire\Admin\Areas;

use App\Models\Areawork;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Soporte\Entities\Entorno;

class ShowAreas extends Component
{

    public $open = false;
    public $entornos;
    public $area;
    public $selectedEntornos = [];

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'area.name' => [
                'required',
                'min:3',
                'max:100',
                new Letter,
                new CampoUnique('areas', 'name', $this->area->id),
            ],
            'area.slug' => [
                'required',
                'min:3',
                'max:100',
                new CampoUnique('areas', 'slug', $this->area->id),
            ],
            'area.visible' => [
                'nullable',
            ],
            'selectedEntornos' => [
                'required',
                'min:1'
            ]
        ];
    }

    public function mount()
    {
        $this->area = new Areawork();
        $this->entornos = Entorno::where('delete', 0)->orderBy('name', 'asc')->get();
    }

    public function render()
    {
        $areas = Areawork::where('delete', 0)->orderBy('name', 'asc')->paginate();
        return view('livewire.admin.areas.show-areas', compact('areas'));
    }

    public function edit(Areawork $area)
    {
        $this->area = $area;
        $this->selectedEntornos = $area->entornos()->pluck('entorno_id')->toArray();
        $this->open = true;
    }

    public function update()
    {
        $this->area->name = trim($this->area->name);
        $this->area->slug = trim($this->area->slug);
        // $this->area->visible = $this->area->visible ;
        $this->validate();

        $this->area->save();
        $this->area->entornos()->syncWithPivotValues($this->selectedEntornos, ['user_id' => Auth::user()->id, 'updated_at' => now('America/Lima')]);
        $this->reset(['open']);
    }

    public function confirmDelete(Areawork $area)
    {
        $this->dispatchBrowserEvent('areas.confirmDelete', $area);
    }

    public function delete(Areawork $area)
    {
        $area->delete = 1;
        $area->visible = 0;
        $area->save();
        $area->entornos()->detach();
        $this->dispatchBrowserEvent('deleted');
    }
}
