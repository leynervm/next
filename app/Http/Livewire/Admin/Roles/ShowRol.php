<?php

namespace App\Http\Livewire\Admin\Roles;

use App\Models\Permission;
use App\Rules\CampoUnique;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class ShowRol extends Component
{

    public $role;
    public $selectedPermisos = [];

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->selectedPermisos = $role->permissions()
            ->modulesActivePermission()->pluck('id');
    }

    protected function rules()
    {
        return [
            'role.name' => ['required', 'string', 'min:2', new CampoUnique('roles', 'name', $this->role->id, false)],
            'selectedPermisos' => ['required', 'array', 'min:1']
        ];
    }

    public function render()
    {
        $permisos = Permission::modulesActivePermission()->orderBy('orden', 'asc')
            ->orderBy('table', 'asc')->orderBy('id', 'asc')->get()->groupBy('module');
        return view('livewire.admin.roles.show-rol', compact('permisos'));
    }

    public function update()
    {
        // dd($this->selectedPermisos);
        $this->role->name = trim(mb_strtoupper($this->role->name, "UTF-8"));
        $this->validate();
        $this->role->save();
        $this->role->permissions()->sync($this->selectedPermisos);
        $this->dispatchBrowserEvent('updated');
        // return redirect()->route('admin.roles');
    }
}
