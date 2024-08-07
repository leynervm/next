<?php

namespace App\Policies;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function visible(User $user, Producto $producto)
    {
        if ($producto->isVisible()) {
            return true;
        } else {
            return false;
        }
    }

    public function publicado(?User $user, Producto $producto)
    {
        if ($producto->isPublicado() && $producto->isVisible()) {
            return true;
        } else {
            return false;
        }
    }
}
