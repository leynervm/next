<?php

namespace App\Policies;

use App\Models\Opencaja;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OpencajaPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Opencaja $opencaja)
    {
        return $user->id === $opencaja->user_id;
    }
}
