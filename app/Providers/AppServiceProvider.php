<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Models\School::observe(\App\Observers\SchoolObserver::class);
        \App\Models\MessageNotic::observe(\App\Observers\MessageNoticeObserver::class);
        \App\Models\Collective::observe(\App\Observers\CollectiveObserver::class);
        \App\Models\Homework::observe(\App\Observers\HomeworkObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
