<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PayConfig;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;

class PayConfigPolicy
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
     * @param PayConfig $payConfig
     * @return bool
     */
    public function operation(Administrator $administrator,PayConfig $payConfig)
    {
        return $administrator->school_id === $payConfig->school_id || $administrator->isAdmin();
    }

}
