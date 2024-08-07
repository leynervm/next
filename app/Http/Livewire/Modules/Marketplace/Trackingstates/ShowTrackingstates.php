<?php

namespace App\Http\Livewire\Modules\Marketplace\Trackingstates;

use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Marketplace\Entities\Trackingstate;

class ShowTrackingstates extends Component
{

    use WithPagination, AuthorizesRequests;

    public $open = false;
    public $trackingstate;
    public $finish = 0;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'trackingstate.name' => [
                'required',  'string', 'min:3',
                new CampoUnique('trackingstates', 'name', $this->trackingstate->id, true)
            ],
            'trackingstate.background' => [
                'required',  'string', 'min:4', 'max:7', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
                new CampoUnique('trackingstates', 'background', $this->trackingstate->id, true)
            ],
            'finish' => [
                'required',  'boolean', 'min:0', 'max:1',
                new DefaultValue('trackingstates', 'finish', $this->trackingstate->id, true)
            ]
        ];
    }

    public function mount()
    {
        $this->trackingstate = new Trackingstate();
    }

    public function render()
    {
        $trackingstates = Trackingstate::orderBy('default', 'desc')->orderBy('id', 'asc')->paginate();
        return view('livewire.modules.marketplace.trackingstates.show-trackingstates', compact('trackingstates'));
    }

    public function edit(Trackingstate $trackingstate)
    {
        $this->authorize('admin.marketplace.trackingstates.edit');
        $this->resetValidation();
        $this->resetExcept(["trackingstate"]);
        $this->trackingstate = $trackingstate;
        $this->finish = $trackingstate->finish ? true : false;
        $this->open = true;
    }

    public function update()
    {
        $this->authorize('admin.marketplace.trackingstates.edit');
        // $this->finish = $this->finish ? 1 : 0;
        $this->trackingstate->name = trim($this->trackingstate->name);
        $this->trackingstate->finish = $this->finish ? 1 : 0;
        $this->validate();
        $this->trackingstate->save();
        $this->resetValidation();
        $this->resetExcept(['trackingstate']);
        $this->dispatchBrowserEvent('toast', toastJSON('Estado actualizado correctamente'));
    }

    public function delete(Trackingstate $trackingstate)
    {
        $this->authorize('admin.marketplace.trackingstates.delete');
        $trackingstate->delete();
        $this->dispatchBrowserEvent('toast', toastJSON('Estado eliminado correctamente'));
    }
}
