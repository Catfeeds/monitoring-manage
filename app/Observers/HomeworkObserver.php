<?php

namespace App\Observers;
use App\Notifications\HomeworkNotice;
use App\Models\HomeWork;

class HomeworkObserver
{
    public function created(Homework $homework)
    {
        // 通知用户有新的作业
        foreach ($homework->collective->parents as $user) {
            $user->notify(new HomeworkNotice($homework));
        }
        $count = $homework->collective->students->count();
        $homework->spend_sum = $count;
        $homework->save();
    }
}
