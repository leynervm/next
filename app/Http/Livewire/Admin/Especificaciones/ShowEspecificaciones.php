<?php

namespace App\Http\Livewire\Admin\Especificaciones;

use App\Models\Caracteristica;
use App\Models\Especificacion;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ShowEspecificaciones extends Component
{

    use WithPagination, AuthorizesRequests;

    public $caracteristica;
    public $especificacion;

    public $open = false;
    public $openespecificacion = false;
    public $openeditespecificacion = false;

    public $name;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'caracteristica.name' => [
                'required', 'min:2', 'max:100',
                new CampoUnique('caracteristicas', 'name', $this->caracteristica->id),
            ],
            'caracteristica.view' => [
                'nullable', 'integer', 'min:0', 'max:1',
            ]
        ];
    }

    public function mount()
    {
        $this->caracteristica = new Caracteristica();
        $this->especificacion = new Especificacion();
    }


    public function render()
    {
        $caracteristicas = Caracteristica::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.especificaciones.show-especificaciones', compact('caracteristicas'));
    }

    public function edit(Caracteristica $caracteristica)
    {
        $this->authorize('admin.almacen.caracteristicas.edit');
        $this->caracteristica = $caracteristica;
        $this->resetValidation();
        $this->open = true;
    }

    public function update()
    {
        $this->authorize('admin.almacen.caracteristicas.edit');
        $this->caracteristica->name = trim($this->caracteristica->name);
        $this->caracteristica->view = $this->caracteristica->view == 1 ? 1 : 0;
        $this->validate();
        $this->caracteristica->save();
        $this->reset(['open']);
        $this->dispatchBrowserEvent('updated');
    }

    public function addespecificacion(Caracteristica $caracteristica)
    {
        $this->authorize('admin.almacen.especificacions.create');
        $this->resetValidation();
        $this->reset(['name', 'especificacion']);
        $this->caracteristica = $caracteristica;
        $this->openespecificacion = true;
    }

    public function saveespecificacion()
    {
        $this->authorize('admin.almacen.especificacions.create');
        $this->name = trim($this->name);
        $validatedata = $this->validate([
            'name' => [
                'required', 'min:2',
                new CampoUnique('especificacions', 'name', null, false, 'caracteristica_id', $this->caracteristica->id)
            ]
        ]);

        $this->caracteristica->especificacions()->create([
            'name' => $this->name
        ]);
        $this->resetValidation();
        $this->reset(['name', 'openespecificacion']);
        $this->dispatchBrowserEvent('created');
    }

    public function editespecificacion(Especificacion $especificacion)
    {
        $this->authorize('admin.almacen.especificacions.edit');
        $this->resetValidation();
        $this->reset(['name', 'especificacion']);
        $this->especificacion = $especificacion;
        $this->name = $especificacion->name;
        $this->openeditespecificacion = true;
    }

    public function update_especificacion()
    {

        $this->authorize('admin.almacen.especificacions.edit');
        $this->name = trim($this->name);
        $validatedata = $this->validate([
            'name' => [
                'required', 'min:2',
                Rule::unique('especificacions', 'name')
                    ->where('caracteristica_id', $this->especificacion->caracteristica_id)
                    ->ignore($this->especificacion->id)
            ],
            'especificacion.caracteristica_id' => [
                'required', 'exists:caracteristicas,id'
            ]
        ]);

        $this->especificacion->name = trim($this->name);
        $this->especificacion->save();
        $this->resetValidation();
        $this->reset(['name', 'especificacion', 'openeditespecificacion']);
        $this->dispatchBrowserEvent('updated');
    }

    public function delete(Caracteristica $caracteristica)
    {

        $this->authorize('admin.almacen.caracteristicas.delete');
        DB::beginTransaction();
        try {
            $caracteristica->especificacions()->delete();
            $caracteristica->delete();
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

    public function deleteEspecificacion(Especificacion $especificacion)
    {

        $this->authorize('admin.almacen.especificacions.delete');
        DB::beginTransaction();
        try {
            $especificacion->delete();
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
