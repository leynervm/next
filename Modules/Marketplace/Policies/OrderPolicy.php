<?php

namespace Modules\Marketplace\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Marketplace\Entities\Order;

class OrderPolicy
{
    use HandlesAuthorization;

    public function user(User $user, Order $order)
    {
        if (auth()->user()->id == $order->user_id) {
            return true;
        } else {
            return false;
        }
    }

    public function carshoop(User $user)
    {
        // if (Cart::instance('shopping')->count() > 0) {
        //     return true;
        // } else {
            return false;
        // }
    }
}
