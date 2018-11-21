<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\Camera;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;

class CameraPolicy
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
    public function operation(Administrator $administrator,Camera $camera)
    {
        return $administrator->school_id === $camera->school_id || $administrator->isAdmin();
    }
}
