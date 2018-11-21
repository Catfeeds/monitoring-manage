<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class CollectivesResource extends JsonResource
{
	public function toArray($request)
	{
		return [
		    'id' =>$this->id,
            'grade_id' => $this->grade->id,
            'name' => $this->grade->name.$this->name,
            'grade_name' => $this->grade->name,
            'class_name' => $this->name,
            'school_name' =>$this->school->name,
            'school_id' =>$this->school->id,
            'school_avatar' => $this->school->avatar
        ];
	}

}