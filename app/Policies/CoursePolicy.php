<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;
use App\Models\Course;
use App\Models\Teacher;

class CoursePolicy
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
     * @param Course $course
     * @return bool
     */
    public function operation(Administrator $administrator,Course $course)
    {
        return $administrator->school_id === $course->collective->school_id || $administrator->isAdmin();
    }

    /**
     * @param Teacher $teacher
     * @param Course $course
     * @return bool
     */
    public function checkGrad(Teacher $teacher,Course $course) {
        $ids = $teacher->collective()->pluck('class_id')->toArray();
        return in_array($course->class_id,$ids);
    }
}
