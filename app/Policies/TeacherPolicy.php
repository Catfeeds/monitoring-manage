<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\Teacher;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;

class TeacherPolicy
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
    public function operation(Administrator $administrator,Teacher $teacher)
    {
        return $administrator->school_id === $teacher->school_id || $administrator->isAdmin();
    }
}
