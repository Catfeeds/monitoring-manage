<?php

namespace App\Policies;

use App\Models\RelaxApply;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;

class RelaxApplyPolicy
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

    public function operation(Administrator $administrator,RelaxApply $relaxApply)
    {
        return ($administrator->school_id === $relaxApply->school_id && ($administrator->isRole('school') || $administrator->id === $relaxApply->teacher->admin_id)) || $administrator->isAdmin();
    }

    /**
     * @param User $user
     * @param RelaxApply $relaxApply
     * @return bool
     */
    public function show(User $user,RelaxApply $relaxApply)
    {
        return $relaxApply->user_id === $user->id;
    }

    public function showGrad(Teacher $teacher,RelaxApply $relaxApply)
    {
        return $relaxApply->teacher_id === $teacher->id;
    }

    public function cancel(User $user,RelaxApply $relaxApply)
    {
        return $relaxApply->user_id === $user->id;
    }
}
