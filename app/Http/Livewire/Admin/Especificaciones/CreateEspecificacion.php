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
    public $name;
    public $view = 0;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:2', 'max:100',
                new CampoUnique('caracteristicas', 'name', null),
            ],
            'view' => ['nullable', 'integer', 'min:0', 'max:1',]
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

    public function save()
    {
        $this->authorize('admin.almacen.caracteristicas.create');
        $this->name = trim($this->name);
        $this->validate();
        DB::beginTransaction();
        try {
            Caracteristica::create([
                'name' => $this->name,
                'view' => $this->view,
            ]);
            DB::commit();
            $this->emitTo('admin.especificaciones.show-especificaciones', 'render');
            $this->dispatchBrowserEvent('created');
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
