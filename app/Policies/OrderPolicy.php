<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\About;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;

class OrderPolicy
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

    /**
     * @param Administrator $administrator
     * @param About $about
     * @return bool
     */
    public function operation(Administrator $administrator,Order $order)
    {
        return $administrator->school_id === $order->school_id || $administrator->isAdmin();
    }
}
