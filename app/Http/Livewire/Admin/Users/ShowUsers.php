<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowUsers extends Component
{

    use WithPagination;
    use AuthorizesRequests;

    public $open = false;
    public $search = '';
    public $searchsucursal = '';

    protected $queryString = [
        'searchsucursal' => [
            'except' => '',
            'as' => 'sucursal'
        ]
    ];

    public function render()
    {
        $users = User::whereNotNull('sucursal_id')->withTrashed();

        if (!auth()->user()->isAdmin()) {
            $users->where('admin', '0');
        }

        if (trim($this->searchsucursal) != '') {
            $users->where('sucursal_id', $this->searchsucursal);
        } else {
            // $users->where('sucursal_id', auth()->user()->sucursal_id);
        }

        if (trim($this->search) != '') {
            $users->where('document', 'ilike', '%' . $this->search . '%')
                ->orWhere('name', 'ilike', '%' . $this->search . '%');
        }

        $users = $users->orderBy('name', 'asc')->paginate();
        $sucursals = Sucursal::whereHas('users')->orderBy('name', 'asc')->get();

        return view('livewire.admin.users.show-users', compact('users', 'sucursals'));
    }

    public function delete(User $user)
    {
        $this->authorize('admin.users.delete');
        DB::beginTransaction();
        try {
            if ($user->openboxes()->open()->exists()) {
                $mensaje = response()->json([
                    'title' => 'USUARIO TIENE CAJAS ACTIVAS EN USO !',
                    'text' => "Existen registros de cajas diarias vinculados al usuario en sucursal asignado, primero debe cerrar las cajas aperturadas.",
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            if ($user->employer) {
                $user->employer->user()->dissociate();
                $user->employer->save();
            }

            $user->status = 1;
            $user->sucursal_id = null;
            $user->save();
            $user->roles()->detach();
            $user->permissions()->detach();
            $user->delete();
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function restoreuser($user_id)
    {
        $this->authorize('admin.users.restore');
        DB::beginTransaction();
        try {
            $user = User::withTrashed()->find($user_id);

            if ($user) {
                $emails = User::withTrashed()->where('email', $user->email)->where('id', '<>', $user->id);

                if ($emails->exists()) {
                    $mensaje = response()->json([
                        'title' => 'Ya existe un usuario con el mismo email !',
                        'text' => "Existen registros de usuario con el mismo correo en la base de datos.",
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                $user->restore();
                DB::commit();
                $this->resetValidation();
                $this->dispatchBrowserEvent('toast', toastJSON('usuario habilitado correctamente'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
