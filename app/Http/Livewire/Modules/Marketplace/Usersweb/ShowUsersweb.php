<?php

namespace App\Http\Livewire\Modules\Marketplace\Usersweb;

use App\Models\User;
use App\Rules\CampoUnique;
use App\Rules\ValidateDocument;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\Jetstream;
use Livewire\Component;
use Livewire\WithPagination;

class ShowUsersweb extends Component
{

    use WithPagination, AuthorizesRequests;

    public $open = false;
    public $emai, $password, $terms;
    public $search = '';

    protected $listeners = ['render'];

    protected $queryString = [
        'search' => [
            'except' => '',
            'as' => 'buscar'
        ]
    ];

    public function render()
    {
        $users = User::withTrashed()->where('admin', '0');

        if (trim($this->search) != '') {
            $users->where('document', 'ilike', '%' . $this->search . '%')
                ->orWhere('name', 'ilike', '%' . $this->search . '%')
                ->orWhere('email', 'ilike', '%' . $this->search . '%');
        }

        $users = $users->web()->orderBy('name', 'asc')->paginate();

        return view('livewire.modules.marketplace.usersweb.show-usersweb', compact('users'));
    }

    public function delete(User $user)
    {
        $this->authorize('admin.marketplace.userweb.delete');
        $user->delete();
        $this->dispatchBrowserEvent('toast', toastJSON('Usuario web desactivado correctamente'));
    }

    public function restoreuser($user_id)
    {

        DB::beginTransaction();
        try {
            $user = User::withTrashed()->find($user_id);

            if ($user) {
                $emails = User::withTrashed()->where('email', $user->email)->where('id', '<>', $user->id);

                if ($emails->exists()) {
                    $mensaje = response()->json([
                        'title' => 'YA EXISTE UN REGISTRO CON EL MISMO CORREO !',
                        'text' => "Existen registros de usuarios con el mismo correo en la base de datos.",
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                $user->restore();
                DB::commit();
                $this->resetValidation();
                $this->dispatchBrowserEvent('toast', toastJSON('usuario web activado correctamente'));
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
