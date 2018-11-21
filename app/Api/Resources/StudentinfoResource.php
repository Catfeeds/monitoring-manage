<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class StudentinfoResource extends JsonResource
{
	public function toArray($request)
	{
		return [
            'name' => $this->name,
            'class'=>$this->grade->name.$this->collective->name,
            'birthday' =>date("Y-m-d",strtotime($this->birthday)),
            'sex'=>$this->sex,
            'avatar'=>$this->avatar,
            'age'=>babyAge($this->birthday),
            'parent'=>ParentsResource::collection($this->parents),
        ];
	}

}