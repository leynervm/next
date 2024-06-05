<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Areawork;
use App\Models\Employer;
use App\Models\Sucursal;
use App\Models\Turno;
use App\Models\User;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShowUser extends Component
{

    public $user;
    public $sexo, $nacimiento, $telefono, $sueldo, $turno_id,
        $areawork_id, $sucursal_id;
    public $selectedRoles = [];
    public $addemployer = false;


    protected function rules()
    {
        return [
            'user.name' => ['required', 'string', 'min:3', 'string'],
            'user.email' => ['required', 'email', new CampoUnique('users', 'email', $this->user->id, true)],
            'user.sucursal_id' => [
                'nullable',
                Rule::requiredIf(Module::isEnabled('employer') && $this->addemployer || Module::isEnabled('employer') && $this->user->employer()->exists()),
                'integer', 'min:1', 'exists:sucursals,id'
            ],
            'nacimiento' => [
                'nullable', Rule::requiredIf($this->addemployer),
                'date', 'date_format:Y-m-d', 'before:today',
                // new ValidateNacimiento(13)
            ],
            'telefono' => [
                'nullable', Rule::requiredIf($this->addemployer),
                'numeric', 'digits_between:7,9', 'regex:/^\d{7}(?:\d{2})?$/'
            ],
            'sexo' => [
                'nullable', Rule::requiredIf($this->addemployer),
                'string', 'min:1', 'max:1',  Rule::in(['M', 'F', 'E'])
            ],
            'sueldo' => [
                'nullable', Rule::requiredIf($this->addemployer),
                'numeric', 'min:0', 'gt:0', 'decimal:0,2'
            ],
            // 'horaingreso' => [
            //     'nullable', Rule::requiredIf($this->addemployer),
            //     'date_format:H:i'
            // ],
            // 'horasalida' => [
            //     'nullable', Rule::requiredIf($this->addemployer),
            //     'date_format:H:i'
            // ],
            'turno_id' => [
                'nullable', Rule::requiredIf($this->addemployer),
                'integer', 'min:1', 'exists:turnos,id'
            ],
            'areawork_id' => ['nullable', 'integer', 'min:1', 'exists:areaworks,id'],
        ];
    }

    public function mount(User $user)
    {
        $this->selectedRoles = $user->roles()->pluck('id');
    }

    public function render()
    {
        $roles = Role::all();
        $permisos = Permission::all();
        $sucursales = Sucursal::orderBy('id', 'asc')->get();
        $areaworks = Areawork::orderBy('name', 'asc')->get();
        $turnos = Turno::orderBy('horaingreso', 'asc')->get();
        return view('livewire.admin.users.show-user', compact('roles', 'sucursales', 'turnos', 'areaworks'));
    }

    public function update()
    {

        $this->user->name = trim($this->user->name);
        $this->user->email = trim($this->user->email);
        $this->user->sucursal_id = empty($this->user->sucursal_id) ? null : $this->user->sucursal_id;

        $this->validate();
        try {
            DB::beginTransaction();

            if (Module::isEnabled('Employer')) {
                if ($this->user->employer) {
                    $this->user->employer->sucursal_id = $this->user->sucursal_id;
                    $this->user->employer->user_id = $this->user->sucursal_id == null ? null : $this->user->id;
                    $this->user->employer->save();
                }

                if ($this->addemployer) {
                    $exists = Employer::with(['sucursal', 'areawork'])->whereDoesntHave('user', function ($query) {
                        $query->where('document', $this->user->document);
                    })->where('document', $this->user->document)->exists();

                    if ($exists) {
                        $mensaje = response()->json([
                            'title' => 'YA EXISTE UN PERSONAL CON LOS MISMOS DATOS INGRESADOS !',
                            'text' => 'Se encontraron registros de trabajadores con los mismos datos ingresados.',
                            'type' => 'warning'
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    } else {
                        $employer = $this->user->employer()->create([
                            'document' => $this->user->document,
                            'name' => $this->user->name,
                            'nacimiento' => $this->nacimiento,
                            'sexo' => $this->sexo,
                            'sueldo' => $this->sueldo,
                            'turno_id' => $this->turno_id,
                            'areawork_id' => $this->areawork_id,
                            'sucursal_id' => $this->user->sucursal_id,
                        ]);

                        if (!empty($this->telefono)) {
                            $employer->telephone()->create([
                                'phone' => $this->telefono,
                            ]);
                        }
                    }
                }
            }

            if (empty($this->user->sucursal_id) || $this->user->isDirty('sucursal_id')) {
                $existsopenboxes = $this->user->openboxes()->open()->exists();
                if ($existsopenboxes) {
                    $mensaje = response()->json([
                        'title' => 'USUARIO TIENE CAJAS ACTIVAS EN USO !',
                        'text' => "Existen registros de cajas diarias vinculados al usuario en sucursal asignado, primero debe cerrar las cajas aperturadas.",
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }
            }

            $roles = empty($this->user->sucursal_id) ? [] : $this->selectedRoles;
            if (!$this->user->isAdmin()) {
                $this->user->roles()->sync($roles);
            }
            $this->user->save();
            DB::commit();
            $this->resetValidation();
            $this->dispatchBrowserEvent('updated');
            return redirect()->route('admin.users');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteemployer()
    {

        if (Module::isEnabled('Employer')) {
            $existsopenboxes = $this->user->openboxes()->open()->exists();
            if ($existsopenboxes) {
                $mensaje = response()->json([
                    'title' => 'USUARIO TIENE CAJAS ACTIVAS EN USO !',
                    'text' => "Existen registros de cajas diarias vinculados al usuario en sucursal asignado, primero debe cerrar las cajas aperturadas.",
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }


            if ($this->user->employer) {
                $this->user->employer->user_id = null;
                $this->user->employer->save();
            }
            $this->user->sucursal_id = null;
            $this->user->save();
            $this->user->refresh();
            $this->dispatchBrowserEvent('delete');
        }
    }

    public function searchemployer()
    {
        if (Module::isEnabled('Employer')) {
            $this->resetValidation(['document', 'name']);
            $this->user->document = trim($this->user->document);
            $this->validate([
                'user.document' => ['required', 'numeric', 'regex:/^\d{8}(?:\d{3})?$/']
            ]);

            try {
                DB::beginTransaction();
                $employer = Employer::with(['sucursal', 'areawork'])->whereDoesntHave('user', function ($query) {
                    $query->where('document', $this->user->document);
                })->where('document', $this->user->document)->first();
                if ($employer) {
                    $employer->user()->associate($this->user);
                    $employer->save();
                    $this->user->sucursal_id = $employer->sucursal_id;
                    $this->user->save();
                    $this->user->refresh();
                    $this->dispatchBrowserEvent('toast', toastJSON('Personal vinculado correctamente'));
                } else {
                    $mensaje = response()->json([
                        'title' => 'NO SE ENCONTRARON DATOS DEL PERSONAL !',
                        'text' => 'No se encontraron registros de personal con los datos del usuario.',
                        'type' => 'warning'
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
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
}
