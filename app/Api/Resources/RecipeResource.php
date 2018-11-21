<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
	public function toArray($request)
	{
		return [
		    'dates' => get_week(strtotime($this->begin_start)),
            'tags' => $this->tags,
            'content' => $this->content,
        ];
	}
}