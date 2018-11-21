<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;
use App\Models\Collective;
use App\Models\Teacher;

class CollectivePolicy
{
    use HandlesAuthorization;

    /**
     * @param Administrator $administrator
     * @param Collective $collective
     * @return bool
     */
    public function operation(Administrator $administrator,Collective $collective)
    {
        if($administrator->role) {
            $teacher = Teacher::where('admin_id',$administrator->id)->first();
            $classes = $teacher->collective;
            $ids = array();
            foreach ($classes as $k=>$v){
                $ids[$k] = $v->id;
            }
            return $administrator->school_id === $collective->school_id && in_array($collective->id,$ids);
        }

        return $administrator->school_id === $collective->school_id || $administrator->isAdmin();
    }

    /**
     * @param User $user
     * @param Collective $collective
     * @return bool
     */
    public function check(User $user,Collective $collective) {
        $ids = $user->collectives()->pluck('class_id')->toArray();
        return in_array($collective->id,$ids);
    }

    public function checkGrad(Teacher $teacher,Collective $collective) {
        $ids = $teacher->collective()->pluck('class_id')->toArray();
        return in_array($collective->id,$ids);
    }
}
