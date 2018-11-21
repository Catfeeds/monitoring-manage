<?php

namespace App\Policies;

use App\User;
use App\Models\About;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;

class AboutPolicy
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
    public function operation(Administrator $administrator,About $about)
    {
        return $administrator->school_id === $about->school_id || $administrator->isAdmin();
    }
}
