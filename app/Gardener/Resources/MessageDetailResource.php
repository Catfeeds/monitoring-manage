<?php

namespace App\Gardener\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ArticleImageResource;

class MessageDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'parent_name' => $this->parent->name,
            'avatar' => $this->parent->avatar,
            'content' => $this->content,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'covers'=>ArticleImageResource::collection($this->covers),
        ];
    }
}