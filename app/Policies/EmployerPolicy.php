<?php

namespace App\Policies;

use App\Models\Employer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployerPolicy
{
    use HandlesAuthorization;

    public function sucursal(User $user, Employer $employer)
    {
        if ($user->sucursal_id == $employer->sucursal_id) {
            return true;
        } else {
            return false;
        }
    }
}
