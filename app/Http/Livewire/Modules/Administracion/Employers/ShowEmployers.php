<?php

namespace App\Http\Livewire\Modules\Administracion\Employers;

use App\Models\Areawork;
use App\Models\Employer;
use App\Models\Sucursal;
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
    public $employer, $telefono, $email, $user;
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
            'employer.sexo' => ['required', 'string', 'min:1', 'max:1',  Rule::in(['M', 'F', 'E'])],
            'telefono' => ['required', 'numeric', 'digits_between:7,9', 'regex:/^\d{7}(?:\d{2})?$/'],
            'employer.sueldo' => ['required', 'numeric', 'min:0', 'gt:0', 'decimal:0,2'],
            'employer.horaingreso' => ['required', 'date_format:H:i'],
            'employer.horasalida' => ['required', 'date_format:H:i'],
            'employer.areawork_id' => ['nullable', 'integer', 'min:1', 'exists:areaworks,id'],
            'employer.sucursal_id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'employer.user_id' => [
                'nullable', Rule::requiredIf(!empty($this->user)),
                'integer', 'min:1', 'exists:users,id',
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
        $sucursalusers = Sucursal::whereHas('employers')->orderBy('name', 'asc')->get();

        $employers = Employer::with(['areawork', 'sucursal', 'user']);
        if (trim($this->search) != '') {
            $employers->where('document', 'ilike', '%' . $this->search . '%')
                ->orWhere('name', 'ilike', '%' . $this->search . '%');
        }
        if (trim($this->searchsucursal) != '') {
            $employers->where('sucursal_id', $this->searchsucursal);
        }

        if ($this->deletes) {
            $this->authorize('admin.administracion.employers.showdeletes');
            $employers->onlyTrashed();
        }

        $employers = $employers->orderBy('name', 'asc')->paginate();

        return view('livewire.modules.administracion.employers.show-employers', compact('employers', 'sucursals', 'areaworks', 'sucursalusers'));
    }

    public function edit(Employer $employer)
    {
        $this->reset();
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
        $this->employer->user_id = $this->user ? $this->user->id : $this->employer->user_id;
        try {
            DB::beginTransaction();
            $this->validate();
            if ($this->user) {
                if ($this->user->employer) {
                    $this->addError('email', 'Perfil de acceso ya se encuentra vinculado a un personal.');
                    return false;
                }

                $this->employer->user_id = $this->user->id;
                $this->user->sucursal_id = $this->employer->sucursal_id;
                $this->user->save();
            }

            $this->employer->save();
            $this->employer->telephone()->updateOrCreate(
                ['id' => $this->employer->telephone->id ?? null],
                ['phone' => trim($this->telefono)]
            );
            DB::commit();
            $this->resetExcept(['employer']);
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

    public function clearuser()
    {
        $this->reset(['user']);
    }

    public function desvincularuser()
    {
        try {
            DB::beginTransaction();
            if ($this->employer->user->openboxes()->mybox($this->employer->sucursal_id)->exists()) {
                $mensaje = response()->json([
                    'title' => 'PERSONAL CUENTA CON UNA CAJA ACTIVA EN USO !',
                    'text' => 'Usuario del personal seleccionado cuenta con caja activa en uso, primero debe cerrar la caja activa.',
                    'type' => 'warning'
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $this->employer->user->roles()->detach();
            $this->employer->user->permissions()->detach();
            $this->employer->user->sucursal_id = null;
            $this->employer->user->save();
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
        // dd($employer->user->openboxes()->mybox($employer->sucursal_id)->get());
        try {
            DB::beginTransaction();

            if ($employer->user) {
                if ($employer->user->openboxes()->mybox($employer->sucursal_id)->exists()) {
                    $mensaje = response()->json([
                        'title' => 'PERSONAL CUENTA CON UNA CAJA ACTIVA EN USO !',
                        'text' => 'Usuario del personal seleccionado cuenta con caja activa en uso, primero debe cerrar la caja activa.',
                        'type' => 'warning'
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                if ($employer->user) {
                    $employer->user->roles()->detach();
                    $employer->user->permissions()->detach();
                    $employer->user->sucursal_id = null;
                    $employer->user->save();
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

        $this->reset(['user']);
        $user = User::whereDoesntHave('employer', function ($query) {
            $query->where('document', $this->employer->document);
        })->where('document', $this->employer->document)->first();

        if ($user) {
            if ($user->employer) {
                $this->addError('email', 'Correo ya se encuentra vinculado a un usuario.');
            } else {
                $this->user = $user;
            }
        } else {
            $mensaje = response()->json([
                'title' => 'NO SE ENCONTRARON DATOS DE ACCESO PARA EL PERSONAL !',
                'text' => 'Personal no cuenta con usuario de acceso al sistema, para sincronizar los datos.',
                'type' => 'warning'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            $this->addError('email', 'No se encontraron registros.');
        }
    }
}
