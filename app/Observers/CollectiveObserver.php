<?php

namespace App\Observers;
use App\Models\Course;
use App\Models\Collective;

class CollectiveObserver
{
    public function created(Collective $collective)
    {
       $course = new Course();
       $week = get_week(strtotime(date('Y-m-d')));
       $course->begin_start = $week[0]['date'];
       $arr = ['C', 'D', 'E', 'F', 'G', 'H', 'I'];
       $content = [];
       foreach ($arr as $v) {
           $content['up'][$v] = '';
           $content['down'][$v] = '';
       }
       $course->content = $content;
       $course->class_id = $collective->id;
       $course->save();
    }
}
