<?php

namespace app\Gardener\Controllers;

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
        $this->authorize('checkGrad',$collective);
        //$week = get_week(strtotime(date('Y-m-d')));
        $course = Course::where('class_id',$collective->id)->first();
        return api()->item($course,CourseResource::class);
    }

    /**
     * @param Course $course
     * @return \Tanmo\Api\Http\Response|void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Course $course)
    {
        $this->authorize('checkGrad',$course);

        $content = request()->get('content');
        $infos = json_decode($content, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            response()->json(['status' => 0,'message' => '数据格式错误！']);
        }

        $course->content = $infos;
        $course->save();

        return response()->json(['status' => 1,'message' => '修改成功！']);
    }
}