<?php

namespace App\Gardener\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Collective;
use App\Gardener\Resources\MessagesResource;
use App\Gardener\Resources\MessageDetailResource;

class MessageController extends Controller
{
    /**
     * @param Collective $collective
     * @return \Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function index(Collective $collective)
    {
        $this->authorize('checkGrad',$collective);
        $teacher = auth()->user();
        $status = request()->get('status');
        if ($status || $status == '0') {
            $messages = (new Message())->where([['teacher_id',$teacher->id],['is_read',$status]])->latest()->get();
        }
        else {
            $messages = (new Message())->where('teacher_id',$teacher->id)->latest()->get();
        }

        return api()->collection($messages, MessagesResource::class);
    }

    /**
     * @param Message $message
     * @return \Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Message $message)
    {
        $this->authorize('showGrad',$message);

        return api()->item($message, MessageDetailResource::class);
    }

    /**
     * @param Message $message
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function read(Message $message)
    {
        $this->authorize('showGrad',$message);

        if($message->is_read != 0) {
            return response()->json(['status' => 0, 'message' => '错误的操作！']);
        }
        $message->is_read = 1;
        $message->save();

        return response()->json(['status' => 1, 'message' => '已设置为已读']);
    }
}