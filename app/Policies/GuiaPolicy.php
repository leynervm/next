<?php

namespace App\Policies;

use App\Models\Guia;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GuiaPolicy
{
    use HandlesAuthorization;

    public function sucursal(User $user, Guia $guia)
    {
        if (auth()->user()->sucursal_id == $guia->sucursal_id) {
            return true;
        } else {
            return false;
        }
    }
}
