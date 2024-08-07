<?php

namespace App\Http\Livewire\Modules\Administracion\Turnos;

use App\Models\Turno;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowTurnos extends Component
{

    use WithPagination, AuthorizesRequests;

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
        $this->authorize('admin.administracion.turnos.edit');
        $this->resetValidation();
        $this->reset();
        $this->turno = $turno;
        $this->open = true;
    }

    public function update()
    {
        $this->authorize('admin.administracion.turnos.edit');
        $this->turno->name = trim($this->turno->name);
        $this->validate();
        DB::beginTransaction();
        try {
            $this->turno->save();
            DB::commit();
            $this->dispatchBrowserEvent('updated');
            $this->resetExcept(['turno']);
            $this->resetValidation();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Turno $turno)
    {
        $this->authorize('admin.administracion.turnos.delete');
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
