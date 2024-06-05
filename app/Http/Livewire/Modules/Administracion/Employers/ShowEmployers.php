<?php

namespace App\Http\Livewire\Modules\Administracion\Employers;

use App\Models\Areawork;
use App\Models\Employer;
use App\Models\Sucursal;
use App\Models\Turno;
use App\Models\User;
use App\Rules\CampoUnique;
use App\Rules\ValidateNacimiento;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;


class ShowEmployers extends Component
{

    use WithPagination;
    use AuthorizesRequests;

    public $open = false;
    public $employer, $telefono, $email;
    public $search = '';
    public $searchsucursal = '';
    public $deletes = false;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'buscar'],
        'searchsucursal' => ['except' => '', 'as' => 'sucursal'],
        'deletes' => ['except' => false, 'as' => 'eliminados'],
    ];

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'employer.document' => [
                'required', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/',
                new CampoUnique('employers', 'document', $this->employer->id, true)
            ],
            'employer.name' => ['required', 'string', 'min:6'],
            'employer.nacimiento' => [
                'required', 'date', 'before:today',
                // new ValidateNacimiento(13)
            ],
            'employer.sexo' => ['required', 'string', 'min:1', 'max:1',  Rule::in(['M', 'F'])],
            'telefono' => ['required', 'numeric', 'digits_between:7,9', 'regex:/^\d{7}(?:\d{2})?$/'],
            'employer.sueldo' => ['required', 'numeric', 'min:0', 'gt:0', 'decimal:0,2'],
            'employer.turno_id' => ['required', 'integer', 'min:1', 'exists:turnos,id'],
            'employer.areawork_id' => ['nullable', 'integer', 'min:1', 'exists:areaworks,id'],
            'employer.sucursal_id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'employer.user_id' => [
                'nullable', 'integer', 'min:1', 'exists:users,id',
                new CampoUnique('employers', 'user_id', $this->employer->id, true)
            ],
        ];
    }

    public function mount()
    {
        $this->employer = new Employer();
    }

    public function render()
    {
        $sucursals = Sucursal::orderBy('name', 'asc')->get();
        $areaworks = Areawork::orderBy('name', 'asc')->get();
        $turnos = Turno::orderBy('horaingreso', 'asc')->get();
        $sucursalusers = Sucursal::whereHas('employers')->orderBy('name', 'asc')->get();

        $employers = Employer::with(['areawork', 'sucursal', 'user']);
        if (trim($this->search) != '') {
            $employers->where('document', 'ilike', '%' . $this->search . '%')
                ->orWhere('name', 'ilike', '%' . $this->search . '%');
        }
        if (trim($this->searchsucursal) != '') {
            $employers->where('sucursal_id', $this->searchsucursal);
        } else {
            $employers->where('sucursal_id', auth()->user()->sucursal_id);
        }

        if ($this->deletes) {
            $this->authorize('admin.administracion.employers.showdeletes');
            $employers->onlyTrashed();
        }

        $employers = $employers->orderBy('name', 'asc')->paginate();

        return view('livewire.modules.administracion.employers.show-employers', compact('employers', 'sucursals', 'turnos', 'areaworks', 'sucursalusers'));
    }

    public function edit(Employer $employer)
    {
        // $this->reset();
        $this->resetValidation();
        $this->employer = $employer;
        $this->telefono = $employer->telephone->phone ?? null;
        $this->open = true;
    }

    public function updatingDeletes()
    {
        $this->authorize('admin.administracion.employers.showdeletes');
    }

    public function update()
    {
        try {
            DB::beginTransaction();
            $this->validate();
            if ($this->employer->isDirty('sucursal_id')) {
                if ($this->employer->user) {
                    $existsopenboxes = $this->employer->user->openboxes()->open()->exists();
                    if ($existsopenboxes) {
                        $mensaje = response()->json([
                            'title' => 'USUARIO DEL PERSONAL TIENE CAJAS ACTIVAS EN USO !',
                            'text' => "Existen registros de cajas diarias vinculados al usuario del personal en sucursal asignado, primero debe cerrar las cajas aperturadas.",
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }
                }
            }

            $this->employer->save();
            $this->employer->telephone()->updateOrCreate(
                ['id' => $this->employer->telephone->id ?? null],
                ['phone' => trim($this->telefono)]
            );
            DB::commit();
            $this->reset(['open']);
            $this->resetValidation();
            $this->dispatchBrowserEvent('updated');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function desvincularuser()
    {
        try {
            DB::beginTransaction();
            if ($this->employer->user->openboxes()->open()->exists()) {
                $mensaje = response()->json([
                    'title' => 'PERSONAL TIENE CAJAS ACTIVAS EN USO !',
                    'text' => "Existen registros de cajas diarias vinculados al personal en sucursal asignado, primero debe cerrar las cajas aperturadas.",
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            if ($this->employer->user) {
                if (!$this->employer->user->isAdmin()) {
                    $this->employer->user->roles()->detach();
                    $this->employer->user->permissions()->detach();
                    $this->employer->user->sucursal_id = null;
                    $this->employer->user->save();
                }
            }

            $this->employer->user_id = null;
            $this->employer->save();
            DB::commit();
            $this->employer->refresh();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Employer $employer)
    {
        DB::beginTransaction();
        try {
            if ($employer->user) {
                if ($employer->user->openboxes()->open()->exists()) {
                    $mensaje = response()->json([
                        'title' => 'PERSONAL CUENTA CON UNA CAJA ACTIVA EN USO !',
                        'text' => 'Usuario del personal seleccionado cuenta con caja activa en uso, primero debe cerrar la caja activa.',
                        'type' => 'warning'
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                if ($employer->user) {
                    if (!$employer->user->isAdmin()) {
                        $employer->user->roles()->detach();
                        $employer->user->permissions()->detach();
                        $employer->user->sucursal_id = null;
                        $employer->user->save();
                    }
                }
                $employer->user_id = null;
                $employer->save();
            }

            $employer->delete();
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

    public function restoreemployer($employer)
    {
        $employer = Employer::onlyTrashed()->find($employer);
        $employer->restore();
        $this->dispatchBrowserEvent('updated');
    }

    public function getClient()
    {

        $this->employer->document = trim($this->employer->document);
        $this->validate([
            'employer.document' => [
                'required', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/',
                new CampoUnique('employers', 'document', $this->employer->id, true)
            ],
        ]);

        try {
            DB::beginTransaction();
            $user = User::whereDoesntHave('employer', function ($query) {
                $query->where('document', $this->employer->document);
            })->where('document', $this->employer->document)->first();

            if ($user) {
                if ($user->employer) {
                    $mensaje = response()->json([
                        'title' => 'USUARIO ENCONTRADO YA SE ENCUENTRA VINCULADO A UN PERSONAL !',
                        'text' => 'Usuario de acceso encontrado, ya se encuentra vinculado a un personal.',
                        'type' => 'warning'
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                } else {
                    $this->employer->user()->associate($user);
                    $this->employer->save();
                    $user->sucursal_id = $this->employer->sucursal_id;
                    $user->save();
                    $this->dispatchBrowserEvent('toast', toastJSON('Usuario vinculado correctamente'));
                }
            } else {
                $mensaje = response()->json([
                    'title' => 'NO SE ENCONTRARON USUARIOS CON LOS DATOS DEL PERSONAL !',
                    'text' => 'Personal no cuenta con usuarios registrados que tengan el mismo número de documento a sincronizar.',
                    'type' => 'warning'
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
