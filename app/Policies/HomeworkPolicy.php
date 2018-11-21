<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;
use App\Models\Homework;
use App\Models\Teacher;

class HomeworkPolicy
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


    public function show(User $user,Homework $homework)
    {
        $notifications = $user->notifications()->where('type','App\Notifications\HomeworkNotice')->get();
        foreach($notifications as $notification)
        {
            $ids[] = $notification->data['id'];
        }

        return in_array($homework->id,$ids);
    }

    /**
     * @param Teacher $teacher
     * @param Homework $homework
     * @return bool
     */
    public function showGrad(Teacher $teacher,Homework $homework)
    {
        return $homework->admin_id == $teacher->admin_id;
    }

}
