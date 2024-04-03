<?php

namespace App\Http\Livewire\Modules\Administracion\Turnos;

use App\Models\Turno;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowTurnos extends Component
{

    use WithPagination;

    protected $listeners = ['render'];

    public $turno;
    public $open = false;

    protected function rules()
    {
        return [
            'turno.name' => [
                'required', 'string', 'min:3',
                new CampoUnique('turnos', 'name', $this->turno->id)
            ],
            'turno.horaingreso' => ['required', 'date_format:H:i'],
            'turno.horasalida' => ['required', 'date_format:H:i'],
            // 'sucursal_id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
        ];
    }

    public function mount()
    {
        $this->turno = new Turno();
    }

    public function render()
    {
        $turnos = Turno::orderBy('horaingreso', 'asc')->paginate();
        return view('livewire.modules.administracion.turnos.show-turnos', compact('turnos'));
    }

    public function edit(Turno $turno)
    {
        $this->resetValidation();
        $this->reset();
        $this->turno = $turno;
        $this->open = true;
    }

    public function delete(Turno $turno)
    {
        try {
            DB::beginTransaction();
            $turno->delete();
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
