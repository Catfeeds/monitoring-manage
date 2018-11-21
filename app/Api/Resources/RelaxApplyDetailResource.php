<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\RelaxApply;
use App\Http\Resources\ArticleImageResource;
class RelaxApplyDetailResource extends JsonResource
{

	public function toArray($request)
	{
        $this->load('covers');
        return [
            'id' => $this->id,
            'student_avatar' => $this->student->avatar,
            'student_name' => $this->student->name,
            'apply_time' => optional($this->created_at)->toDateTimeString(),
            'parent_name' => $this->parent->name,
            'date_num' => $this->date_num,
            'reason' => $this->reason,
            'begin_time' => $this->begin,
            'end_time' => $this->end,
            'status' => $this->status_text,
            'teacher_name' => $this->teacher->name,
            'type' => $this->type_text,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'confirm_at' => optional($this->confirm_at)->toDateTimeString(),
            'covers'=>ArticleImageResource::collection($this->covers),
        ];
	}
}