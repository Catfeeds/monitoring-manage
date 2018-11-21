<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class StudentlistResource extends JsonResource
{
	public function toArray($request)
	{

		return [
            'id' => $this->id,
            'avatar' =>$this->avatar,
            'name' =>$this->name,
        ];
	}

}