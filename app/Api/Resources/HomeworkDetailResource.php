<?php

namespace app\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Student;

class HomeworkDetailResource extends JsonResource
{
	public function toArray($request)
	{
        $arr = array();
        foreach($this->student->parents as $parent) {
            $notification = $parent->notifications()->where('data->id',$this->id)->whereNotNull('read_at')->first();
            if(!empty($notification->read_at)) {
                $arr[] = ['role' => $parent->pivot->role,'read_at' => date('Y-m-d H:i',strtotime($notification->read_at->toDateTimeString()))];
            }
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => date('Y-m-d H:i',strtotime($this->created_at->toDateTimeString())),
            'end_at' => date('Y-m-d H:i',strtotime($this->end_at)),
            'parents' => $arr,
        ];
	}
}