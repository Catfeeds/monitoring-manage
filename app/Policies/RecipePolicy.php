<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;
use App\Models\Recipe;
use App\Models\Teacher;
use App\Models\School;

class RecipePolicy
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
     * @param Recipe $recipe
     * @return bool
     */
    public function operation(Administrator $administrator,Recipe $recipe)
    {
        return $administrator->school_id === $recipe->school_id || $administrator->isAdmin();
    }

}
