<?php

namespace App\Http\Livewire\Admin\Aperturas;

use App\Models\Box;
use App\Models\Monthbox;
use App\Models\Openbox;
use App\Rules\ValidateCaja;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateApertura extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $box_id, $apertura, $employer, $monthbox;
    public $selected;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'box_id' => [
                'required', 'integer', 'min:1', 'exists:boxes,id', new ValidateCaja()
            ],
            'apertura' => [
                'required', 'numeric', 'min:0', 'decimal:0,2'
            ],
            'employer.id' => [
                'required', 'integer', 'min:1', 'exists:employers,id'
            ],
        ];
    }

    public function mount()
    {
        $this->monthbox = Monthbox::usando(auth()->user()->sucursal_id)->first();
    }

    public function render()
    {
        $boxes = Box::with(['openboxes', 'user'])->sucursal()->orderBy('name', 'asc')->get();
        return view('livewire.admin.aperturas.create-apertura', compact('boxes'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.cajas.aperturas.create');
            $this->resetValidation();
            $this->resetExcept(['monthbox']);
        }
    }

    public function save()
    {

        $this->authorize('admin.cajas.aperturas.create');
        if (auth()->user()->employer()->exists() == false) {
            $mensaje = response()->json([
                'title' => 'VINCULAR USUARIO A UN PERSONAL DE TRABAJO !',
                'text' => 'Para aperturar nueva caja, el usuario debe estar vinculado a un personal, y poder asignar los horarios de apertura y cierre de caja automático.',
                'type' => 'warning'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        if (!$this->monthbox->isUsing()) {
            $mensaje =  response()->json([
                'title' => 'APERTURAR NUEVA CAJA MENSUAL !',
                'text' => "No se encontraron cajas mensuales activas para registrar movimiento."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        $this->employer = auth()->user()->employer;
        $this->validate();
        DB::beginTransaction();

        try {
            $openbox = Openbox::create([
                'startdate' => now('America/Lima')->format('Y-m-d ') . $this->employer->horaingreso,
                'expiredate' => now('America/Lima')->format('Y-m-d ') . $this->employer->horasalida,
                'apertura' => $this->apertura,
                'aperturarestante' => $this->apertura,
                'totalcash' => $this->apertura,
                'status' => 0,
                'box_id' => $this->box_id,
                'sucursal_id' => $this->employer->sucursal_id,
                'user_id' => auth()->user()->id,
            ]);
            $openbox->box->user_id = auth()->user()->id;
            $openbox->box->save();
            DB::commit();
            $this->emitTo('admin.aperturas.show-aperturas', 'render');
            $this->dispatchBrowserEvent('created');
            $this->resetExcept(['monthbox']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
