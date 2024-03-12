<?php

namespace App\Http\Livewire\Admin\Roles;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class ShowRoles extends Component
{

    use WithPagination;

    public function render()
    {
        $roles = Role::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.roles.show-roles', compact('roles'));
    }

    public function delete(Role $role)
    {
        $role->delete();
        $this->dispatchBrowserEvent('deleted');
    }
}
