<?php

namespace App\Gardener\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessagesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'parent_name' => $this->parent->name,
            'avatar' => $this->parent->avatar,
            'is_read' => $this->is_read,
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
    }
}