<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Areawork;
use App\Models\Employer;
use App\Models\Sucursal;
use App\Models\Turno;
use App\Models\User;
use App\Rules\CampoUnique;
use App\Rules\ValidateDocument;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateUser extends Component
{


    use AuthorizesRequests;

    public $open = false;
    public $addemployer = false;

    public $document, $name, $email, $password, $password_confirmation,
        $sucursal_id, $almacen_id;
    public $nacimiento, $sueldo, $horaingreso, $horasalida, $turno_id,
        $sexo, $areawork_id,   $telefono, $employer;
    public $selectedRoles = [];

    public $userasign, $searchemail;


    protected function rules()
    {
        return [
            'document' => [
                'required',
                'numeric',
                'regex:/^\d{8}(?:\d{3})?$/',
                new CampoUnique('users', 'document', null, true)
            ],
            'name' => ['required', 'string', 'min:3', 'string'],
            'email' => ['required', 'email', new CampoUnique('users', 'email', null, true)],
            'password' => ['required', 'min:8', 'confirmed'],
            'selectedRoles' => ['nullable', 'array', 'min:0', 'exists:roles,id'],

            'nacimiento' => [
                'nullable',
                Rule::requiredIf($this->addemployer && $this->employer == null),
                'date',
                'date_format:Y-m-d',
                'before:today',
                // new ValidateNacimiento(13)
            ],
            'telefono' => [
                'nullable',
                Rule::requiredIf($this->addemployer && $this->employer == null),
                'numeric',
                'digits_between:7,9',
                'regex:/^\d{7}(?:\d{2})?$/'
            ],
            'sexo' => [
                'nullable',
                Rule::requiredIf($this->addemployer && $this->employer == null),
                'string',
                'min:1',
                'max:1',
                Rule::in(['M', 'F', 'E'])
            ],
            'sueldo' => [
                'nullable',
                Rule::requiredIf($this->addemployer && $this->employer == null),
                'numeric',
                'min:0',
                'gt:0',
                'decimal:0,2'
            ],
            // 'horaingreso' => [
            //     'nullable', Rule::requiredIf($this->addemployer && $this->employer == null),
            //     'date_format:H:i'
            // ],
            // 'horasalida' => [
            //     'nullable', Rule::requiredIf($this->addemployer && $this->employer == null),
            //     'date_format:H:i'
            // ],
            'turno_id' => [
                'nullable',
                Rule::requiredIf($this->addemployer && $this->employer == null),
                'integer',
                'min:1',
                'exists:turnos,id'
            ],
            'areawork_id' => [
                'nullable',
                Rule::requiredIf($this->addemployer && $this->employer == null),
                'integer',
                'min:1',
                'exists:areaworks,id'
            ],
            'sucursal_id' => [
                'nullable',
                Rule::requiredIf($this->addemployer && $this->employer == null),
                'integer',
                'min:1',
                'exists:sucursals,id'
            ],
            'almacen_id' => ['nullable', 'integer', 'min:1', 'exists:almacens,id'],

            'employer.id' => ['nullable', 'integer', 'min:1', 'exists:employers,id']
        ];
    }

    public function render()
    {
        $roles = Role::all();
        $permisos = Permission::all();
        $sucursales = Sucursal::all();
        $areaworks = Areawork::orderBy('name', 'asc')->get();
        $turnos = Turno::orderBy('horaingreso', 'asc')->get();
        return view('livewire.admin.users.create-user', compact('roles', 'sucursales', 'turnos', 'areaworks'));
    }

    public function save()
    {
        $this->authorize('admin.users.create');
        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->email = trim($this->email);
        $this->password = trim($this->password);
        $this->password_confirmation = trim($this->password_confirmation);
        $this->sucursal_id = empty($this->sucursal_id) ? null : $this->sucursal_id;
        $validatedData  = $this->validate();
        DB::beginTransaction();

        try {

            $user = User::onlyTrashed()->where('document', $this->document)->first();

            if ($user) {
                $user->name = $this->name;
                $user->email = $this->email;
                $user->password = bcrypt($this->password);
                $user->sucursal_id = !empty($this->sucursal_id) ? $this->sucursal_id : null;
                $user->restore();
            } else {
                $user = User::create([
                    'document' => $this->document,
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => bcrypt($this->password),
                    'sucursal_id' => !empty($this->sucursal_id) ? $this->sucursal_id : null,
                ]);
            }

            if (!empty($this->sucursal_id)) {
                $user->roles()->sync($this->selectedRoles);
            }

            if (Module::isEnabled('Employer')) {
                if ($this->addemployer) {
                    if ($this->employer) {
                        $this->employer->user_id = $user->id;
                        $this->employer->save();
                    } else {
                        $exists = Employer::with(['sucursal', 'areawork'])->whereDoesntHave('user', function ($query) {
                            $query->where('document', $this->document);
                        })->where('document', $this->document)->exists();

                        if ($exists) {
                            $mensaje = response()->json([
                                'title' => 'YA EXISTE UN TRABAJADOR CON LOS MISMOS DATOS INGRESADOS !',
                                'text' => 'Se encontraron registros de trabajadores con los mismos datos ingresados.',
                                'type' => 'warning'
                            ])->getData();
                            $this->addError('document', 'Ya existe un trabajador con el mismo documento.');
                            $this->dispatchBrowserEvent('validation', $mensaje);
                            return false;
                        } else {
                            $employer = $user->employer()->create([
                                'document' => $this->document,
                                'name' => $this->name,
                                'nacimiento' => $this->nacimiento,
                                'sexo' => $this->sexo,
                                'sueldo' => $this->sueldo,
                                // 'horaingreso' => $this->horaingreso,
                                // 'horasalida' => $this->horasalida,
                                'turno_id' => $this->turno_id,
                                'areawork_id' => $this->areawork_id,
                                'sucursal_id' => $this->sucursal_id,
                            ]);

                            if (!empty($this->telefono)) {
                                $employer->telephone()->create([
                                    'phone' => $this->telefono,
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            $this->reset();
            $this->resetValidation();
            $this->dispatchBrowserEvent('created');
            return redirect()->route('admin.users');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function searchclient()
    {
        $this->authorize('admin.users.create');
        $this->resetValidation(['document', 'name']);
        $this->reset(['name', 'employer']);
        $this->document = trim($this->document);
        $this->validate([
            'document' => [
                'required',
                'numeric',
                'regex:/^\d{8}(?:\d{3})?$/',
                new ValidateDocument,
                new CampoUnique('users', 'document', null, true)
            ]
        ]);

        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->document,
            'searchbd' => true,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());
            if (isset($cliente->success) && $cliente->success) {
                $this->name = $cliente->name;
                if ($cliente->birthday) {
                    $this->dispatchBrowserEvent('birthday', $cliente->name);
                }
            } else {
                $this->name = '';
                $this->addError('document', $cliente->error);
            }
        } else {
            $mensaje =  response()->json([
                'title' => 'Error:' . $response->status() . ' ' . $response->json(),
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        if (Module::isEnabled('Employer')) {
            $this->employer = Employer::with(['sucursal', 'areawork'])->whereDoesntHave('user', function ($query) {
                $query->where('document', $this->document);
            })->where('document', $this->document)->first();
            if ($this->employer) {
                $this->addemployer = true;
                $this->sucursal_id = $this->employer->sucursal_id;
                $mensaje = response()->json([
                    'title' => 'DATOS DEL PERSONAL ENCONTRADO, SE VINCULARÁ CON EL USUARIO DE ACCESO',
                    'text' => null,
                    'type' => 'warning'
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
            }
        }
    }

    public function deleteemployer()
    {
        $this->authorize('admin.users.create');
        if (Module::isEnabled('Employer')) {
            $this->reset(['employer']);
        }
    }
}
