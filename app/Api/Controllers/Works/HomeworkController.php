<?php

namespace app\Api\Controllers\Works;

use App\Http\Controllers\Controller;
use Auth;
use App\Api\Resources\HomeworkResource;
use App\Api\Resources\HomeworkDetailResource;
use App\Models\Homework;
use App\Models\Student;

class HomeworkController extends Controller
{
    public function index()
    {
        // 获取登录用户的所有作业通知
        $notifications = Auth::user()->notifications()->where('type','App\Notifications\HomeworkNotice')->latest()->get();
        return api()->collection($notifications,HomeworkResource::class);
    }

    public function show(Student $student,Homework $homework)
    {
        $this->authorize('show',$student);
        $this->authorize('show',$homework);

        $homework->student = $student;

        return api()->item($homework, HomeworkDetailResource::class);
    }

    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function read()
    {
        $uuid = request()->get('uid');
        $user = Auth::user();

        $notification = $user->unreadNotifications()->where('id',$uuid)->first();
        if($notification) {
            $notification->markAsRead();

            Auth::user()->decrement('notification_count',1);
            Auth::user()->save();

            $homework = Homework::where('id',$notification->data['id'])->first();
            $students = $user->students()->where('class_id',$homework->collective->id)->wherePivot('contact',1)->first();

            if(count($students)>0)
            {
                $homework->read_sum += 1;
                $homework->save();
            }

            return response()->json(['status' => 1,'message'=> '成功']);
        }
        return response()->json(['status' => 0,'message' => '作业已读']);
    }
}