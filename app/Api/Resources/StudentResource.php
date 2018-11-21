<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class StudentResource extends JsonResource
{
	public function toArray($request)
	{

		return [
            'note' => $this->name.$this->pivot->role,
        ];
	}

}