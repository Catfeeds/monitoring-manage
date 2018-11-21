<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannersResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'link_type' => $this->link_type,
            'link' => $this->link,
            'cover' => $this->cover
        ];
    }
}