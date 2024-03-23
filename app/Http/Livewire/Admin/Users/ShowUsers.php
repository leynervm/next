<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowUsers extends Component
{

    use WithPagination;

    public $open = false;
    public $search = '';
    public $searchsucursal = '';

    public function render()
    {
        $users = User::with(['employer'])->with(['sucursal' => function ($query) {
            $query->withTrashed();
        }]);
        // ->whereNotNull('sucursal_id');

        if (trim($this->search) != '') {
            $users->where('document', 'ilike', '%' . $this->search . '%')
                ->orWhere('name', 'ilike', '%' . $this->search . '%');
        }

        if (trim($this->searchsucursal) != '') {
            $users->where('sucursal_id', $this->searchsucursal);
        }

        $sucursals = Sucursal::whereHas('users')->orderBy('name', 'asc')->get();
        $users = $users->orderBy('name', 'asc')->paginate();
        return view('livewire.admin.users.show-users', compact('users', 'sucursals'));
    }

    public function delete(User $user)
    {

        DB::beginTransaction();
        try {
            if ($user->employer) {
                if ($user->openboxes()->mybox($user->employer->sucursal_id)->exists()) {
                    $mensaje = response()->json([
                        'title' => 'USUARIO CUENTA CON UNA CAJA ACTIVA EN USO !',
                        'text' => 'Usuario seleccionado cuenta con caja activa en uso, primero debe cerrar la caja activa.',
                        'type' => 'warning'
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                $user->employer->user()->dissociate();
                $user->employer->save();
            }

            $user->status = 1;
            $user->sucursal_id = null;
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
}
