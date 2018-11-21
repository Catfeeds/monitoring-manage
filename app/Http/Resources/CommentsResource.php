<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentsResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'username' =>$this->type?$this->teacher->name:$this->user->name,
            'avatar'=>$this->type?$this->teacher->name:$this->user->avatar,
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
    }
}