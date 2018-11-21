<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CameraResource extends JsonResource
{
	public function toArray($request)
	{
		return [
		    'uid' => $this->uid,
            'name' => $this->area,
            'class_name' => $this->collective->grade->name.' '.$this->collective->name,
        ];
	}
}