<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
	public function toArray($request)
	{
        return [
            'parent_name' => $this->parent->name,
            'avatar' => $this->avatar,
            'is_read' => $this->is_read,
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
	}
}