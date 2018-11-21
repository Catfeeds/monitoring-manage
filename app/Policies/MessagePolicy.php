<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;
use App\Models\Message;
use App\Models\Teacher;

class MessagePolicy
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
     * @param Message $message
     * @return bool
     */
    public function delete(Administrator $administrator,Message $message)
    {
        return ($administrator->school_id === $message->school_id && ($administrator->isRole('school') || $administrator->id === $message->teacher->admin_id)) || $administrator->isAdmin();
    }

    /**
     * @param Teacher $teacher
     * @param Message $message
     * @return bool
     */
    public function showGrad(Teacher $teacher,Message $message)
    {
        return $message->teacher_id === $teacher->id;
    }
}
