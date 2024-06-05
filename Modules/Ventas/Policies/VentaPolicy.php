<?php

namespace Modules\Ventas\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Ventas\Entities\Venta;

class VentaPolicy
{
    use HandlesAuthorization;

    public function sucursal(User $user, Venta $venta)
    {
        if (auth()->user()->sucursal_id == $venta->sucursal_id) {
            return true;
        } else {
            return false;
        }
    }
}
