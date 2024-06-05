<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function desarrollador(User $user, User $usereditar)
    {
        if ($usereditar->isAdmin()) {
            if (auth()->user()->isAdmin()) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function dashboard(User $user, User $usereditar)
    {
        if ($usereditar->isDashboard() || $usereditar->isAdmin()) {
            return true;
        } else {
            return false;
        }
    }
}
