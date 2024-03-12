<?php

namespace App\Http\Livewire\Modules\Administracion\Employers;

use App\Helpers\GetClient;
use App\Models\Areawork;
use App\Models\Employer;
use App\Models\Sucursal;
use App\Models\User;
use App\Rules\CampoUnique;
use App\Rules\ValidateNacimiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateEmployer extends Component
{

    public $open = false;
    public $adduser = false;
    public $user;

    public $document, $name, $nacimiento, $sueldo, $horaingreso, $horasalida,
        $sexo, $areawork_id, $sucursal_id, $telefono;
    public $email, $password, $password_confirmation;
    public $selectedRoles = [];

    protected function rules()
    {
        return [
            'document' => [
                'required', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/',
                new CampoUnique('employers', 'document', null, true)
            ],
            'name' => ['required', 'string', 'min:6'],
            'nacimiento' => [
                'required', 'date', 'date_format:Y-m-d', 'before:today',
                // new ValidateNacimiento(13)
            ],
            'telefono' => ['required', 'numeric', 'digits_between:7,9', 'regex:/^\d{7}(?:\d{2})?$/'],
            'sexo' => ['required', 'string', 'min:1', 'max:1',  Rule::in(['M', 'F', 'E'])],
            'sueldo' => ['required', 'numeric', 'min:0', 'gt:0', 'decimal:0,2'],
            'horaingreso' => ['required', 'date_format:H:i'],
            'horasalida' => ['required', 'date_format:H:i'],
            'areawork_id' => ['nullable', 'integer', 'min:1', 'exists:areaworks,id'],
            'sucursal_id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],

            'email' => [
                'nullable', Rule::requiredIf($this->adduser && $this->user == null),
                'email', new CampoUnique('users', 'email', null, true)
            ],
            'password' => [
                'nullable', Rule::requiredIf($this->adduser && $this->user == null),
                'min:8', 'confirmed'
            ],
            'selectedRoles' => ['nullable', 'array', 'min:0', 'exists:roles,id'],
        ];
    }

    public function render()
    {
        $sucursals = Sucursal::orderBy('name', 'asc')->get();
        $areaworks = Areawork::orderBy('name', 'asc')->get();
        $roles = Role::all();

        return view('livewire.modules.administracion.employers.create-employer', compact('sucursals', 'areaworks', 'roles'));
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept(['open']);
            $this->resetValidation();
        }
    }

    public function save()
    {

        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->telefono = trim($this->telefono);
        $this->sueldo = trim($this->sueldo);
        if ($this->user) {
            if ($this->user->employer) {
                $this->addError('email', 'Correo ya se encuentra vinculado.');
                return false;
            }
        }

        $validateData = $this->validate();
        try {
            DB::beginTransaction();
            $employer = Employer::onlyTrashed()->where('document', $this->document)->first();

            if ($employer) {
                $employer->nacimiento = $this->nacimiento;
                $employer->sueldo = $this->sueldo;
                $employer->horaingreso = $this->horaingreso;
                $employer->horasalida = $this->horasalida;
                $employer->areawork_id = $this->areawork_id;
                $employer->sucursal_id = $this->sucursal_id;
                $employer->restore();
            } else {
                $employer = Employer::create([
                    'document' => $this->document,
                    'name' => $this->name,
                    'nacimiento' => $this->nacimiento,
                    'sexo' => $this->sexo,
                    'sueldo' => $this->sueldo,
                    'horaingreso' => $this->horaingreso,
                    'horasalida' => $this->horasalida,
                    'areawork_id' => $this->areawork_id,
                    'sucursal_id' => $this->sucursal_id,
                ]);
            }

            if ($this->adduser) {
                if ($this->user) {
                    $this->user->sucursal_id = $this->sucursal_id;
                    $this->user->save();
                    $employer->user()->associate($this->user);
                    $employer->save();
                } else {
                    $exists = User::whereDoesntHave('employer', function ($query) {
                        $query->where('document', $this->document);
                    })->where('document', $this->document)->exists();

                    if ($exists) {
                        $mensaje = response()->json([
                            'title' => 'YA EXISTE UN USUARIO CON LOS MISMOS DATOS INGRESADOS !',
                            'text' => 'Se encontraron registros de usuarios con los mismos datos ingresados.',
                            'type' => 'warning'
                        ])->getData();
                        $this->addError('document', 'Ya existe un usuario con el mismo documento.');
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    } else {
                        $user = User::create([
                            'document' => $this->document,
                            'name' => $this->name,
                            'email' => $this->email,
                            'password' => bcrypt($this->password),
                            'almacen_id' => null,
                            'sucursal_id' => $this->sucursal_id,
                        ]);

                        $user->roles()->sync($this->selectedRoles);
                        $employer->user()->associate($user);
                        $employer->save();
                    }
                }
            }

            if (!empty($this->telefono)) {
                $employer->telephone()->create([
                    'phone' => $this->telefono,
                ]);
            }
            DB::commit();
            $this->reset();
            $this->resetValidation();
            $this->emitTo('modules.administracion.employers.show-employers', 'render');
            $this->dispatchBrowserEvent('created');
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

    public function getClient()
    {

        $this->reset(['user', 'name']);
        $this->document = trim($this->document);
        $this->validate([
            'document' => [
                'required', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/',
                new CampoUnique('employers', 'document', null, true)
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->document);

        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->resetValidation(['document', 'name']);
                $this->name = $response->getData()->name;
                if ($response->getData()->birthday) {
                    $this->dispatchBrowserEvent('birthday', $response->getData()->name);
                }
            } else {
                $this->resetValidation(['document']);
                $this->addError('document', $response->getData()->message);
            }
        } else {
            $this->addError('document', 'Error de respuesta');
        }


        $user = User::whereDoesntHave('employer', function ($query) {
            $query->where('document', $this->document);
        })->where('document', $this->document)->first();

        if ($user) {
            $this->adduser = true;
            $this->name = trim($this->name) == '' ? $user->name : $this->name;

            if ($user->employer) {
                $this->addError('email', 'Correo ya se encuentra vinculado a un usuario.');
            } else {
                $this->reset(['email', 'password', 'password_confirmation']);
                $this->user = $user;
            }
        } else {
            $this->addError('email', 'No se encontraron registros.');
        }
    }

    // public function getUser()
    // {
    //     $this->email = trim($this->email);
    //     $this->validate(['email' => ['required', 'email']]);

    //     $user = User::where('email', $this->email)->first();
    //     if ($user) {
    //         if ($user->employer) {
    //             $this->addError('email', 'Correo ya se encuentra vinculado.');
    //         } else {
    //             $this->user = $user;
    //         }
    //     } else {
    //         $this->addError('email', 'No se encontraron registros.');
    //     }
    // }
}
