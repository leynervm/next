<?php

namespace Modules\Facturacion\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Facturacion\Entities\Comprobante;

class ComprobantePolicy
{
    use HandlesAuthorization;

    public function sucursal(User $user, Comprobante $comprobante)
    {
        if (auth()->user()->sucursal_id == $comprobante->sucursal_id) {
            return true;
        } else {
            return false;
        }
    }
}
