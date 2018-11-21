<?php

namespace App\Gardener\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class HomeworkDetailResource extends JsonResource
{
    public function toArray($request)
    {
        $user_ids = DB::table('notifications')->where('data->id',$this->id)->whereNull('read_at')->pluck('notifiable_id')->toArray();
        $users = collect();
        foreach($this->collective->students as $student)
        {
            $user = $student->parents()->whereIn('parent_id',$user_ids)->wherePivot('contact',1)->first();
            if($user) {
                $users->push($user);
            }
        }
        return [
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->created_at->toDateTimeString(),
            'end_at' => $this->end_at,
            'sum' => $this->spend_sum,
            'read' => $this->read_sum,
            'user_avatars' =>  UsersResource::collection($users),
        ];
    }
}