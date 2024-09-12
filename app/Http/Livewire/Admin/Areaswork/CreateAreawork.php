<?php

namespace App\Http\Livewire\Admin\Areaswork;

use App\Models\Areawork;
use App\Rules\CampoUnique;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class CreateAreawork extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $name, $visible = 0;

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                new CampoUnique('areaworks', 'name', null),
            ],
            'visible' => ['nullable', 'integer', 'min:0', 'max:1'],
        ];
    }

    public function render()
    {
        return view('livewire.admin.areaswork.create-areawork');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.administracion.areaswork.create');
            $this->resetValidation();
            $this->reset();
        }
    }

    public function save($closemodal = false)
    {
        $this->authorize('admin.administracion.areaswork.create');
        $this->name = trim(mb_strtoupper($this->name, "UTF-8"));
        $this->visible = $this->visible > 0 ? 1 : 0;
        $validateData = $this->validate();
        DB::beginTransaction();
        try {
            $areawork = Areawork::onlyTrashed()->where('name', $this->name)->first();
            if ($areawork) {
                $areawork->restore();
                $areawork->visible = $this->visible;
                $areawork->save();
            } else {
                Areawork::create($validateData);
            }
            DB::commit();
            $this->emitTo('admin.areaswork.show-areaswork', 'render');
            $this->dispatchBrowserEvent('created');
            if ($closemodal) {
                $this->reset();
            } else {
                $this->resetExcept(['open']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
