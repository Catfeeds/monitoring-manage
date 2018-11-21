<?php

namespace App\Observers;

use App\Models\School;
use App\Models\About;
use App\Models\PayConfig;
use App\Models\Recipe;

class SchoolObserver
{
    public function created(School $school)
    {
        $payConfig = new PayConfig();
        $payConfig->school_id = $school->id;
        $payConfig->save();

        $recipe = new Recipe();
        $recipe->school_id = $school->id;
        $week = get_week(strtotime(date('Y-m-d')));
        $recipe->begin_start = $week[0]['date'];
        $arr = [];
        $labels = [];
        $tags = [
            'one' => '早餐',
            'two' => '早餐加餐',
            'three' => '午餐',
            'four' => '午餐加餐',
            'five' => '晚餐',
            'six' => '',
            'seven' => '',
        ];
        $dates = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($dates as $k => $date) {
            foreach ($tags as $k1 => $v) {
                if ($date == 'monday') {
                    $labels[$k1] = $v;
                }
                $arr[$date][$k1] = '';
            }
        }
        $recipe->tags = $labels;
        $recipe->content = $arr;
        $recipe->save();
    }
}
