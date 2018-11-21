<?php

namespace App\Http\Resources;

use App\Api\Resources\ArticleZanUserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentsResource;
class PressDetailResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title'=>$this->title,
            'banner'=>$this->banner,
            'content'=>$this->content,
            'look_count'=>$this->look_count,
            'created_at'=>date('Y-m-d H:i:s',strtotime($this->created_at)),
            'avatar'=>$this->user->avatar,
            'name'=>$this->user->name
        ];
    }
}