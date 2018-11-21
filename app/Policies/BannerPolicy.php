<?php

namespace App\Policies;

use App\Models\Banner;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;

class BannerPolicy
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
    public function operation(Administrator $administrator,Banner $banner)
    {
        return $administrator->school_id === $banner->school_id || $administrator->isAdmin();
    }
}
