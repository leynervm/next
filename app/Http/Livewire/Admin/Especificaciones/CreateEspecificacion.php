<?php

namespace App\Http\Livewire\Admin\Especificaciones;

use App\Models\Caracteristica;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateEspecificacion extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $name, $view = 0, $filterweb = 0;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:2', 'max:100',
                new CampoUnique('caracteristicas', 'name', null),
            ],
            'filterweb' => ['nullable', 'integer', 'min:0', 'max:1'],
            'view' => ['nullable', 'integer', 'min:0', 'max:1']
        ];
    }

    public function render()
    {
        return view('livewire.admin.especificaciones.create-especificacion');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.almacen.caracteristicas.create');
            $this->resetValidation();
            $this->reset();
        }
    }

    public function save($closemodal = false)
    {
        $this->authorize('admin.almacen.caracteristicas.create');
        $this->name = trim($this->name);
        $this->filterweb = $this->filterweb > 0 ? 1 : 0;
        $this->view = $this->view > 0 ? 1 : 0;
        $this->validate();
        DB::beginTransaction();
        try {
            $orden = Caracteristica::max('orden') ?? 0;
            Caracteristica::create([
                'name' => $this->name,
                'view' => $this->view,
                'filterweb' => $this->filterweb,
                'orden' => $orden + 1
            ]);
            DB::commit();
            $this->emitTo('admin.especificaciones.show-especificaciones', 'render');
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
