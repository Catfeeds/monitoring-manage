<?php

namespace App\Gardener\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use App\Models\Student;

class CollectiveNoticDetailResource extends JsonResource
{
    public function toArray($request)
    {
        $user_ids = DB::table('notifications')->where('data->id',$this->id)->whereNull('read_at')->pluck('notifiable_id')->toArray();
        $users = collect();
        $students = Student::whereIn('class_id',$this->collection_ids)->get();
        foreach($students as $student)
        {
            $user = $student->parents()->whereIn('parent_id',$user_ids)->wherePivot('contact',1)->first();
            if($user) {
                $users->push($user);
            }
        }
        return [
            'school_name' => $this->school->name,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'sum' => $this->sum_num,
            'read' => $this->sum_num - count($user_ids),
            'user_avatars' =>  UsersResource::collection($users),
        ];
    }
}