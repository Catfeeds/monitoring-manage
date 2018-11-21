<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;
use App\Models\Navigation;

class NavigationPolicy
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
     * @param Navigation $navigation
     * @return bool
     */
    public function operation(Administrator $administrator,Navigation $navigation)
    {
        return $administrator->school_id === $navigation->school_id || $administrator->isAdmin();
    }
}
