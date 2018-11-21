<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;
use App\Models\MessageNotic;
use App\Models\Teacher;

class NoticPolicy
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
     * @param MessageNotic $notic
     * @return bool
     */
    public function operation(Administrator $administrator,MessageNotic $notic)
    {
        return $administrator->school_id === $notic->school_id || $administrator->isAdmin();
    }

    /**
     * @param User $user
     * @param MessageNotic $messageNotic
     * @return bool
     */
    public function show(User $user,MessageNotic $messageNotic)
    {
        foreach($user->notifications()->get() as $notification)
        {
            $ids[] = $notification->data['id'];
        }

        return in_array($messageNotic->id,$ids);
    }

    /**
     * @param Teacher $teacher
     * @param MessageNotic $messageNotic
     * @return bool
     */
    public function showGrad(Teacher $teacher,MessageNotic $messageNotic)
    {
        return $messageNotic->admin_id == $teacher->admin_id;
    }
}
