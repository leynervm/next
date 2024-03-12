<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Employer;
use App\Models\Sucursal;
use App\Models\User;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShowUser extends Component
{

    public $user;
    public $selectedRoles = [];


    protected function rules()
    {
        return [
            'user.name' => ['required', 'string', 'min:3', 'string'],
            'user.email' => ['required', 'email', new CampoUnique('users', 'email', $this->user->id, true)],
            'user.sucursal_id' => ['nullable', 'integer', 'min:1', 'exists:sucursals,id'],
            // 'user.almacen_id' => ['nullable', 'integer', 'min:1', 'exists:almacens,id']
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
                // $this->user->almacen_id = Sucursal::find($this->user->sucursal_id)->almacenDefault()->first()->id ?? null;
            } else {
                // $this->user->almacen_id = null;
            }

            $this->user->save();
            $this->user->roles()->sync($this->selectedRoles);
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
        $this->user->employer->user_id = null;
        $this->user->employer->save();
        $this->user->refresh();
        $this->dispatchBrowserEvent('updated');
    }
}
