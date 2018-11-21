<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;

class StudentPolicy
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
    public function operation(Administrator $administrator,Student $student)
    {
        if($administrator->role) {
            $teacher = Teacher::where('admin_id',$administrator->id)->first();
            $classe = $teacher->collective;
            $ids=array();
            foreach ($classe as $k=>$v){
                $ids[$k]=$v->id;
            }
            return $administrator->school_id === $student->school_id && in_array($student->class_id,$ids);
        }
        return $administrator->school_id === $student->school_id || $administrator->isAdmin();
    }

    /**
     * @param User $user
     * @param Student $student
     * @return bool
     */
    public function show(User $user,Student $student)
    {
        foreach ($user->students as $v) {
            $ids[] = $v->id;
        }

        return in_array($student->id,$ids);
    }
}
