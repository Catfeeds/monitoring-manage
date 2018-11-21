<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FamilyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'role' => $this->pivot->role,
            'contact' => $this->pivot->contact,
        ];
    }
}