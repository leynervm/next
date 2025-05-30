<?php

namespace App\Http\Livewire\Admin\Areaswork;

use App\Models\Areawork;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowAreaswork extends Component
{

    use AuthorizesRequests, WithPagination;

    public $areawork;
    public $open = false;
    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'areawork.name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                new CampoUnique('areaworks', 'name', $this->areawork->id),
            ],
            'areawork.addtickets' => ['nullable', 'integer', 'min:0', 'max:1'],
        ];
    }

    public function mount()
    {
        $this->areawork = new Areawork;
    }

    public function render()
    {
        $areaswork = Areawork::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.areaswork.show-areaswork', compact('areaswork'));
    }

    public function edit(Areawork $areawork)
    {
        $this->authorize('admin.administracion.areaswork.edit');
        $this->areawork = $areawork;
        $this->resetValidation();
        $this->open = true;
    }

    public function save()
    {
        $this->authorize('admin.administracion.areaswork.edit');
        $this->areawork->addtickets = $this->areawork->addtickets > 0 ? 1 : 0;
        $this->validate();
        $this->areawork->save();
        $this->resetValidation();
        $this->resetExcept(['areawork']);
        $this->dispatchBrowserEvent('updated');
    }

    public function delete(Areawork $areawork)
    {
        $this->authorize('admin.administracion.areaswork.delete');
        try {
            DB::beginTransaction();
            $areawork->delete();
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
