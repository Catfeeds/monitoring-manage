<?php

namespace App\Policies;

use App\Models\Classify;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;

class ClassifyPolicy
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
    public function operation(Administrator $administrator,Classify $classify)
    {
        return $administrator->school_id === $classify->school_id || $administrator->isAdmin();
    }
}
