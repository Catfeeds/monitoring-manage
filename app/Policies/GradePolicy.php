<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;
use App\Models\Grade;

class GradePolicy
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
     * @param Grade $grade
     * @return bool
     */
    public function operation(Administrator $administrator,Grade $grade)
    {
        return $administrator->school_id === $grade->school_id || $administrator->isAdmin();
    }
}
