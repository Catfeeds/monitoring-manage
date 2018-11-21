<?php

namespace App\Http\Resources;

use App\Api\Resources\ArticleZanUserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentsResource;
class ArticlesResource extends JsonResource
{

    public function toArray($request)
    {
        $this->load('covers');
        $this->load('zans');
        $this->load('comments');
        return [
            'id' => $this->id,
            'content' => $this->content,
            'label' =>$this->label,
            'covers' => ArticleImageResource::collection($this->covers),
            'user_id' =>$this->type?$this->teacher->id:$this->user->id,
            'username' =>$this->type?$this->teacher->name:$this->user->name,
            'avatar'=>$this->type?$this->teacher->avatar:$this->user->avatar,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'zan_users' => ArticleZanUserResource::collection($this->zans),
            'is_zan'=>$this->zan?1:0,
            'movie'=>$this->movie,
            'zan_num' =>$this->zans->count(),
            'comment_num'=>$this->comments->count(),
            'comments' =>CommentsResource::collection($this->comments),
            'first_movie'=>$this->first_movie,
        ];
    }
}