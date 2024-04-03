<?php

namespace App\Http\Livewire\Admin\Aperturas;

use App\Enums\MovimientosEnum;
use App\Models\Box;
use App\Models\Concept;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Monthbox;
use App\Models\Openbox;
use App\Rules\ValidateCaja;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Nwidart\Modules\Facades\Module;

class CreateApertura extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $box_id, $apertura, $employer, $monthbox, $expiredate,
        $moneda_id, $concept_id, $methodpayment_id;
    // public $selected;

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
                'nullable',
                auth()->user()->isAdmin() ? '' : Rule::requiredIf(Module::isEnabled('Employer')),
                'integer', 'min:1', 'exists:employers,id'
            ],
            'methodpayment_id' => [
                'required', 'integer', 'min:1', 'exists:methodpayments,id'
            ],
            'concept_id' => [
                'required', 'integer', 'min:1', 'exists:concepts,id'
            ],
            'monthbox.id' => [
                'required', 'integer', 'min:1', 'exists:monthboxes,id'
            ],
            'moneda_id' => [
                'required', 'integer', 'min:1', 'exists:monedas,id'
            ],
            'expiredate' => [
                'required', 'date',
            ]
        ];
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
            $this->reset();
            if (Module::isDisabled('Employer') || auth()->user()->isAdmin()) {
                $this->expiredate = Carbon::now('America/Lima')->endOfDay()->format('Y-m-d\TH:i');
            }
        }
    }

    public function save()
    {

        $this->authorize('admin.cajas.aperturas.create');

        $this->monthbox = Monthbox::usando(auth()->user()->sucursal_id)->first();
        $startdate = now('America/Lima')->format('Y-m-d H:i:s');

        if (Module::isEnabled('Employer')) {
            if (!auth()->user()->isAdmin() && !auth()->user()->employer()->exists()) {
                $mensaje = response()->json([
                    'title' => 'VINCULAR USUARIO A UN PERSONAL DE TRABAJO !',
                    'text' => 'Para aperturar nueva caja, el usuario debe estar vinculado a un personal, y poder asignar los horarios de apertura y cierre de caja.',
                    'type' => 'warning'
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            if (auth()->user()->employer()->exists()) {
                $this->employer = auth()->user()->employer;

                if (!$this->employer->turno) {
                    $mensaje = response()->json([
                        'title' => 'ASIGNAR TURNO LABORAL AL PERSONAL VINCULADO !',
                        'text' => 'El personal está vinculado, pero no cuenta con un turno laboral para configurar los horarios de apertura y cierre de caja.',
                        'type' => 'warning'
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                $startdate = now('America/Lima')->format('Y-m-d ') . $this->employer->turno->horaingreso;
                $this->expiredate = now('America/Lima')->format('Y-m-d ') . $this->employer->turno->horasalida;
            }
        }

        if (!$this->monthbox || !$this->monthbox->isUsing()) {
            $mensaje =  response()->json([
                'title' => 'APERTURAR NUEVA CAJA MENSUAL !',
                'text' => "No se encontraron cajas mensuales activas para registrar movimiento."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        $this->methodpayment_id = Methodpayment::efectivo()->first()->id ?? null;
        $this->moneda_id = Moneda::default()->first()->id ?? null;
        $this->concept_id = Concept::openbox()->first()->id ?? null;

        if (!$this->methodpayment_id) {
            $mensaje =  response()->json([
                'title' => 'REGISTRAR AL MENOS UNA FORMA DE PAGO EN EFECTIVO !',
                'text' => "No se encontraron formas de pago en efectivo, para realizar apertura de caja."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        $this->validate();
        DB::beginTransaction();

        try {
            $openbox = Openbox::create([
                'startdate' => $startdate,
                'expiredate' => $this->expiredate,
                'apertura' => $this->apertura,
                'status' => 0,
                'box_id' => $this->box_id,
                'monthbox_id' => $this->monthbox->id,
                'sucursal_id' => auth()->user()->sucursal_id,
                'user_id' => auth()->user()->id,
            ]);
            $openbox->box->user_id = auth()->user()->id;
            $openbox->box->save();

            $openbox->savePayment(
                auth()->user()->sucursal_id,
                number_format($this->apertura, 3, '.', ''),
                $this->moneda_id,
                $this->methodpayment_id,
                MovimientosEnum::INGRESO->value,
                $this->concept_id,
                $openbox->id,
                $this->monthbox->id,
                null,
                'APERTURA DE CAJA DIARIA',
            );
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
