<?php

namespace app\Api\Controllers\Works;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Collective;
use App\Api\Resources\CourseResource;

class CourseController extends Controller
{
    /**
     * @param Collective $collective
     * @return \Illuminate\Http\JsonResponse|\Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Collective $collective)
    {
        $this->authorize('check',$collective);
//        $date = request()->get('date');
//        $date=date("Y-m-d",strtotime("$date last Monday"));
//        if(!$date) {
//            $date = date("Y-m-d",strtotime("last Monday"));
//        }
//        $course = Course::where([['class_id',$collective->id],['begin_start',$date]])->first();
//        if($course) {
//            return api()->item($course,CourseResource::class);
//        }
//        else {
//            return response()->json(['status'=>0,'messgae' => '该周课程尚未添加!']);
//        }
        //$week = get_week(strtotime(date('Y-m-d')));
        $course = Course::where('class_id',$collective->id)->first();
        return api()->item($course,CourseResource::class);
    }
}