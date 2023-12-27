<?php

namespace App\Http\Livewire\Admin\Especificaciones;

use App\Models\Caracteristica;
use App\Models\Especificacion;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ShowEspecificaciones extends Component
{

    use WithPagination;

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
                'required', 'min:3', 'max:100',
                new CampoUnique('caracteristicas', 'name', $this->caracteristica->id, true),
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
        $this->caracteristica = $caracteristica;
        $this->resetValidation();
        $this->open = true;
    }

    public function update()
    {
        $this->caracteristica->name = trim($this->caracteristica->name);
        $this->caracteristica->view = $this->caracteristica->view == 1 ? 1 : 0;
        $this->validate();
        $this->caracteristica->save();
        $this->reset(['open']);
    }

    public function openmodalespecificacion(Caracteristica $caracteristica)
    {

        $this->resetValidation(['name', 'especificacion']);
        $this->reset(['name', 'especificacion']);
        $this->caracteristica = $caracteristica;
        $this->openespecificacion = true;
    }

    public function add_especificacion()
    {
        $this->name = trim($this->name);
        $validatedata = $this->validate([
            'name' => [
                'required', 'min:2',
                Rule::unique('especificacions', 'name')
                    ->where('caracteristica_id', $this->caracteristica->id)
                    ->whereNull('deleted_at')
            ]
        ]);


        $especificacion = $this->caracteristica->especificacions()->withTrashed()
            ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

        if ($especificacion) {
            $especificacion->restore();
        } else {
            $this->caracteristica->especificacions()->create([
                'name' => $this->name
            ]);
        }

        $this->resetValidation(['name']);
        $this->reset(['name', 'openespecificacion']);
    }

    public function editespecificacion(Especificacion $especificacion)
    {
        $this->resetValidation(['name', 'especificacion']);
        $this->reset(['name', 'especificacion']);
        $this->especificacion = $especificacion;
        $this->name = $especificacion->name;
        $this->openeditespecificacion = true;
    }

    public function update_especificacion()
    {
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
        $this->resetValidation(['name', 'especificacion']);
        $this->reset(['name', 'especificacion', 'openeditespecificacion']);
    }

    public function delete(Caracteristica $caracteristica)
    {
        DB::beginTransaction();
        try {

            $caracteristica->especificacions()->delete();
            $caracteristica->deleteOrFail();
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
        DB::beginTransaction();
        try {
            $especificacion->deleteOrFail();
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
