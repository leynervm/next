<?php

namespace App\Http\Livewire\Admin\Roles;

use App\Models\Permission;
use App\Rules\CampoUnique;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateRol extends Component
{

    public $open = false;
    public $name;
    public $selectedPermisos = [];

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'min:2', new CampoUnique('roles', 'name', null, false)],
            'selectedPermisos' => ['required', 'array', 'min:1']
        ];
    }

    public function render()
    {
        $permisos = Permission::modulesActivePermission()->orderBy('orden', 'asc')
            ->orderBy('table', 'asc')->orderBy('id', 'asc')->get()->groupBy('module');
        return view('livewire.admin.roles.create-rol', compact('permisos'));
    }

    public function save()
    {
        $this->name = trim(mb_strtoupper($this->name, "UTF-8"));
        $this->validate();
        $rol = Role::create([
            'name' => $this->name,
        ]);
        $rol->permissions()->sync($this->selectedPermisos);
        $this->dispatchBrowserEvent('created');
        return redirect()->route('admin.roles');
    }
}
