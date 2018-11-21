<?php

namespace App\Policies;

use App\Models\TeacherApply;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;

class TeacherApplyPolicy
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

    public function operation(Administrator $administrator,TeacherApply $relaxApply)
    {
        return ($administrator->school_id === $relaxApply->school_id && ($administrator->isRole('school'))) || $administrator->isAdmin();
    }

}
