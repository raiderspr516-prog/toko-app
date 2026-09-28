<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Customer hanya boleh melihat/mengelola order miliknya sendiri.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }

    public function cancel(User $user, Order $order): bool
    {
        return $user->id === $order->user_id && $order->isCancellableByCustomer();
    }
}
