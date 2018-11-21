<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        \App\Models\Banner::class => \App\Policies\BannerPolicy::class,
        \App\Models\Navigation::class => \App\Policies\NavigationPolicy::class,
        \App\Models\Grade::class => \App\Policies\GradePolicy::class,
        \App\Models\Collective::class => \App\Policies\CollectivePolicy::class,
        \App\Models\About::class => \App\Policies\AboutPolicy::class,
        \App\Models\Message::class => \App\Policies\MessagePolicy::class,
        \App\Models\RelaxApply::class => \App\Policies\RelaxApplyPolicy::class,
        \App\Models\TeacherApply::class => \App\Policies\TeacherApplyPolicy::class,
        \App\Models\Course::class => \App\Policies\CoursePolicy::class,

        \App\Models\Teacher::class => \App\Policies\TeacherPolicy::class,
        \App\Models\Student::class => \App\Policies\StudentPolicy::class,
        \App\Models\Camera::class => \App\Policies\CameraPolicy::class,
        \App\Models\Charge::class => \App\Policies\ChargePolicy::class,

        \App\Models\MessageNotic::class => \App\Policies\NoticPolicy::class,
        \App\Models\PayConfig::class => \App\Policies\PayConfigPolicy::class,
        \App\Models\Article::class => \App\Policies\ArticlePolicy::class,
        \App\Models\Feedback::class => \App\Policies\FeedbackPolicy::class,
        \App\Models\Order::class => \App\Policies\OrderPolicy::class,

        \App\Models\Recipe::class => \App\Policies\RecipePolicy::class,
        \App\Models\School::class => \App\Policies\SchoolPolicy::class,

        \App\Models\Classify::class => \App\Policies\ClassifyPolicy::class,
        \App\Models\Press::class => \App\Policies\PressPolicy::class,
        \App\Models\Space::class => \App\Policies\SpacePolicy::class,
        \App\Models\Homework::class => \App\Policies\HomeworkPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


    }
}
