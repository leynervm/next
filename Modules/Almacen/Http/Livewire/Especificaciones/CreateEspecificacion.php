<?php

namespace Modules\Almacen\Http\Livewire\Especificaciones;

use App\Models\Caracteristica;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateEspecificacion extends Component
{

    public $open = false;
    public $name;
    public $view = 0;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('caracteristicas', 'name', null, true),
            ],
            'view' => [
                'nullable', 'integer', 'min:0', 'max:1',
            ]
        ];
    }

    public function render()
    {
        return view('almacen::livewire.especificaciones.create-especificacion');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset('name', 'view');
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->validate();

        DB::beginTransaction();

        try {
            $caracteristica = Caracteristica::withTrashed()
                ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

            if ($caracteristica) {
                $caracteristica->view = $this->view;
                $caracteristica->restore();
            } else {
                Caracteristica::create([
                    'name' => $this->name,
                    'view' => $this->view,
                ]);
            }

            DB::commit();
            $this->emitTo('almacen::especificaciones.show-especificaciones', 'render');
            $this->reset('name', 'view', 'open');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
