<?php

namespace App\Http\Livewire\Admin\Almacens;

use App\Helpers\FormatoPersonalizado;
use App\Models\Almacen;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowAlmacens extends Component
{

    use WithPagination, AuthorizesRequests;

    protected $listeners = ['render'];

    public $open = false;
    public $almacen;

    protected function rules()
    {
        return [
            'almacen.name' => [
                'required', 'string', 'min:3', 'max:100',
                new CampoUnique('almacens', 'name', $this->almacen->id, true)
            ],
            'almacen.default' => [
                'required', 'integer', 'min:0', 'max:1',
                new DefaultValue('almacens', 'default', $this->almacen->id, true)
            ],
        ];
    }

    public function mount()
    {
        $this->almacen = new Almacen();
    }

    public function render()
    {
        $almacens = Almacen::orderBy('default', 'desc')->orderBy('name', 'asc')->paginate();
        return view('livewire.admin.almacens.show-almacens', compact('almacens'));
    }

    public function edit(Almacen $almacen)
    {
        $this->authorize('admin.almacen.edit');
        $this->resetValidation();
        $this->resetExcept(['almacen']);
        $this->almacen = $almacen;
        $this->open = true;
    }

    public function update()
    {
        $this->authorize('admin.almacen.edit');
        $this->almacen->default = $this->almacen->default == '1' ? 1 : 0;
        $this->validate();
        $this->almacen->save();
        $this->dispatchBrowserEvent('updated');
        $this->resetExcept(['almacen']);
        $this->resetValidation();
    }

    public function delete(Almacen $almacen)
    {

        $this->authorize('admin.almacen.delete');
        DB::beginTransaction();
        try {
            $almacen->productos()->detach();
            $almacen->delete();
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
