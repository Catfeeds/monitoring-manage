<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class CollectiveResource extends JsonResource
{
	public function toArray($request)
	{
		return [
		    'id' =>$this->id,
           'grade' => $this->grade->name,
            'class'=>$this->name,
            'school_info' =>$this->school,
            'user_name' =>auth('teacher')->user()->name,
            'user_sex' =>auth('teacher')->user()->sex,
            'user_avatar' =>auth('teacher')->user()->avatar,
            'user_note' =>auth('teacher')->user()->note,
        ];
	}

}