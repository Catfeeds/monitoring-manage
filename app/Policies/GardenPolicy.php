<?php

namespace App\Policies;

use App\Models\Gardenpay;
use App\Models\Space;
use App\User;
use App\Models\About;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;

class GardenPolicy
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
}
