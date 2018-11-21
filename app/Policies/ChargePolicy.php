<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\Charge;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;

class ChargePolicy
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
     * @param Banner $banner
     * @return bool
     */
    public function operation(Administrator $administrator,Charge $charge)
    {
        return $administrator->school_id === $charge->school_id || $administrator->isAdmin();
    }
}
