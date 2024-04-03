<?php

namespace App\Http\Livewire\Admin\Permisos;

use App\Models\Permission;
use App\Rules\CampoUnique;
use Livewire\Component;
use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Role;

class ShowPermisos extends Component
{

    public $permission;
    public $open = false;

    protected function rules()
    {
        return [
            'permission.descripcion' => ['required', 'string', 'min:3', new CampoUnique('permissions', 'descripcion', $this->permission->id, false)],
        ];
    }

    public function mount()
    {
        $this->permission = new Permission();
    }

    public function render()
    {
        $permisos = Permission::modulesActivePermission()->orderBy('orden', 'asc')
            ->orderBy('table', 'asc')->orderBy('id', 'asc')->get()->groupBy('module');
        return view('livewire.admin.permisos.show-permisos', compact('permisos'));
    }

    public function edit(Permission $permission)
    {
        $this->permission = $permission;
        $this->resetValidation();
        $this->open = true;
    }

    public function update()
    {
        $this->permission->descripcion = trim($this->permission->descripcion);
        $this->validate();
        $this->permission->save();
        $this->dispatchBrowserEvent('updated');
        $this->open = false;
    }
}
