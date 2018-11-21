<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationDetailResource extends JsonResource
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
            'school_name' => $this->school->name,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'parents' => $arr
        ];
	}
}