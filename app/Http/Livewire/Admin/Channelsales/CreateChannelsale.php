<?php

namespace App\Http\Livewire\Admin\Channelsales;

use App\Models\Channelsale;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Livewire\Component;

class CreateChannelsale extends Component
{

    public $open = false;
    public $name;
    public $default = 0;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('channelsales', 'name', null, true),
            ],
            'default' => [
                'required', 'integer', 'min:0', 'max:1', new DefaultValue('channelsales', 'default', null, true)
            ]
        ];
    }

    public function render()
    {
        return view('livewire.admin.channelsales.create-channelsale');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset();
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->default = $this->default == 1 ? 1 : 0;
        $this->validate();

        $channelsale = Channelsale::withTrashed()
            ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

        if ($channelsale) {
            $channelsale->default =  $this->default;
            $channelsale->restore();
        } else {
            Channelsale::create([
                'name' => $this->name,
                'default' => $this->default
            ]);
        }

        $this->emitTo('admin.channelsales.show-channelsales', 'render');
        $this->reset();
    }
}
