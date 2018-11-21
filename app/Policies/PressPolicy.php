<?php

namespace App\Policies;

use App\Models\Classify;
use App\Models\Press;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;

class PressPolicy
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
    public function operation(Administrator $administrator,Press $press)
    {
        return $administrator->school_id === $press->classify->school_id || $administrator->isAdmin();
    }
}
