<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\School;
use App\Models\Teacher;

class SchoolPolicy
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

    public function check(User $user,School $school) {
        $ids = $user->schools()->pluck('school_id')->toArray();
        return in_array($school->id,$ids);
    }

    public function show(Teacher $teacher,School $school) {
        return $school->id === $teacher->school_id;
    }
}
