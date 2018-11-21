<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
	public function toArray($request)
	{
        return [
            'dates' => get_week(strtotime(date('Y-m-d'))),
            'content' => $this->content,
        ];
	}
}