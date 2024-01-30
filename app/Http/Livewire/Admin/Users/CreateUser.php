<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateUser extends Component
{


    public $name, $email, $password, $password_confirmation,
        $role_id, $sucursal_id, $almacen_id;


    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role_id' => ['nullable', 'integer', 'min:1', 'exists:roles,id'],
            'sucursal_id' => ['nullable', 'integer', 'min:1', 'exists:sucursals,id'],
            'almacen_id' => ['nullable', 'integer', 'min:1', 'exists:almacens,id']
        ];
    }


    public function render()
    {

        $roles = Role::all();
        $permisos = Permission::all();
        $sucursales = Sucursal::all();
        return view('livewire.admin.users.create-user', compact('roles', 'sucursales'));
    }

    public function save()
    {

        $this->name = trim($this->name);
        $this->email = trim($this->email);
        $this->password = trim($this->password);
        $this->password_confirmation = trim($this->password_confirmation);
        $this->sucursal_id = empty($this->sucursal_id) ? null : $this->sucursal_id;
        $validatedData  = $this->validate();

        try {
            DB::beginTransaction();
            if ($this->sucursal_id) {
                $this->almacen_id = Sucursal::find($this->sucursal_id)->almacenDefault()->first()->id ?? null;
            }

            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'role_id' => $this->role_id,
                'almacen_id' => $this->almacen_id,
                'sucursal_id' => $this->sucursal_id,
            ]);
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
}
