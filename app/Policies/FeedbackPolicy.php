<?php

namespace App\Policies;

use Tanmo\Admin\Models\Administrator;
use App\Models\About;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Feedback;
class FeedbackPolicy
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
     * @param About $about
     * @return bool
     */
    public function operation(Administrator $administrator,Feedback $feedback)
    {
        return $administrator->school_id === $feedback->school_id || $administrator->isAdmin();
    }
}
