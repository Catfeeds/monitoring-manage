<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Homework;

class HomeworkResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'homework_id' => $this->data['id'],
            'notification_id' =>$this->id,
            'title' => $this->data['title'],
            'week' => $this->data['created'],
            'content' => $this->data['content'],
            'month' => $this->data['month'],
            'date' => $this->data['date'],
            'read' => optional($this->read_at)->toDateTimeString(),
        ];
    }
}