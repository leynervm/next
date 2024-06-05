<?php

namespace Modules\Almacen\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Almacen\Entities\Compra;

class CompraPolicy
{
    use HandlesAuthorization;

    public function sucursal(User $user, Compra $compra)
    {
        if (auth()->user()->sucursal_id == $compra->sucursal_id) {
            return true;
        } else {
            return false;
        }
    }
}
