<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsResource extends JsonResource
{
	public function toArray($request)
	{
		return [
            'message_id' => $this->data['id'],
            'notification_id' =>$this->id,
            'title' => $this->data['title'],
            'school_name' => $this->data['school_name'],
            'refuse_name' => $this->data['refuse_name'],
            'created' => $this->data['created'],
            'content' => $this->data['content'],
            'read' => optional($this->read_at)->toDateTimeString(),
        ];
	}
}