<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ArticleImageResource;
class RelaxAppliesResource extends JsonResource
{
	public function toArray($request)
	{

        return [
            'id' => $this->id,
            'parent_name' => $this->parent->name,
            'student_name' => $this->student->name,
            'student_avatar' => $this->student->avatar,
            'apply_time' => optional($this->created_at)->toDateTimeString(),
            'date_num' => $this->date_num,
            'status' => $this->status_text,
            'type' => $this->type,
        ];
	}
}