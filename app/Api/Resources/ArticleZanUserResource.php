<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleZanUserResource extends JsonResource
{
	public function toArray($request)
	{

		return [
		    'name'=>$this->type?$this->teacher->name:$this->user->name,
            'avatar'=>$this->type?$this->teacher->avatar:$this->user->avatar
        ];
	}
}