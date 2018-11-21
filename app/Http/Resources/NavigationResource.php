<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NavigationResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'icon' => $this->icon,
            'redirect' => $this->redirect,

            'title' => $this->title
        ];
    }
}