<?php

namespace App\Http\Livewire\Admin\Roles;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class ShowRoles extends Component
{

    use WithPagination, AuthorizesRequests;

    public function render()
    {
        $roles = Role::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.roles.show-roles', compact('roles'));
    }

    public function delete(Role $role)
    {
        $this->authorize('admin.roles.delete');
        $role->delete();
        $this->dispatchBrowserEvent('deleted');
    }
}
