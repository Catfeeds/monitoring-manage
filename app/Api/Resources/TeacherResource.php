<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tel'=>$this->tel,
            'avatar'=>$this->avatar,
            'position'=>$this->position
        ];
    }
}