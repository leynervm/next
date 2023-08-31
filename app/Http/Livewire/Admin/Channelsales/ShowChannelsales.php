<?php

namespace App\Http\Livewire\Admin\Channelsales;

use App\Models\Channelsale;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Livewire\Component;
use Livewire\WithPagination;

class ShowChannelsales extends Component
{

    use WithPagination;

    public $open = false;
    public $channelsale;


    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'channelsale.name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('channelsales', 'name', $this->channelsale->id, true),
            ],
            'channelsale.default' => [
                'required', 'integer', 'min:0', 'max:1', new DefaultValue('channelsales', 'default', $this->channelsale->id, true)
            ]
        ];
    }

    public function mount()
    {
        $this->channelsale = new Channelsale();
    }

    public function render()
    {
        $channelsales = Channelsale::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.channelsales.show-channelsales', compact('channelsales'));
    }

    public function edit(Channelsale $channelsale)
    {
        $this->resetValidation();
        $this->channelsale = $channelsale;
        $this->open = true;
    }

    public function update()
    {
        $this->channelsale->name = trim($this->channelsale->name);
        $this->channelsale->default = $this->channelsale->default == 1 ? 1 : 0;
        $this->validate();
        $this->channelsale->save();
        $this->reset(['open']);
    }

    public function confirmDelete(Channelsale $channelsale)
    {
        $this->dispatchBrowserEvent('areas.confirmDelete', $channelsale);
    }

    public function delete(Channelsale $channelsale)
    {
        $channelsale->save();
        $this->dispatchBrowserEvent('deleted');
    }
}
