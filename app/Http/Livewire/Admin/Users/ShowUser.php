<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShowUser extends Component
{

    public User $user;


    protected function rules()
    {
        return [
            'user.name' => ['required', 'string', 'min:3', 'string'],
            'user.email' => ['required', 'email', 'unique:users,email,' . $this->user->id],
            'user.role_id' => ['nullable', 'integer', 'min:1', 'exists:roles,id'],
            'user.sucursal_id' => ['nullable', 'integer', 'min:1', 'exists:sucursals,id'],
            'user.almacen_id' => ['nullable', 'integer', 'min:1', 'exists:almacens,id']
        ];
    }

    public function render()
    {
        $roles = Role::all();
        $permisos = Permission::all();
        $sucursales = Sucursal::all();
        return view('livewire.admin.users.show-user', compact('roles', 'sucursales'));
    }

    public function update()
    {

        $this->user->name = trim($this->user->name);
        $this->user->email = trim($this->user->email);
        $this->user->sucursal_id = empty($this->user->sucursal_id) ? null : $this->user->sucursal_id;
        $validateData = $this->validate();
        try {
            DB::beginTransaction();
            if ($this->user->sucursal_id) {
                $this->user->almacen_id = Sucursal::find($this->user->sucursal_id)->almacenDefault()->first()->id ?? null;
            }
            else {
                $this->user->almacen_id = null;
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
}
