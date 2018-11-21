<?php

namespace App\Http\Resources;

use App\Api\Resources\ArticleZanUserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentsResource;
use Illuminate\Support\Facades\Storage;

class FileResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'file_name' => pathinfo($this->file,PATHINFO_BASENAME),
            'created_at'=>date("m-d H:i",strtotime($this->created_at)),
            'file_path'=>Storage::disk('public')->url($this->file),
            'push_name'=>$this->admin->name,
            'type'=>$this->type
        ];
    }
}