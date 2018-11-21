<?php

namespace App\Policies;

use App\Models\User;
use App\Models\About;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;
use App\Models\Article;
class ArticlePolicy
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
    public function delete(User $user,Article $article)
    {
        return $user->id == $article->user_id;
    }
}
