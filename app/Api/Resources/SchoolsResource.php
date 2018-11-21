<?php

namespace App\Api\Resources;

use App\Http\Resources\CoverResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolsResource extends JsonResource
{
	public function toArray($request)
	{
		return [
		    'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'phone'=>$this->phone,
            'address'=>$this->address,
            'describe'=>$this->describe,
            'detail_info'=>$this->detail_info,
            'banners'=> $this->covers()->limit(3)->get(['path'])->toArray(),
            'sence_school'=>CoverResource::collection($this->covers)
        ];
	}
}