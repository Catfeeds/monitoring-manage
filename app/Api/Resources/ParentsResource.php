<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParentsResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'name' => $this->name,
            'phone'=>$this->phone,
            'avatar'=>$this->avatar,
            'note'=>$this->pivot['role'],
        ];
    }
}