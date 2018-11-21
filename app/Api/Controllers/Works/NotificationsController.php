<?php

namespace app\Api\Controllers\Works;

use App\Http\Controllers\Controller;
use App\Api\Resources\NotificationsResource;
use App\Api\Resources\NotificationDetailResource;
use App\Models\MessageNotic;
use App\Models\Student;
use Auth;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Tanmo\Api\Http\Response
     */
	public function index()
    {
        // 获取登录用户的所有通知
        $notifications = Auth::user()->notifications()->where('type','App\Notifications\CollectiveNotic')->latest()->get();
        return api()->collection($notifications,NotificationsResource::class);
    }

    /**
     * @param MessageNotic $messageNotic
     * @return \Illuminate\Http\JsonResponse|\Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Student $student,MessageNotic $messageNotic)
    {
        $this->authorize('show',$student);
        $this->authorize('show',$messageNotic);

        $messageNotic->student = $student;

        return api()->item($messageNotic, NotificationDetailResource::class);
    }

    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function read()
    {
        $uuid = request()->get('id');
        $user = Auth::user();
        $notification = Auth::user()->unreadNotifications()->where('id',$uuid)->first();
        $messageNotic = MessageNotic::where('id',$notification->data['id'])->first();
        if($notification) {
            $notification->markAsRead();

            Auth::user()->decrement('notification_count',1);
            Auth::user()->save();

            $students = $user->students()->whereIn('class_id',$messageNotic->collection_ids)->wherePivot('contact',1)->first();
            if(count($students)>0)
            {
                $messageNotic->read_sum += 1;
                $messageNotic->save();
            }
        }
        return api()->noContent();
    }
}